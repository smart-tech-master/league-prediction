<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('dashboard.index');
    }

    public function downloadUserChartAsPdf(Request $request){
        $data = [
            'sortBy' => $request->sort_by,
            'ageRange' => $request->age_range,
            'country' => $request->country,
            'userChart' => $request->user_chart,
        ];
        //$this->_print_r($data);
        $pdf = Pdf::loadView('dashboard.download-user-chart-as-pdf', $data);
        return $pdf->download();
    }
}
