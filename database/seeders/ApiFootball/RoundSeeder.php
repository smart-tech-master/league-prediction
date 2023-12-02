<?php

namespace Database\Seeders\ApiFootball;

use App\Models\ApiFootball\Round;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoundSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rounds = [
            // World cup
            [
                'name' => 1,
                'alias' => 'Group Stage - 1',
                'slug' => 'group-stage-1',
                'keywords' => ['Group Stage - 1'],
                'sl' => 1,
                'league_id' => 1,
                'season_id' => 1,
            ],
            [
                'name' => 2,
                'alias' => 'Group Stage - 2',
                'slug' => 'group-stage-2',
                'keywords' => ['Group Stage - 2'],
                'sl' => 2,
                'league_id' => 1,
                'season_id' => 1,
            ],
            [
                'name' => 3,
                'alias' => 'Group Stage - 3',
                'slug' => 'group-stage-3',
                'keywords' => ['Group Stage - 3'],
                'sl' => 3,
                'league_id' => 1,
                'season_id' => 1,
            ],
            [
                'name' => 'Round of 16',
                'alias' => 'Round of 16',
                'slug' => 'round-of-16',
                'keywords' => [],
                'sl' => 4,
                'league_id' => 1,
                'season_id' => 1,
            ],
            [
                'name' => 'Quarter-finals',
                'alias' => 'Quarter-finals',
                'slug' => 'quarter-finals',
                'keywords' => [],
                'sl' => 5,
                'league_id' => 1,
                'season_id' => 1,
            ],
            [
                'name' => 'Semi-finals',
                'alias' => 'Semi-finals',
                'slug' => 'semi-finals',
                'keywords' => [],
                'sl' => 6,
                'league_id' => 1,
                'season_id' => 1,
            ],
            [
                'name' => '3rd Place',
                'alias' => '3rd Place',
                'slug' => '3rd-place',
                'keywords' => [],
                'sl' => 7,
                'league_id' => 1,
                'season_id' => 1,
            ],
            [
                'name' => 'Final',
                'alias' => 'Final',
                'slug' => 'final',
                'keywords' => [],
                'sl' => 8,
                'league_id' => 1,
                'season_id' => 1,
            ],

            // UEFA Champions League
            [
                'name' => 1,
                'alias' => 'Group Stage - 1',
                'slug' => 'group-stage-1',
                'keywords' => [
                    'Group A - 1',
                    'Group B - 1',
                    'Group C - 1',
                    'Group D - 1',
                    'Group E - 1',
                    'Group F - 1',
                    'Group G - 1',
                    'Group H - 1',
                ],
                'sl' => 1,
                'league_id' => 2,
                'season_id' => 1,
            ],
            [
                'name' => 2,
                'alias' => 'Group Stage - 2',
                'slug' => 'group-stage-2',
                'keywords' => [
                    'Group A - 2',
                    'Group B - 2',
                    'Group C - 2',
                    'Group D - 2',
                    'Group E - 2',
                    'Group F - 2',
                    'Group G - 2',
                    'Group H - 2',
                ],
                'sl' => 2,
                'league_id' => 2,
                'season_id' => 1,
            ],
            [
                'name' => 3,
                'alias' => 'Group Stage - 3',
                'slug' => 'group-stage-3',
                'keywords' => [
                    'Group A - 3',
                    'Group B - 3',
                    'Group C - 3',
                    'Group D - 3',
                    'Group E - 3',
                    'Group F - 3',
                    'Group G - 3',
                    'Group H - 3',
                ],
                'sl' => 3,
                'league_id' => 2,
                'season_id' => 1,
            ],
            [
                'name' => 4,
                'alias' => 'Group Stage - 4',
                'slug' => 'group-stage-4',
                'keywords' => [
                    'Group A - 4',
                    'Group B - 4',
                    'Group C - 4',
                    'Group D - 4',
                    'Group E - 4',
                    'Group F - 4',
                    'Group G - 4',
                    'Group H - 4',
                ],
                'sl' => 4,
                'league_id' => 2,
                'season_id' => 1,
            ],
            [
                'name' => 5,
                'alias' => 'Group Stage - 5',
                'slug' => 'group-stage-5',
                'keywords' => [
                    'Group A - 5',
                    'Group B - 5',
                    'Group C - 5',
                    'Group D - 5',
                    'Group E - 5',
                    'Group F - 5',
                    'Group G - 5',
                    'Group H - 5',
                ],
                'sl' => 5,
                'league_id' => 2,
                'season_id' => 1,
            ],
            [
                'name' => 6,
                'alias' => 'Group Stage - 6',
                'slug' => 'group-stage-6',
                'keywords' => [
                    'Group A - 6',
                    'Group B - 6',
                    'Group C - 6',
                    'Group D - 6',
                    'Group E - 6',
                    'Group F - 6',
                    'Group G - 6',
                    'Group H - 6',
                ],
                'sl' => 6,
                'league_id' => 2,
                'season_id' => 1,
            ],
            [
                'name' => 'Round of 16',
                'alias' => 'Round of 16(Part 1)',
                'slug' => 'round-of-16-part-1',
                'keywords' => [],
                'sl' => 7,
                'league_id' => 2,
                'season_id' => 1,
            ],
            [
                'name' => 'Round of 16',
                'alias' => 'Round of 16(Part 2)',
                'slug' => 'round-of-16-part-2',
                'keywords' => [],
                'sl' => 8,
                'league_id' => 2,
                'season_id' => 1,
            ],
            [
                'name' => 'Quarter-finals',
                'alias' => 'Quarter-finals(Part 1)',
                'slug' => 'quarter-finals-part-1',
                'keywords' => [],
                'sl' => 9,
                'league_id' => 2,
                'season_id' => 1,
            ],
            [
                'name' => 'Quarter-finals',
                'alias' => 'Quarter-finals(Part 2)',
                'slug' => 'quarter-finals-part-2',
                'keywords' => [],
                'sl' => 10,
                'league_id' => 2,
                'season_id' => 1,
            ],
            [
                'name' => 'Semi-finals',
                'alias' => 'Semi-finals(Part 1)',
                'slug' => 'semi-finals-part-1',
                'keywords' => [],
                'sl' => 11,
                'league_id' => 2,
                'season_id' => 1,
            ],
            [
                'name' => 'Semi-finals',
                'alias' => 'Semi-finals(Part 2)',
                'slug' => 'semi-finals-part-2',
                'keywords' => [],
                'sl' => 12,
                'league_id' => 2,
                'season_id' => 1,
            ],
            [
                'name' => 'Final',
                'alias' => 'Final',
                'slug' => 'final',
                'keywords' => [],
                'sl' => 13,
                'league_id' => 2,
                'season_id' => 1,
            ],
        ];

        // Premier League
        for ($i = 1; $i <= 38; $i++) {
            $rounds = array_merge($rounds, [
                [
                    'name' => $i,
                    'alias' => 'Regular Season - ' . $i,
                    'slug' => 'regular-season-' . $i,
                    'keywords' => ['Regular Season - ' . $i],
                    'sl' => $i,
                    'league_id' => 39,
                    'season_id' => 1,
                ],
            ]);
        }

        foreach ($rounds as $round) {
            Round::forceCreate($round);
        }
    }
}
