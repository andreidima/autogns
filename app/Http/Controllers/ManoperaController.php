<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Programare;
use App\Models\Manopera;

use Carbon\Carbon;

class ManoperaController extends Controller
{
    public function export(Request $request) {
        // manopera o zi
        $search_data = $request->search_data ?? \Carbon\Carbon::now()->todatestring();

        // Manopera o luna
        $luna = $request->luna ?? Carbon::now()->subMonthNoOverflow()->isoFormat('MM');
        $an = $request->an ?? Carbon::now()->subMonthNoOverflow()->isoFormat('YYYY');

        switch ($request->input('action')) {
            case 'manopereOZi':
                $request->validate(['search_data' => 'required']);

                $search_data = $request->search_data;

                $manopere = Manopera::with('mecanic', 'programare')
                    ->whereHas('programare', function($query) use($search_data){
                        return $query->whereDate('data_ora_finalizare', '=', $search_data)
                                ->where('stare_masina', 3);
                    })
                    ->get();

                // return view('manopere.export.exportPDF.manopereOZi', compact('manopere', 'search_data'));
                $pdf = \PDF::loadView('manopere.export.exportPDF.manopereOZi', compact('manopere', 'search_data'))
                    ->setPaper('a4', 'portrait');
                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->download('AutoGNS Manopere ' . \Carbon\Carbon::parse($search_data)->isoFormat('DD.MM.YYYY') . '.pdf');
                // return $pdf->stream();
                break;

            case 'manopereOLuna':
                $request->validate(['luna' => 'required|numeric|between:1,12', 'an' => 'required|numeric|between:2023,2050']);

                $search_data = Carbon::today();
                $search_data->day = 1;
                $search_data->month = $luna;
                $search_data->year = $an;

                $manopere = Manopera::with('mecanic', 'programare')
                    ->whereHas('programare', function($query) use($search_data){
                        return $query->whereMonth('data_ora_finalizare', $search_data)
                                    ->whereYear('data_ora_finalizare', $search_data)
                                    ->where('stare_masina', 3);
                    })
                    ->get()
                    ->sortBy('programare.data');

                // $manopere = $manopere->sortBy('programare.data');

                // return view('manopere.export.exportPDF.manopereOLuna', compact('manopere', 'search_data'));
                $pdf = \PDF::loadView('manopere.export.exportPDF.manopereOLuna', compact('manopere', 'search_data'))
                    ->setPaper('a4', 'portrait');
                $pdf->getDomPDF()->set_option("enable_php", true);
                // return $pdf->download('AutoGNS Manopere ' . $luna . '.' . $an . '.pdf');
                return $pdf->stream();
                break;

            default:
                return view('manopere.export.index', compact('search_data', 'luna', 'an'));
                break;
            }
    }
}
