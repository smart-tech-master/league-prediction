<?php

namespace App\Http\Controllers\API\L10n;

use App\Http\Controllers\Controller;
use App\Http\Resources\L10n\LocaleResource;
use App\Models\Locale;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function index(){
        return LocaleResource::collection(Locale::all());
    }
}
