<?php
namespace App\Services;

use App\Mail\ResetPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public static function register(Request $request)
    {
        if($request->has('unique_id')) {
            $user = User::forceCreate([
                'unique_id' => $request->unique_id,
                'full_name' => $request->input('full_name'),
                'email' => $request->email,
                'username' => $request->username,
                'country_id' => $request->country,
            ]);
        }else{
            $user = User::forceCreate([
                'username' => $request->username,
                'full_name' => $request->input('full_name'),
                'email' => $request->email,
                'country_id' => $request->country,
                'password' => Hash::make($request->password),
                'dob' => $request->dob,
                'role' => 'public-user',
            ]);
        }

        static::sendEmail($request->email);
        $user->tokens()->delete();

        return $user;
    }

    public static function login(Request $request, $provider)
    {
        if($request->has('unique_id')) {
            $user = User::publicUser()->where('unique_id', $request->unique_id)->first();
        } else {
            if (is_null($provider)) {
                $user = User::publicUser()->isNotBlocked()->where(DB::raw('BINARY `' . ($request->filled('email') ? 'email' : 'username') . '`'), $request->login_with)->first();
                
                if (!$user || !Hash::check($request->password, $user->password)) {
                    throw ValidationException::withMessages([
                        'login_with' => trans('auth.failed'),
                    ]);
                }
            } else {
                $user = User::publicUser()->isNotBlocked()->whereProvider($provider)->whereProviderId($request->provider)->whereEmail($request->email)->first();
    
                if (!$user) {
                    $user = User::withTrashed()->whereEmail($request->email)->first();
                    if ($user) {
                        throw ValidationException::withMessages([
                            'email' => trans('validation.unique', ['attribute' => 'email']),
                        ]);
                    } else {
                        $user = User::forceCreate([
                            'email' => $request->email,
                            'full_name' => $request->input('full_name'),
                            'profile_picture' => $request->input('profile_picture'),
                            'provider' => $provider,
                            'provider_id' => $request->provider,
                            'role' => 'public-user',
                        ]);
                    }
                }
            }
        }

        if ($user) {
            return $user;
        } else {
            throw ValidationException::withMessages([
                'login_with' => trans('auth.failed'),
            ]);
        }
    }

    public static function sendEmail($email)
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->pin_code = rand(1111, 9999);
            $user->save();
            \Illuminate\Support\Facades\Mail::to($email)
                ->send(new ResetPassword($user));
            return response()->json(['message' => 'Email sent successfully']);
        } else {
            return response()->json(['message' => 'Email not found']);
        }

    }

    public static function changePassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }
        
        return $user;
    }

}
