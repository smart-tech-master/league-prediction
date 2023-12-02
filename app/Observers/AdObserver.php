<?php

namespace App\Observers;

use App\Models\Ad;
use App\Services\AdService;

class AdObserver
{
    /**
     * Handle the Ad "created" event.
     *
     * @param  \App\Models\Ad  $ad
     * @return void
     */
    public function created(Ad $ad)
    {
        (new AdService())->sendNotification($ad);
    }

    /**
     * Handle the Ad "updated" event.
     *
     * @param  \App\Models\Ad  $ad
     * @return void
     */
    public function updated(Ad $ad)
    {
        (new AdService())->sendNotification($ad);
    }

    /**
     * Handle the Ad "deleted" event.
     *
     * @param  \App\Models\Ad  $ad
     * @return void
     */
    public function deleted(Ad $ad)
    {
        //
    }

    /**
     * Handle the Ad "restored" event.
     *
     * @param  \App\Models\Ad  $ad
     * @return void
     */
    public function restored(Ad $ad)
    {
        //
    }

    /**
     * Handle the Ad "force deleted" event.
     *
     * @param  \App\Models\Ad  $ad
     * @return void
     */
    public function forceDeleted(Ad $ad)
    {
        //
    }
}
