<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;

class PageController extends Controller
{
    public function index(){
        return PageResource::collection(Page::all());
    }

    public function show(Page $page){
        return PageResource::make($page);
    }
}
