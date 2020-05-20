<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $data = array([
            'uno' => 'hola'
        ]);
        $pdf = PDF::loadView('reports.reportone', $data);
        return $pdf->setPaper('ledger', 'portrait')->setWarnings(false)->stream('invoice.pdf');
    }
}
