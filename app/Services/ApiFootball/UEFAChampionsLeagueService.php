<?php
namespace App\Services\ApiFootball;
use App\Models\ApiFootball\Round;
use App\Models\ApiFootball\Season;

class UEFAChampionsLeagueService
{
    protected $id = 2;
    protected $name = 'UEFA Champions League';

    protected $groupName = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
    protected $groupNum = 6;

    public function init() 
    {
        for ($num=1; $num <= $this->groupNum; $num++) {
            $this->create($num);
        }
    }

    public function create($groupNum) 
    {
        Round::forceCreate([
            'name' => 'Group - '.$groupNum,
            'alias' => 'Group - '.$groupNum,
            'slug' => 'Group - '.$groupNum,
            'keywords' => $this->getKeywords($groupNum, ['league' => 2, 'season' => Season::first()->year]),
            'sl' => $groupNum,
            'league_id' => 2,
            'season_id' => 1
        ]);
    }

    public function getKeywords($groupNum, $params) {

        $keywords = [];
        
        $apiFootball = new \ApiFootball();

        foreach ($this->groupName as $key => $name) {
            $params['round'] = "Group {$name} - {$groupNum}";

            $response = $apiFootball::fixtures($params)->object()->response ?? [];

            $keywords[] = $params['round'];
            foreach ($response as $key => $value) {
                $keywords[] = $value->fixture->id;
            }
        }

        return $keywords;
    }
}
