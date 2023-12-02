<?php

namespace App\View\Composers;

use App\Models\Page;
use Illuminate\View\View;

class PageComposer
{
    protected $pages;

    public function __construct(Page $pages)
    {
        $this->pages = $pages->get();
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('pages', $this->pages);
    }
}
