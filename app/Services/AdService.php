<?php
namespace App\Services;

use App\Http\Resources\AdResource;
use App\Models\Ad;
use App\Models\User;
use App\Notifications\AdCreated;
use Illuminate\Support\Facades\Notification;

class AdService{

    public function getAppInternalPages(){
        return [
            'my-rank' => 'Rank',
            'profile' => 'Profile',
            'contact-us' => 'Contact Us',
            'About-us' => 'About Us',
        ];
    }

    public function sendNotification(Ad $ad){
        if($ad->ad_type == 'banner' || ! is_null($ad->time_of_appearance)) {
            $users = User::publicUser()->whereBelongsTo($ad->country)->get();
            Notification::send($users, new AdCreated($ad));
            //$fcm = new \FCM();
            //$fcm::send('/topic/ads', AdResource::make($ad)->resolve());
        }
    }

    public static function adsAmongMatches($league, $country) {
        $ads = Ad::whereBelongsTo($country)->where('type', 'banner')->get();
        foreach ($ads as $key => $ad) {
            $ad->action_type = $league->type == "League" ? "create_league" : "create_cup";
        }

        return AdResource::collection($ads);
    }
}
