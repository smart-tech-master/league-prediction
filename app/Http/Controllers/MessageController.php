<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\ApiFootball\League;
use App\Models\Country;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use function Sodium\add;

class MessageController extends Controller
{
    public function index()
    {
        return view('messages.index')->withMessages(Message::latest()->get());
    }

    public function create()
    {
        return view('messages.create')
            ->withCountries(Country::orderBy('name')->get())
            ->withLeagues(League::all());
    }

    public function store(StoreMessageRequest $request)
    {
        $message = Message::forceCreate([
            'title' => $request->title,
            'text' => $request->input('text'),
            'picture' => $request->hasFile('picture') ? \FileUpload::put($request->picture) : null,
            'data' => [
                'send_to' => $request->send_to,
                $request->send_to => $request->input($request->send_to)
            ],
        ]);

        flash()->addSuccess('Message has been saved successfully.');

        return redirect()->back();
    }

    public function show(Message $message)
    {
        //$this->_print_r($message->data);
        $view = view('messages.show')->withMessage($message);

        if (optional($message->data)->send_to && optional($message->data)->{$message->data->send_to}) {
            switch ($message->data->send_to) {
                case 'countries':
                    $view = $view->withCountries(Country::orderBy('name')->whereIn('id', $message->data->{$message->data->send_to}));
                    break;
                case 'leagues':
                    $view = $view->withLeagues(League::whereIn('id', $message->data->{$message->data->send_to}));
                    break;
            }
        }

        return $view;
    }

    public function destroy(Message $message){
        $message->delete();

        flash()->addSuccess('Message has been deleted successfully.');

        return redirect()->back();
    }
}
