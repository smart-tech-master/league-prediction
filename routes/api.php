<?php

use Illuminate\Support\Facades\Route;

Route::apiResources([
    'ads' => \App\Http\Controllers\API\AdController::class,
    'pages' => \App\Http\Controllers\API\PageController::class,
    'contacts' => \App\Http\Controllers\API\ContactController::class,
    'countries' => \App\Http\Controllers\API\CountryController::class,
    'settings' => \App\Http\Controllers\API\SettingController::class,
]);

Route::prefix('l10n')->group(function () {
    Route::apiResources([
        'locales' => \App\Http\Controllers\API\L10n\LocaleController::class,
        'translations' => \App\Http\Controllers\API\L10n\TranslationController::class,
    ]);
});

Route::prefix('api-football')->group(function () {
    Route::apiResources([
        'leagues' => \App\Http\Controllers\API\ApiFootball\LeagueController::class,
        'seasons' => \App\Http\Controllers\API\ApiFootball\SeasonController::class,
        'leagues.seasons.rounds' => \App\Http\Controllers\API\ApiFootball\RoundController::class,
        'leagues.seasons.rounds.fixtures' => \App\Http\Controllers\API\ApiFootball\FixtureController::class,
    ]);
});

Route::post('availability/{col}', [\App\Http\Controllers\API\AvailabilityController::class, 'index']);

//Auth routes
Route::post('/login/{provider?}', [App\Http\Controllers\API\AuthController::class, 'login'])->where('provider', 'google|apple');
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
Route::post('/password/email', [App\Http\Controllers\API\AuthController::class, 'sendResetLinkEmail']);
Route::post('/send-email/{email}', [App\Http\Controllers\API\AuthController::class, 'sendEmail']);
Route::post('/confirm-email/{pin_code}', [App\Http\Controllers\API\AuthController::class, 'confirmEmail']);
Route::post('/change-password', [App\Http\Controllers\API\AuthController::class, 'changePassword']);

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::prefix('account')->group(function () {
        Route::get('/profile', [App\Http\Controllers\API\AccountController::class, 'showProfileForm']);
        Route::put('/profile', [App\Http\Controllers\API\AccountController::class, 'updateProfile']);
        Route::put('/password', [App\Http\Controllers\API\AccountController::class, 'updatePassword']);
        Route::put('/settings', [App\Http\Controllers\API\AccountController::class, 'updateSettings']);
        Route::put('/device', [App\Http\Controllers\API\AccountController::class, 'updateDeviceDetails']);
        Route::delete('/', [App\Http\Controllers\API\AccountController::class, 'destroy']);
    });

    Route::apiResources([
        'subscriptions' => \App\Http\Controllers\API\SubscriptionController::class,
        'predictions' => \App\Http\Controllers\API\PredictionController::class,
        'ranks' => \App\Http\Controllers\API\RankController::class,
        'likings' => \App\Http\Controllers\API\LikingController::class,
        'users' => \App\Http\Controllers\API\UserController::class,
        'notifications' => \App\Http\Controllers\API\NotificationController::class,
    ]);

    Route::prefix('paginations')->group(function () {
        Route::prefix('ranks')->group(function () {
            Route::apiResources([
                'seasons.leagues' => \App\Http\Controllers\API\Paginations\Ranks\LeagueController::class,
            ]);
        });
        Route::apiResources([
            'users' => \App\Http\Controllers\API\Paginations\Users\UserController::class,
            'users.leagues' => \App\Http\Controllers\API\Paginations\Users\LeagueController::class,
            'users.leagues.rounds' => \App\Http\Controllers\API\Paginations\Users\RoundController::class,
            'users.leagues.rounds.fixtures' => \App\Http\Controllers\API\Paginations\Users\FixtureController::class,
        ]);
        Route::prefix('custom-football')->group(function () {
            Route::apiResources([
                'competitions.competitors' => \App\Http\Controllers\API\Paginations\CustomFootball\Competitions\CompetitorController::class,
            ]);
        });
    });

    Route::prefix('custom-football')->group(function () {
        Route::apiResources([
            'competitions' => \App\Http\Controllers\API\CustomFootball\CompetitionController::class,
            'competitions.chats' => \App\Http\Controllers\API\CustomFootball\ChatController::class,
            'leagues.seasons.rounds' => \App\Http\Controllers\API\CustomFootball\RoundController::class,
        ]);

        Route::post('/search', \App\Http\Controllers\API\CustomFootball\Search::class);
        Route::post('/competitions/{competition}/update', [App\Http\Controllers\API\CustomFootball\CompetitionController::class, 'update']);
        Route::get('/competitions/{competition}/unread-chat', [App\Http\Controllers\API\CustomFootball\ChatController::class, 'getUnreadChat']);
        Route::get('/competitions/{competition}/mark-as-read-chat', [App\Http\Controllers\API\CustomFootball\ChatController::class, 'markAsReadChat']);
    });

    // update
    
    Route::apiResource('leagues', \App\Http\Controllers\API\UserLeagueSettingController::class);

    Route::get('upcoming-matches', [App\Http\Controllers\API\ApiFootball\FixtureController::class, 'upcomingMatches']);

    Route::apiResources([
        'leagues.rounds.fixtures' => \App\Http\Controllers\API\ApiFootball\FixtureController::class,
    ]);

    // API route for logout user
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
});
