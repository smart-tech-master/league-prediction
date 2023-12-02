<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Translation extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $translatable = ['text'];

    public static function getGroup(string $group, string $locale): array
    {
        return static::query()->where('key', 'LIKE', "{$group}.%")->get()
            ->map(function (Translation $translation) use ($locale, $group) {

                $key = preg_replace("/{$group}\\./", '', $translation->key, 1);
                $text = $translation->translate('text', $locale);

                return compact('key', 'text');

            })
            ->pluck('text', 'key')
            ->toArray();
    }
}
