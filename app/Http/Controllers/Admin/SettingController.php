<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
   

    public function index()
    {
        return view('admin.settings.index', [
            'settings' => Setting::orderBy('key')->get()
        ]);
    }

   public function edit()
    {
        $settings = Setting::all();
        return view('admin.settings.edit', compact('settings'));
    }
public function update(Request $request)
{
    foreach ($request->settings as $key => $value) {
    setting_set($key, $value);
}
    Cache::forget('settings');

    return back()->with('success', 'Settings updated successfully');
}
}
