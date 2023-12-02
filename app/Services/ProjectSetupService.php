<?php

namespace App\Services;

class ProjectSetupService
{
    public function migration()
    {
        $dots = ['.', '..', 'api-football', 'custom-football'];

        $paths = [
            'api-football' => 'api-football',
            'default' => '',
            'custom-football' => 'custom-football'
        ];

        $excepts = [
            'api-football' => [],
            'default' => [
                '2022_08_08_080612_create_league_user_table.php',
                '2022_08_24_100407_create_competition_user_table.php',
                '2022_09_20_051524_create_fixture_user_table.php', '2022_09_23_100905_create_fixture_prediction_table.php',
            ],
            'custom-football' => [],
        ];

        $migrations = [];

        foreach ($paths as $key => $value) {
            $migrations[] = collect(scandir(database_path('migrations' . ($key != 'default' ? ('/' . $key) : ''))))
                ->filter(function ($item) use ($dots, $excepts, $key) {
                    return !in_array($item, array_merge(array_merge($dots, $excepts[$key] ?? [])));
                })
                ->map(function ($item) use ($key, $value) {
                    return '/database/migrations' . ($key != 'default' ? ('/' . $key) : '') . '/' . $item;
                })
                ->flatten()
                ->toArray();
        }

        foreach ($excepts as $key => $values) {
            if (!empty($values)) {
                foreach ($values as $value) {
                    $migrations[] = '/database/migrations' . ($key != 'default' ? ('/' . $key) : '') . '/' . $value;
                }
            }
        }

        return collect($migrations)
            ->flatten()
            ->toArray();
    }
}
