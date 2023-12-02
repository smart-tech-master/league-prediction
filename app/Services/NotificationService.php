<?php

namespace App\Services;

use App\Models\ApiFootball\Round;
use App\Models\ApiFootball\Season;
use App\Models\Message;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\MessageCreated;
use App\Notifications\RoundNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function round($type)
    {
        $apiFootball = new \ApiFootball();

        $rounds = Round::whereBelongsTo(Season::first())->get()
            ->filter(function ($round) use ($type, $apiFootball) {
                if ($type == 'reminder' && ! is_null($round->started_at) && Carbon::now()->diffInMinutes(Carbon::parse($round->started_at), false) == 1440) { // 24 hours = 1440 minutes
                    return true;
                }elseif ($type == 'endOfRound' && ! is_null($round->ended_at) && Carbon::now()->diffInDays(Carbon::parse($round->ended_at), false) == 0){
                    $fixture = $round->fixtures()->whereTimestamp($round->ended_at)->whereNotNull('finished_at')->whereIn('short_status', $apiFootball::fixtureFinishedStates())->orderByDesc('finished_at')->first();
                    if($fixture && Carbon::now()->diffInMinutes(Carbon::parse($fixture->finished_at), false) == -1) {
                        return true;
                    }
                }
            });

        foreach ($rounds as $round) {
            $users = User::publicUser()->isNotBlocked()->whereReceiveNotifications(true)->get()
                ->filter(function ($user) use ($round) {
                    return $user->leagues()
                        ->wherePivot('league_id', $round->league->id)
                        ->wherePivot('season_id', $round->season->id)
                        ->first();
                });

            Notification::send($users, new RoundNotification($round, $type));
        }

        \Log::info('notification:round --type=' . $type);
    }

    public function custom(Message $message)
    {
        if (isset($message->data->send_to)) {

            $users = User::publicUser()->isNotBlocked()->whereReceiveNotifications(true);

            switch ($message->data->send_to) {
                case 'users':
                    $users = $users->get();
                    break;
                case 'countries':
                    $users = $users->whereIn('country_id', $message->data->{$message->data->send_to})->get();
                    break;
                case 'leagues':
                    $users = $users->get()
                        ->filter(function ($user) use ($message) {
                            return $user->leagues()->wherePivot('season_id', Season::first()->id)->get()->pluck('id')->intersect($message->data->{$message->data->send_to} ?? [])->isNotEmpty();
                        });
                    break;
            }

            if ($users->count()) {
                Notification::send($users, new MessageCreated($message));
            }
        }
    }

    public function toArray($object, $type = null)
    {
        $settings = collect(Setting::all());

        $data = [];

        if ($object instanceof Message) {
            $data = array_merge($data, [
                'object' => 'message',
                'title' => $object->title,
                'body' => $object->text,
                'image' => asset($object->picture),
            ]);
        } elseif ($object instanceof Round && !is_null($type)) {
            if ($type == 'reminder') {
                $data = array_merge($data, [
                    'object' => 'round-reminder',
                    'title' => $object->league->name . ' started at ' . $object->started_at,
                    'body' => $settings->firstWhere('type', 'body_text.reminder')->content ?? null,
                    'image' => asset($object->league->logo),
                ]);
            } elseif ($type == 'endOfRound') {
                $data = array_merge($data, [
                    'object' => 'round-endOfRound',
                    'title' => $object->league->name . ' ended at ' . $object->ended_at,
                    'body' => $settings->firstWhere('type', 'body_text.end_of_round')->content ?? null,
                    'image' => asset($object->league->logo),
                ]);
            }
        }

        return $data;
    }
}
