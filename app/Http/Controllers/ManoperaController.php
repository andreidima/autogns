<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Programare;
use App\Models\Manopera;

class ManoperaController extends Controller
{
    public function export(Request $request) {
        $search_data = $request->search_data ?? \Carbon\Carbon::now()->todatestring();

        switch ($request->input('action')) {
            case 'manopereOZi':
                $request->validate(['search_data' => 'required']);

                $search_data = $request->search_data;

                $manopere = Manopera::with('mecanic', 'programare')
                    ->whereHas('programare', function($query) use($search_data){
                        return $query->whereDate('data_ora_finalizare', '=', $search_data);
                    })
                    ->get();

                // return view('manopere.export.exportPDF.manopereOZi', compact('manopere', 'search_data'));
                $pdf = \PDF::loadView('manopere.export.exportPDF.manopereOZi', compact('manopere', 'search_data'))
                    ->setPaper('a4', 'portrait');
                $pdf->getDomPDF()->set_option("enable_php", true);
                // return $pdf->download('Contract ' . $comanda->transportator_contract . '.pdf');
                return $pdf->stream();
                break;

            default:
                return view('manopere.export.index', compact('search_data'));
                break;
            }
    }
}
