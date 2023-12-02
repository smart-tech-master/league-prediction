<?php

use Illuminate\Support\Facades\Route;

Route::get('/leagues', [\App\Http\Controllers\ApiFootballController::class, 'getLeagues'])->name('leagues.index');
Route::get('/leagues/{league}/seasons/{season}/fixtures', [\App\Http\Controllers\ApiFootballController::class, 'getFixtures'])->name('leagues.seasons.fixtures.index');

    Route::get('api-football', function (){
//                $response = Http::withHeaders([
//            'x-rapidapi-key' => env('X_RAPIDAPI_KEY'),
//            'x-rapidapi-host' => env('X_RAPIDAPI_HOST'),
//            'content-type' => 'application/json'
//        ])->baseUrl('https://' . env('X_RAPIDAPI_HOST') . '/v3/')->withoutVerifying()
//        ->get('fixtures?league=39&season=2021')->object();
//        //->get('teams?id=42')->object();
//        echo 'premier league 2022:';
//        echo '<pre>';print_r($response->response);echo '</pre>';


//        $response = Http::withHeaders([
//            'x-rapidapi-key' => env('X_RAPIDAPI_KEY'),
//            'x-rapidapi-host' => env('X_RAPIDAPI_HOST'),
//            'content-type' => 'application/json'
//        ])->baseUrl('https://' . env('X_RAPIDAPI_HOST') . '/v3/')->withoutVerifying()
//        ->get('fixtures/rounds?league=1&season=2014')->object();
//        //->get('teams?id=42')->object();
//        echo 'world cup 2014:';
//        echo '<pre>';print_r($response->response);echo '</pre>';
//        $response = Http::withHeaders([
//            'x-rapidapi-key' => env('X_RAPIDAPI_KEY'),
//            'x-rapidapi-host' => env('X_RAPIDAPI_HOST'),
//            'content-type' => 'application/json'
//        ])->baseUrl('https://' . env('X_RAPIDAPI_HOST') . '/v3/')->withoutVerifying()
//        ->get('fixtures/rounds?league=1&season=2018')->object();
//        //->get('teams?id=42')->object();
//        echo 'world cup 2018:';
//        echo '<pre>';print_r($response->response);echo '</pre>';
//        $response = Http::withHeaders([
//            'x-rapidapi-key' => env('X_RAPIDAPI_KEY'),
//            'x-rapidapi-host' => env('X_RAPIDAPI_HOST'),
//            'content-type' => 'application/json'
//        ])->baseUrl('https://' . env('X_RAPIDAPI_HOST') . '/v3/')->withoutVerifying()
//        ->get('fixtures/rounds?league=1&season=2022')->object();
//        //->get('teams?id=42')->object();
//        echo 'world cup 2022:';
//        echo '<pre>';print_r($response->response);echo '</pre>';
//        echo '----------------------------------------------------------------------------------<br/>';
//        $response = Http::withHeaders([
//            'x-rapidapi-key' => env('X_RAPIDAPI_KEY'),
//            'x-rapidapi-host' => env('X_RAPIDAPI_HOST'),
//            'content-type' => 'application/json'
//        ])->baseUrl('https://' . env('X_RAPIDAPI_HOST') . '/v3/')->withoutVerifying()
//        ->get('fixtures/rounds?league=2&season=2021')->object();
//        //->get('teams?id=42')->object();
//        echo 'uefa 2021:';
//        echo '<pre>';print_r($response->response);echo '</pre>';
//        $response = Http::withHeaders([
//            'x-rapidapi-key' => env('X_RAPIDAPI_KEY'),
//            'x-rapidapi-host' => env('X_RAPIDAPI_HOST'),
//            'content-type' => 'application/json'
//        ])->baseUrl('https://' . env('X_RAPIDAPI_HOST') . '/v3/')->withoutVerifying()
//        ->get('fixtures/rounds?league=2&season=2020')->object();
//        //->get('teams?id=42')->object();
//        echo 'uefa 2020:';
//        echo '<pre>';print_r($response->response);echo '</pre>';
//        $response = Http::withHeaders([
//            'x-rapidapi-key' => env('X_RAPIDAPI_KEY'),
//            'x-rapidapi-host' => env('X_RAPIDAPI_HOST'),
//            'content-type' => 'application/json'
//        ])->baseUrl('https://' . env('X_RAPIDAPI_HOST') . '/v3/')->withoutVerifying()
//        ->get('fixtures/rounds?league=2&season=2019')->object();
//        //->get('teams?id=42')->object();
//        echo 'uefa 2019:';
//        echo '<pre>';print_r($response->response);echo '</pre>';
//        echo '----------------------------------------------------------------------------------<br/>';
//
//
//        $response = Http::withHeaders([
//            'x-rapidapi-key' => env('X_RAPIDAPI_KEY'),
//            'x-rapidapi-host' => env('X_RAPIDAPI_HOST'),
//            'content-type' => 'application/json'
//        ])->baseUrl('https://' . env('X_RAPIDAPI_HOST') . '/v3/')->withoutVerifying()
//            ->get('fixtures/rounds?league=39&season=2021')->object();
//        //->get('teams?id=42')->object();
//        echo 'premier 2021:';
//        echo '<pre>';print_r($response->response);echo '</pre>';
//        $response = Http::withHeaders([
//            'x-rapidapi-key' => env('X_RAPIDAPI_KEY'),
//            'x-rapidapi-host' => env('X_RAPIDAPI_HOST'),
//            'content-type' => 'application/json'
//        ])->baseUrl('https://' . env('X_RAPIDAPI_HOST') . '/v3/')->withoutVerifying()
//            ->get('fixtures/rounds?league=39&season=2020')->object();
//        //->get('teams?id=42')->object();
//        echo 'premier 2020:';
//        echo '<pre>';print_r($response->response);echo '</pre>';
//        $response = Http::withHeaders([
//            'x-rapidapi-key' => env('X_RAPIDAPI_KEY'),
//            'x-rapidapi-host' => env('X_RAPIDAPI_HOST'),
//            'content-type' => 'application/json'
//        ])->baseUrl('https://' . env('X_RAPIDAPI_HOST') . '/v3/')->withoutVerifying()
//            ->get('fixtures/rounds?league=39&season=2019')->object();
//        //->get('teams?id=42')->object();
//        echo 'premier 2019:';
//        echo '<pre>';print_r($response->response);echo '</pre>';
    });



