<?php

namespace App\Http\Controllers\API\L10n;

use App\Http\Controllers\Controller;
use App\Models\Locale;
use App\Models\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function index(Request $request){
        //return TranslationResource::collection(Translation::all());
        $data = [];
        foreach (Translation::all() as $translation){
            if(in_array($translation->key, ['apptitle'])){
                $trans = [];
                foreach (Locale::all() as $locale) {
                    $trans = array_merge($trans, [$locale->code => $translation->translate('text', $locale->code)]);
                }
            }

            $data = array_merge($data, [$translation->key => $trans ?? $translation->translate('text', $request->header('X-L10n'))]);

            unset($trans);
        }

        return response()->json(['data' => $data]);
    }
}
