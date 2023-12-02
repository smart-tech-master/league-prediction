<?php

namespace App\Http\Resources\L10n;

use App\Models\Locale;
use Illuminate\Http\Resources\Json\JsonResource;

class TranslationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(in_array($this->key, ['apptitle'])){
            $trans = [];

            foreach (Locale::all() as $locale){
                $trans = array_merge($trans, [$locale->code => $this->translate('text', $locale->code)]);
            }

            return [$this->key => $trans];
        }

        return [
            $this->key => $this->translate('text', $request->header('X-L10n')),
        ];
    }
}
