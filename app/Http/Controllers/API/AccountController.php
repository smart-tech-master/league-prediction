<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\UpdateDeviceRequest;
use App\Http\Requests\Account\UpdatePasswordRequest;
use App\Http\Requests\Account\UpdateProfileRequest;
use App\Http\Requests\Account\UpdateSettingsRequest;
use App\Http\Resources\Account\ProfileResource;
use App\Models\Locale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function showProfileForm()
    {
        return ProfileResource::make(auth()->user());
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        if ($request->hasFile('profile_picture')) {
            $request->user()->profile_picture = \FileUpload::put($request->profile_picture);
        }

        if($request->filled('username')){
            $request->user()->username = $request->username;
        }

        if($request->filled('country')){
            $request->user()->country_id = $request->country;
        }

        if($request->filled('dob')){
            $request->user()->dob = $request->dob;
        }

        if($request->filled('full_name')){
            $request->user()->full_name = $request->input('full_name');
        }

        if($request->filled('bio')){
            $request->user()->bio = utf8_encode($request->bio);
        }

        $request->user()->email = $request->email;
        $request->user()->update();

        return ProfileResource::make($request->user())->additional(['message' => __('Profile has been updated successfully.')]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $request->user()->password = Hash::make($request->new_password);
        $request->user()->update();

        return ProfileResource::make($request->user())->additional(['message' => __('Password has been updated successfully.')]);
    }

    public function updateSettings(UpdateSettingsRequest $request)
    {
        $request->user()->receive_notifications = $request->receive_notifications;
        $request->user()->locale_id = Locale::whereCode($request->l10n)->firstOrFail()->id;
        $request->user()->update();

        return ProfileResource::make($request->user())->additional(['message' => __('Settings has been updated successfully.')]);
    }

    public function updateDeviceDetails(UpdateDeviceRequest $request){

        $request->user()->device_token = $request->device_token;
        $request->user()->device_platform = $request->device_platform;
        $request->user()->update();

        return ProfileResource::make($request->user())->additional(['message' => __('Device has been updated successfully.')]);
    }

    public function destroy(Request $request){
        // $request->user()->blocked_at = Carbon::now();
        $request->user()->remove($request->user()->id);

        return response()->json(['message' => __('Account has been deleted successfully.')]);
    }
}
