<?php

namespace App\Scopes;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class SequentialLeagueScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $settings = Setting::where('type', 'like', 'leagues.%')
            ->get()
            ->mapWithKeys(function ($setting) {
                return [Str::remove('leagues.', $setting->type) => $setting->content];
            })
            ->undot()
            ->filter(function ($setting) {
                return optional($setting)['display_in_app'] && optional($setting)['appearance_order'];
            })
            ->sortBy('appearance_order')
            ->keys()
            ->toArray();

        if(! empty($settings)) {
            $builder->whereIn('id', $settings)->orderByRaw('FIELD(id, ' . implode(',', $settings) . ')');
        }
    }
}
