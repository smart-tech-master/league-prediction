<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return NotificationResource::collection($request->user()->notifications()->get());
    }

    public function show(DatabaseNotification $notification){

        $notification->markAsRead();

        return NotificationResource::make($notification);
    }
}
