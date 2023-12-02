<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function __construct(private User $model)
    {

    }
    //Customer Register API
    public function register(RegisterRequest $request)
    {
        $user = AuthService::register($request);

        return AuthResource::make($user);
    }

    //Login API
    public function login(LoginRequest $request, $provider = null)
    { 
        $user = AuthService::login($request, $provider);

        return AuthResource::make($user);
    }

    public function sendResetLinkEmail(Request $request)
    {
        return (new ForgotPasswordController())->sendResetLinkEmail($request);
    }

    public function sendEmail($email)
    {
        return AuthService::sendEmail($email);
    }

    public function confirmEmail($pin_code)
    {
        $user = $this->model->where('pin_code', $pin_code)->first();
        if ($user) {
            $user->email_verified_at = now();
            $user->pin_code = "verified";

            $user->save();
            return response()->json(['message' => 'Email verified successfully']);
        } else {
            return response()->json(['message' => 'Invalid pin code']);
        }

    }

    public function changePassword(Request $request) {
        $user = AuthService::changePassword($request);

        return AuthResource::make($user);
    }

}
