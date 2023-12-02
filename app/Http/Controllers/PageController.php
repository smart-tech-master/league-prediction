<?php

namespace App\Http\Controllers;

use App\Http\Requests\PageRequest;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function edit(Page $page){
        return view('pages.edit')
            ->withPage($page);
    }

    public function update(PageRequest $request, Page $page){
        if ($request->hasFile('picture')) {
            $page->picture = \FileUpload::put($request->picture);
        }

        $page->content = $request->input('content');
        $page->update();

        flash()->addSuccess('Page has been updated successfully.');

        return redirect()->back();
    }
}
