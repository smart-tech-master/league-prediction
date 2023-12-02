<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Resources\Account\ProfileResource;
use App\Services\SubscriptionService;

class SubscriptionController extends Controller
{
    public function store(SubscriptionRequest $request)
    {
        return (new SubscriptionService())->store($request);
    }
}
