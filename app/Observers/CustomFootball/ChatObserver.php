<?php

namespace App\Observers\CustomFootball;

use App\Models\CustomFootball\Chat;
use App\Notifications\ChatCreated;
use Illuminate\Support\Facades\Notification;

class ChatObserver
{
    /**
     * Handle the Chat "created" event.
     *
     * @param  \App\Models\CustomFootball\Chat  $chat
     * @return void
     */
    public function created(Chat $chat)
    {
        Notification::send($chat->competition->competitors()->get(), new ChatCreated($chat));
//        if(! in_array($chat->competition->user->id, $chat->competition->competitors()->get()->pluck('id')->toArray())){
//            $chat->competition->user->notify(new ChatCreated($chat));
//        }
    }

    /**
     * Handle the Chat "updated" event.
     *
     * @param  \App\Models\CustomFootball\Chat  $chat
     * @return void
     */
    public function updated(Chat $chat)
    {
        //
    }

    /**
     * Handle the Chat "deleted" event.
     *
     * @param  \App\Models\CustomFootball\Chat  $chat
     * @return void
     */
    public function deleted(Chat $chat)
    {
        //
    }

    /**
     * Handle the Chat "restored" event.
     *
     * @param  \App\Models\CustomFootball\Chat  $chat
     * @return void
     */
    public function restored(Chat $chat)
    {
        //
    }

    /**
     * Handle the Chat "force deleted" event.
     *
     * @param  \App\Models\CustomFootball\Chat  $chat
     * @return void
     */
    public function forceDeleted(Chat $chat)
    {
        //
    }
}
