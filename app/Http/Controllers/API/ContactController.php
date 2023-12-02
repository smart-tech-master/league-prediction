<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Resources\ContactResource;
use App\Http\Resources\PageResource;
use App\Models\Contact;
use App\Models\Page;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request){
        $contact = Contact::forceCreate([
            'name' => $request->input('name', null),
            'email' => $request->input('email', null),
            'phone' => $request->input('phone', null),
            'message' => $request->input('message', null),
            'user_id' => $request->user('sanctum')->id ?? null,
        ]);

        return ContactResource::make($contact)->additional(['message' => __('Data has been inserted successfully.')]);
    }
}
