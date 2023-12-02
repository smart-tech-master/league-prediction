<?php

namespace App\Http\Controllers\API\CustomFootball;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomFootball\SearchRequest;
use App\Services\CustomFootball\SearchService;
use Illuminate\Http\Request;

class Search extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(SearchRequest $request)
    {
        return (new SearchService())->result($request);
    }
}
