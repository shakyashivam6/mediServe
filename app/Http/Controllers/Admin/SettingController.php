<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SetConfig;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Keys editable from this screen, with their form label. This is the
     * "App Config" admin module from the roadmap — a thin editor over the
     * key/value settings already read by SetConfig::get() elsewhere in the
     * app (e.g. the site logo shared to every view in AppServiceProvider).
     */
    protected array $fields = [
        'SITENAME' => 'Site Name',
        'SITELOGO' => 'Site Logo Path',
        'SITEEMAIL' => 'Contact Email',
        'SITEPHONE' => 'Contact Phone',
        'SITEADDRESS' => 'Contact Address',
    ];

    public function edit()
    {
        $settings = collect($this->fields)->keys()
            ->mapWithKeys(fn (string $key) => [$key => SetConfig::get($key, '')]);

        return view('Admin.settings.edit', [
            'fields' => $this->fields,
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'SITENAME' => ['required', 'string', 'max:191'],
            'SITELOGO' => ['nullable', 'string', 'max:191'],
            'SITEEMAIL' => ['nullable', 'email', 'max:191'],
            'SITEPHONE' => ['nullable', 'string', 'max:30'],
            'SITEADDRESS' => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($data as $key => $value) {
            SetConfig::set($key, $value);
        }

        return redirect()->route('admin.settings.edit')->with('status', 'Settings updated.');
    }
}
