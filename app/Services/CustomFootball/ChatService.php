<?php

namespace App\Services\CustomFootball;

use App\Models\CustomFootball\Competition;

class ChatService
{
    public static function getUnreadComments($user, $competition) {
        // echo $user->id;exit;
        // echo $competition->id;exit;
        $comments = $competition->chats()->where('user_id', '!=', $user->id)->latest()->get()
            ->filter(function($comment) use ($user) {
                return !self::readStatus($user, $comment);
            });

        return $comments;
    }

    public static function readStatus($user, $comment) {
        return !is_null( $comment->read_users ) && in_array( $user->id, json_decode( $comment->read_users ) );
    }

    public static function markAsReadChat($user, $competition) {
        return self::getUnreadComments($user, $competition)->map( function($comment) use ($user) {
            $read_users = is_null($comment->read_users) ? [] : json_decode($comment->read_users) ;
            $comment->read_users = json_encode(array_merge($read_users , [$user->id]));
            $comment->save();
            
            return $comment;
        });
    }

}
