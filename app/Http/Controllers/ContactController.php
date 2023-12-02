<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(){
        return view('contacts.index')->withContacts(Contact::latest()->get());
    }

    public function show(Contact $contact){
        return view('contacts.show')->withContact($contact);
    }

    public function destroy(Contact $contact){
        $contact->delete();

        flash()->addSuccess('Contact has been deleted successfully.');

        return redirect()->back();
    }
}
