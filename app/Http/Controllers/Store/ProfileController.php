<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Self-service edit of the logged-in Store's own owner details + shop
     * details. Deliberately narrower than what Admin can edit on this same
     * record (Admin\StoreController): login_id, Aadhaar/PAN, gender and DOB
     * are KYC/identity fields fixed at approval time — a Store can't quietly
     * change those itself, only Admin can.
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        return view('Store.profile.edit', [
            'user' => $user,
            'store' => $user->store,
            'locations' => Location::query()->select('id', 'name', 'parent_id')->get(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        // A blank password field submits as "", not absent — normalize to
        // null so `nullable` actually means "leave unchanged".
        $request->merge(['password' => $request->filled('password') ? $request->input('password') : null]);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'second_name' => ['required', 'string', 'max:100'],
            'mobile' => ['required', 'string', 'max:15', Rule::unique('users', 'mobile')->ignore($user->id)],
            'email' => ['required', 'email', 'max:191'],
            'state' => ['required', 'integer', 'exists:locations,id'],
            'city' => ['required', 'integer', 'exists:locations,id'],
            'pincode' => ['required', 'digits:6'],
            'address_line' => ['nullable', 'string', 'max:500'],
            'password' => ['nullable', 'string', 'min:6'],
            'shop_name' => ['required', 'string', 'max:191'],
            'license_no' => ['nullable', 'string', 'max:100'],
            'gst_no' => ['nullable', 'string', 'max:100'],
            // Letters/digits only — this becomes the literal "PREFIX-000123"
            // Order ID text (see Prescription::generateOrderNumber), so
            // spaces/punctuation would just make it awkward to read/say out
            // loud on a call.
            'order_prefix' => ['nullable', 'string', 'max:10', 'alpha_num'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'delivery_radius_km' => ['nullable', 'integer', 'min:0', 'max:65535'],
            'delivery_speed_kmph' => ['nullable', 'integer', 'min:0', 'max:65535'],
        ]);

        $user->update([
            'first_name' => $data['first_name'],
            'second_name' => $data['second_name'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'city' => $data['city'],
            'state' => $data['state'],
            'pincode' => $data['pincode'],
            'address_line' => $data['address_line'] ?? null,
            ...(isset($data['password']) ? ['password' => Hash::make($data['password'])] : []),
        ]);

        $user->store->update([
            'shop_name' => $data['shop_name'],
            'license_no' => $data['license_no'] ?? null,
            'gst_no' => $data['gst_no'] ?? null,
            'order_prefix' => isset($data['order_prefix']) ? strtoupper($data['order_prefix']) : null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'delivery_radius_km' => $data['delivery_radius_km'] ?? null,
            'delivery_speed_kmph' => $data['delivery_speed_kmph'] ?? null,
        ]);

        return redirect()->route('store.profile.edit')->with('status', 'Profile updated.');
    }
}
