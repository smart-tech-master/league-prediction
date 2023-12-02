<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

class PinCodeController extends Controller
{
    public function notify()
    {
      return view('auth.pin-code-notification');
    }
}