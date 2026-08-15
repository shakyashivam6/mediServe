<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class StoreController extends Controller
{
    /**
     * List Stores. Renders the page on a normal GET; on the DataTables ajax
     * GET it returns the server-side JSON payload instead.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Store::query()->with('user'))
                ->addColumn('shop_name', fn (Store $store) => $store->shop_name)
                ->addColumn('owner', fn (Store $store) => $store->user->first_name.' '.$store->user->second_name)
                ->addColumn('mobile', fn (Store $store) => $store->user->mobile)
                ->addColumn('status', function (Store $store) {
                    $variant = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$store->status];

                    return '<span class="badge bg-'.$variant.'">'.ucfirst($store->status).'</span>';
                })
                ->addColumn('actions', function (Store $store) {
                    $editUrl = route('admin.stores.edit', $store);
                    $buttons = '<a href="'.$editUrl.'" class="btn btn-soft-primary btn-sm me-1"><i class="ri-pencil-line"></i></a>';

                    if ($store->status === 'pending' && auth()->user()->can('stores.approve')) {
                        $buttons .= '<form method="POST" action="'.route('admin.stores.approve', $store).'" class="d-inline me-1">'
                            .csrf_field()
                            .'<button type="submit" class="btn btn-soft-success btn-sm"><i class="ri-check-line"></i></button>'
                            .'</form>';
                        $buttons .= '<form method="POST" action="'.route('admin.stores.reject', $store).'" class="d-inline">'
                            .csrf_field()
                            .'<button type="submit" class="btn btn-soft-danger btn-sm"><i class="ri-close-line"></i></button>'
                            .'</form>';
                    }

                    return $buttons;
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('Admin.stores.index');
    }

    /**
     * Show the form for creating a new Store.
     */
    public function create()
    {
        abort_unless($this->canManage(), 403);

        return view('Admin.stores.create', [
            'nextLoginId' => User::generateLoginId(),
            'locations' => $this->locations(),
        ]);
    }

    /**
     * Store a newly created Store — one User (role=store) + one Store row.
     */
    public function store(Request $request)
    {
        abort_unless($this->canManage(), 403);

        $data = $this->validateRequest($request);

        $user = User::create([
            'first_name' => $data['first_name'],
            'second_name' => $data['second_name'],
            'login_id' => $data['login_id'],
            'mobile' => $data['mobile'],
            'adhaar' => $data['adhaar'],
            'pan' => $data['pan'],
            'email' => $data['email'],
            'gender' => $data['gender'],
            'otp' => '123456',
            'dob' => $data['dob'],
            'city' => $data['city'],
            'state' => $data['state'],
            'pincode' => $data['pincode'],
            'address' => null,
            'address_line' => $data['address_line'] ?? null,
            'role' => 'store',
            'isActive' => false,
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        $user->store()->create([
            'shop_name' => $data['shop_name'],
            'license_no' => $data['license_no'] ?? null,
            'gst_no' => $data['gst_no'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'delivery_radius_km' => $data['delivery_radius_km'] ?? null,
            'delivery_speed_kmph' => $data['delivery_speed_kmph'] ?? null,
        ]);

        return redirect()->route('admin.stores.index')->with('status', "Store \"{$data['shop_name']}\" created — pending approval.");
    }

    /**
     * Show the form for editing a Store.
     */
    public function edit(Store $store)
    {
        abort_unless($this->canManage(), 403);

        return view('Admin.stores.edit', [
            'store' => $store,
            'user' => $store->user,
            'locations' => $this->locations(),
        ]);
    }

    /**
     * Update a Store's user + shop details.
     */
    public function update(Request $request, Store $store)
    {
        abort_unless($this->canManage(), 403);

        $data = $this->validateRequest($request, $store->user);

        $store->user->update([
            'first_name' => $data['first_name'],
            'second_name' => $data['second_name'],
            'login_id' => $data['login_id'],
            'mobile' => $data['mobile'],
            'adhaar' => $data['adhaar'],
            'pan' => $data['pan'],
            'email' => $data['email'],
            'gender' => $data['gender'],
            'dob' => $data['dob'],
            'city' => $data['city'],
            'state' => $data['state'],
            'pincode' => $data['pincode'],
            'address_line' => $data['address_line'] ?? null,
            ...(isset($data['password']) ? ['password' => Hash::make($data['password'])] : []),
        ]);

        $store->update([
            'shop_name' => $data['shop_name'],
            'license_no' => $data['license_no'] ?? null,
            'gst_no' => $data['gst_no'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'delivery_radius_km' => $data['delivery_radius_km'] ?? null,
            'delivery_speed_kmph' => $data['delivery_speed_kmph'] ?? null,
        ]);

        return redirect()->route('admin.stores.index')->with('status', "Store \"{$store->shop_name}\" updated.");
    }

    /**
     * Deactivate a Store rather than deleting it outright — it may already
     * have orders/Captains attached once those modules exist.
     */
    public function destroy(Store $store)
    {
        abort_unless($this->canManage(), 403);

        $store->user->update(['isActive' => false]);

        return redirect()->route('admin.stores.index')->with('status', "Store \"{$store->shop_name}\" deactivated.");
    }

    /**
     * Approve a pending Store — activates its login.
     */
    public function approve(Store $store)
    {
        abort_unless(auth()->user()->can('stores.approve'), 403);

        $store->update(['status' => 'approved']);
        $store->user->update(['isActive' => true]);

        return redirect()->route('admin.stores.index')->with('status', "Store \"{$store->shop_name}\" approved.");
    }

    /**
     * Reject a pending Store — login stays inactive.
     */
    public function reject(Store $store)
    {
        abort_unless(auth()->user()->can('stores.approve'), 403);

        $store->update(['status' => 'rejected']);
        $store->user->update(['isActive' => false]);

        return redirect()->route('admin.stores.index')->with('status', "Store \"{$store->shop_name}\" rejected.");
    }

    protected function canManage(): bool
    {
        return auth()->user()->can('stores.manage');
    }

    /**
     * Shared validation for both create and update. $existingUser lets the
     * unique rules ignore the record being edited.
     */
    protected function validateRequest(Request $request, ?User $existingUser = null): array
    {
        $isUpdate = $existingUser !== null;

        // A blank password field submits as "", not absent — normalize to
        // null so `nullable` on update actually means "leave unchanged".
        $request->merge(['password' => $request->filled('password') ? $request->input('password') : null]);

        return $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'second_name' => ['required', 'string', 'max:100'],
            'login_id' => ['required', 'string', 'max:20', Rule::unique('users', 'login_id')->ignore($existingUser?->id)],
            'mobile' => ['required', 'string', 'max:15', Rule::unique('users', 'mobile')->ignore($existingUser?->id)],
            'adhaar' => ['required', 'string', 'max:20'],
            'pan' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:191'],
            'gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'dob' => ['required', 'date'],
            'state' => ['required', 'integer', 'exists:locations,id'],
            'city' => ['required', 'integer', 'exists:locations,id'],
            'pincode' => ['required', 'digits:6'],
            'address_line' => ['nullable', 'string', 'max:500'],
            'password' => [$isUpdate ? 'nullable' : 'required', 'string', 'min:6'],
            'shop_name' => ['required', 'string', 'max:191'],
            'license_no' => ['nullable', 'string', 'max:100'],
            'gst_no' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'delivery_radius_km' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'delivery_speed_kmph' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);
    }

    /**
     * All locations for the State/City cascading selects — small enough
     * (~700 rows) to ship once and filter client-side in JS.
     */
    protected function locations()
    {
        return Location::query()->select('id', 'name', 'parent_id')->get();
    }
}
