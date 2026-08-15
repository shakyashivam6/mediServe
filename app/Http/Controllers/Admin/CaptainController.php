<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class CaptainController extends Controller
{
    protected array $vehicleTypes = ['bike', 'scooter', 'cycle', 'other'];

    /**
     * List Captains. Renders the page on a normal GET; on the DataTables
     * ajax GET it returns the server-side JSON payload instead.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(User::query()->where('role', 'captain')->with('parentStore'))
                ->addColumn('name', fn (User $user) => $user->first_name.' '.$user->second_name)
                ->addColumn('store', fn (User $user) => $user->parentStore->store->shop_name ?? '—')
                ->addColumn('vehicle_type', fn (User $user) => ucfirst($user->vehicle_type ?? '—'))
                ->addColumn('status', fn (User $user) => $user->isActive
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>')
                ->addColumn('actions', function (User $user) {
                    $editUrl = route('admin.captains.edit', $user);
                    $toggleLabel = $user->isActive ? 'Deactivate' : 'Activate';
                    $toggleIcon = $user->isActive ? 'ri-forbid-line' : 'ri-check-line';

                    return '<a href="'.$editUrl.'" class="btn btn-soft-primary btn-sm me-1"><i class="ri-pencil-line"></i></a>'
                        .'<form method="POST" action="'.route('admin.captains.destroy', $user).'" class="d-inline" title="'.$toggleLabel.'">'
                        .csrf_field().method_field('DELETE')
                        .'<button type="submit" class="btn btn-soft-warning btn-sm"><i class="'.$toggleIcon.'"></i></button>'
                        .'</form>';
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('Admin.captains.index');
    }

    /**
     * Show the form for creating a new Captain.
     */
    public function create()
    {
        return view('Admin.captains.create', [
            'nextLoginId' => User::generateLoginId(),
            'locations' => $this->locations(),
            'stores' => $this->approvedStores(),
            'vehicleTypes' => $this->vehicleTypes,
        ]);
    }

    /**
     * Store a newly created Captain.
     */
    public function store(Request $request)
    {
        $data = $this->validateRequest($request);

        User::create([
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
            'role' => 'captain',
            'store_id' => $data['store_id'],
            'vehicle_type' => $data['vehicle_type'] ?? null,
            'isActive' => true,
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.captains.index')->with('status', "Captain \"{$data['first_name']} {$data['second_name']}\" created.");
    }

    /**
     * Show the form for editing a Captain.
     */
    public function edit(User $captain)
    {
        abort_unless($captain->role === 'captain', 404);

        return view('Admin.captains.edit', [
            'user' => $captain,
            'locations' => $this->locations(),
            'stores' => $this->approvedStores(),
            'vehicleTypes' => $this->vehicleTypes,
        ]);
    }

    /**
     * Update a Captain.
     */
    public function update(Request $request, User $captain)
    {
        abort_unless($captain->role === 'captain', 404);

        $data = $this->validateRequest($request, $captain);

        $captain->update([
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
            'store_id' => $data['store_id'],
            'vehicle_type' => $data['vehicle_type'] ?? null,
            ...(isset($data['password']) ? ['password' => Hash::make($data['password'])] : []),
        ]);

        return redirect()->route('admin.captains.index')->with('status', "Captain \"{$captain->first_name} {$captain->second_name}\" updated.");
    }

    /**
     * Toggle a Captain's active state rather than deleting the account.
     */
    public function destroy(User $captain)
    {
        abort_unless($captain->role === 'captain', 404);

        $captain->update(['isActive' => ! $captain->isActive]);

        $status = $captain->isActive ? 'activated' : 'deactivated';

        return redirect()->route('admin.captains.index')->with('status', "Captain \"{$captain->first_name} {$captain->second_name}\" {$status}.");
    }

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
            'store_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', 'store')],
            'vehicle_type' => ['nullable', Rule::in($this->vehicleTypes)],
        ]);
    }

    /**
     * Stores a Captain can be linked to — approved ones only, since a
     * pending/rejected Store shouldn't be receiving deliveries yet.
     */
    protected function approvedStores()
    {
        return User::query()
            ->where('role', 'store')
            ->whereHas('store', fn ($q) => $q->where('status', 'approved'))
            ->with('store')
            ->orderBy('first_name')
            ->get();
    }

    protected function locations()
    {
        return Location::query()->select('id', 'name', 'parent_id')->get();
    }
}
