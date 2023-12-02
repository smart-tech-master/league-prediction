<?php

namespace App\Http\Controllers\L10n;

use App\Http\Controllers\Controller;
use App\Http\Requests\L10n\StoreTranslationRequest;
use App\Http\Requests\L10n\UpdateTranslationRequest;
use App\Models\Locale;
use App\Models\Translation;

class TranslationController extends Controller
{

    public function index()
    {
        return view('l10n.translations.index')->withTranslations(Translation::all());
    }

    public function create()
    {
        return view('l10n.translations.create')->withLocales(Locale::all());
    }

    public function store(StoreTranslationRequest $request)
    {
        $translation = new Translation();
        $translation->key = $request->key;
        foreach (Locale::all() as $locale) {
            $translation->setTranslation('text', $locale->code, $request->input('text.' . $locale->code));
        }
        $translation->save();

        flash()->addSuccess('Translation has been saved successfully.');

        return redirect()->back();

    }

    public function edit(Translation $translation)
    {
        return view('l10n.translations.edit')->withTranslation($translation)
            ->withLocales(Locale::all());
    }


    public function update(UpdateTranslationRequest $request, Translation $translation)
    {
        //$translation->key = $request->key;
        foreach (Locale::all() as $locale) {
            $translation->setTranslation('text', $locale->code, $request->input('text.' . $locale->code));
        }
        $translation->update();

        flash()->addSuccess('Translation has been updated successfully.');

        return redirect()->back();
    }


    public function destroy(Translation $translation)
    {
        $translation->forceDelete();

        return redirect()->back()->withSuccess('Translation has been deleted successfully.');
    }


}
