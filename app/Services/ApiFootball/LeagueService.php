<?php
namespace App\Services\ApiFootball;

use App\Models\ApiFootball\League;
use App\Models\ApiFootball\Season;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LeagueService{
    public function points(User $user, League $league, Season $season){
        return $user->predictions()->select('points')
            ->whereBelongsTo($league)->whereBelongsTo($season)
            ->sum('points');
    }

    public function init() {
        $apiFootball = new \ApiFootball();
        
        $params = ['season' => Season::first()->year];
        
        $response = $apiFootball::leagues($params)->object()->response ?? [];
        
        $this->truncate();

        $this->create($response);

        print_r("Okay, getting leagues is done");
    }

    public function create($response) {
        $country_id = 1;
        foreach ($response as $value) {
            League::forceCreate([
                'id' => $value->league->id,
                'name' => $value->league->name,
                'type' => $value->league->type,
                'logo' => $value->league->logo,
                'country_id' => $country_id
            ]);
        }
    }

    public function truncate() {
        DB::connection('api-football')->statement('SET FOREIGN_KEY_CHECKS=0');
        DB::connection('api-football')->table('leagues')->truncate();
        DB::connection('api-football')->statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
