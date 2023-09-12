<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Programare;
use App\Models\Manopera;

class ManoperaController extends Controller
{
    public function export(Request $request) {
        $search_data = $request->search_data ?? \Carbon\Carbon::now()->todatestring();

        $searchLuna = $request->searchLuna ?? Carbon::now()->subMonthNoOverflow()->isoFormat('MM');
        $searchAn = $request->searchAn ?? Carbon::now()->subMonthNoOverflow()->isoFormat('YYYY');

        $request->validate(['searchLuna' => 'numeric|between:1,12', 'searchAn' => 'numeric|between:2023,2040']);

        $searchData = Carbon::today();
        $searchData->day = 1;
        $searchData->month = $searchLuna;
        $searchData->year = $searchAn;
        $interval = $request->interval ?? (\Carbon\Carbon::today()->subMonthNoOverflow()->startOfMonth()->format('Y-m-d') . "," . \Carbon\Carbon::today()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d'));

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
                return $pdf->download('AutoGNS Manopere ' . \Carbon\Carbon::parse($search_data)->isoFormat('DD.MM.YYYY') . '.pdf');
                // return $pdf->stream();
                break;

            case 'manopereOLuna':
                $request->validate(['search_data' => 'required']);

                $search_data = $request->search_data;

                $manopere = Manopera::with('mecanic', 'programare')
                    ->whereHas('programare', function($query) use($search_data){
                        return $query->whereDate('data_ora_finalizare', '=', $search_data);
                    })
                    ->get();

                // return view('manopere.export.exportPDF.manopereOLuna', compact('manopere', 'search_data'));
                $pdf = \PDF::loadView('manopere.export.exportPDF.manopereOLuna', compact('manopere', 'search_data'))
                    ->setPaper('a4', 'portrait');
                $pdf->getDomPDF()->set_option("enable_php", true);
                return $pdf->download('AutoGNS Manopere ' . \Carbon\Carbon::parse($search_data)->isoFormat('DD.MM.YYYY') . '.pdf');
                // return $pdf->stream();
                break;

            default:
                return view('manopere.export.index', compact('search_data', 'interval'));
                break;
            }
    }
}
