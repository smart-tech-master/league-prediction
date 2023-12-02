<?php

namespace App\Http\Controllers\AJAX;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function sortBy(Request $request)
    {
        $users = User::publicUser();

        if ($request->has('country') && $request->filled('country') && in_array('country', $request->sort_by)) {
            $users = $users->whereBelongsTo(Country::findOrFail($request->country));
        }

        if ($request->has('age_range') && $request->filled('age_range') && strpos($request->age_range, '-') !== false && in_array('age-range', $request->sort_by)) {
            $collection = collect(explode('-', $request->age_range));
            $users = $users->agedBetween($collection->first(), $collection->last());
        }

        if (in_array('new-users', $request->sort_by)) {
            $users = $users->whereDate('created_at', Carbon::today());
        }

        $users = $users->get();

        if (in_array('active-users', $request->sort_by)) {
            $users = $users->filter(function ($user) {
                return $user->leagues()->count() || $user->competitions()->count();
            });
        }

        $userChart = [
            'type' => 'bar',
            'data' => [
                'labels' => ['1 day', '1 week', '1 month', '1 year'],
                'datasets' => [
                    $users->whereBetween('created_at', [Carbon::now()->subDay(), Carbon::now()])->count(),
                    $users->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()])->count(),
                    $users->whereBetween('created_at', [Carbon::now()->subMonth(), Carbon::now()])->count(),
                    $users->whereBetween('created_at', [Carbon::now()->subYear(), Carbon::now()])->count(),
                    $users->count(),
                ],
                'backgroundColor' => [
                    '#48c4b9',
                    '#48c4b9',
                    '#48c4b9',
                    '#48c4b9',
                ],
                'borderRadius' => 50,
                'width' => 3
            ],
            'options' => [
                'barThickness' => 10,
                'scales' => [
                    'y' => [
                        'beginAtZero' => true
                    ]
                ]
            ]
        ];

        $doughnutChart = [
            'type' => 'doughnut',
            'data' => [
                'datasets' => [
                    [
                        'labels' => ['1 day', '1 week', '1 month', '1 year'],
                        'datasets' => [
                            $users->whereBetween('created_at', [Carbon::now()->subDay(), Carbon::now()])->count(),
                            $users->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()])->count(),
                            $users->whereBetween('created_at', [Carbon::now()->subMonth(), Carbon::now()])->count(),
                            $users->whereBetween('created_at', [Carbon::now()->subYear(), Carbon::now()])->count(),
                            $users->count(),
                        ],
                        'backgroundColor' => [
                            '#48c4b9',
                            '#48c4b9',
                            '#48c4b9',
                            '#48c4b9',
                        ],
                        ]
                ],
            ],
            'options' => []
        ];

        return response()->json([
            'data' => [
                'userChart' => $userChart,
                'doughnutChart' => $doughnutChart,
            ]
        ]);
    }
}
