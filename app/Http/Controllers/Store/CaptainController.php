<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

/**
 * A Store's self-service Captain management — the same shape as
 * Admin\CaptainController, but store_id is always the logged-in Store
 * (never a picker) and every record is scoped to it, so one Store can never
 * see or touch another Store's Captains. Admin keeps its own Captain screen
 * for platform-wide oversight (see roadmap) — this doesn't replace it.
 */
class CaptainController extends Controller
{
    protected array $vehicleTypes = ['bike', 'scooter', 'cycle', 'other'];

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(User::query()->where('role', 'captain')->where('store_id', $request->user()->id))
                ->addColumn('name', fn (User $user) => $user->first_name.' '.$user->second_name)
                ->addColumn('login_id', fn (User $user) => $user->login_id)
                ->addColumn('vehicle_type', fn (User $user) => ucfirst($user->vehicle_type ?? '—'))
                ->addColumn('status', fn (User $user) => $user->isActive
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Inactive</span>')
                ->addColumn('actions', function (User $user) {
                    $editUrl = route('store.captains.edit', $user);
                    $toggleLabel = $user->isActive ? 'Deactivate' : 'Activate';
                    $toggleIcon = $user->isActive ? 'ri-forbid-line' : 'ri-check-line';

                    return '<a href="'.$editUrl.'" class="btn btn-soft-primary btn-sm me-1"><i class="ri-pencil-line"></i></a>'
                        .'<form method="POST" action="'.route('store.captains.destroy', $user).'" class="d-inline" title="'.$toggleLabel.'">'
                        .csrf_field().method_field('DELETE')
                        .'<button type="submit" class="btn btn-soft-warning btn-sm"><i class="'.$toggleIcon.'"></i></button>'
                        .'</form>';
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('Store.captains.index', [
            'storeApproved' => $request->user()->store?->status === 'approved',
        ]);
    }

    public function create(Request $request)
    {
        $this->ensureApproved($request);

        return view('Store.captains.create', [
            'nextLoginId' => User::generateLoginId(),
            'locations' => $this->locations(),
            'vehicleTypes' => $this->vehicleTypes,
        ]);
    }

    public function store(Request $request)
    {
        $this->ensureApproved($request);

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
            'store_id' => $request->user()->id,
            'vehicle_type' => $data['vehicle_type'] ?? null,
            'isActive' => true,
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('store.captains.index')->with('status', "Captain \"{$data['first_name']} {$data['second_name']}\" created.");
    }

    public function edit(Request $request, User $captain)
    {
        $this->authorizeCaptain($request, $captain);

        return view('Store.captains.edit', [
            'user' => $captain,
            'locations' => $this->locations(),
            'vehicleTypes' => $this->vehicleTypes,
        ]);
    }

    public function update(Request $request, User $captain)
    {
        $this->authorizeCaptain($request, $captain);

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
            'vehicle_type' => $data['vehicle_type'] ?? null,
            ...(isset($data['password']) ? ['password' => Hash::make($data['password'])] : []),
        ]);

        return redirect()->route('store.captains.index')->with('status', "Captain \"{$captain->first_name} {$captain->second_name}\" updated.");
    }

    /**
     * Toggle a Captain's active state rather than deleting the account.
     */
    public function destroy(Request $request, User $captain)
    {
        $this->authorizeCaptain($request, $captain);

        $captain->update(['isActive' => ! $captain->isActive]);

        $status = $captain->isActive ? 'activated' : 'deactivated';

        return redirect()->route('store.captains.index')->with('status', "Captain \"{$captain->first_name} {$captain->second_name}\" {$status}.");
    }

    /**
     * A Captain only belongs to this screen if it's role=captain AND
     * store_id matches the logged-in Store — stops one Store from editing
     * another Store's Captain by guessing a URL.
     */
    protected function authorizeCaptain(Request $request, User $captain): void
    {
        abort_unless($captain->role === 'captain' && $captain->store_id === $request->user()->id, 404);
    }

    /**
     * Mirrors Admin\CaptainController::approvedStores() — a Store can only
     * gain Captains once it's itself approved.
     */
    protected function ensureApproved(Request $request): void
    {
        abort_unless($request->user()->store?->status === 'approved', 403);
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
            'vehicle_type' => ['nullable', Rule::in($this->vehicleTypes)],
        ]);
    }

    protected function locations()
    {
        return Location::query()->select('id', 'name', 'parent_id')->get();
    }
}
