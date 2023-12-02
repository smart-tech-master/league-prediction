<?php
namespace App\View\Composers;

use App\Models\Country;
use Illuminate\View\View;

class CountryComposer{
    protected $countries;

    public function __construct(Country $country)
    {
        $this->countries = $country->get();
    }

    public function compose(View $view){
        $view->with('countries', $this->countries);
    }
}
