<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Models\Programare;
use App\Models\Manopera;
use App\Models\User;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class MecanicBonusController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('programare_return_url');
        $search_data = Carbon::parse($request->search_data) ?? Carbon::today();

        switch ($request->input('schimbaLuna')) {
            case 'oLunaInapoi':
                $search_data = Carbon::parse($search_data)->subMonthNoOverflow();
                break;
            case 'oLunaInainte':
                $search_data = Carbon::parse($search_data)->addMonthNoOverflow();
                break;
        }

        $dataInceputLuna = Carbon::parse($search_data)->startOfMonth();
        $dataSfarsitLuna = Carbon::parse($search_data)->endOfMonth();

        $programari = Programare::with('user', 'manopere')
            ->when($search_data, function ($query, $search_data) {
                $query->where(function($query) use ($search_data){
                    $query->where(function($query) use ($search_data){
                        $query
                            // ->whereNull('data_ora_programare')
                            ->whereMonth('data_ora_finalizare', '=', $search_data->month)
                            ->whereYear('data_ora_finalizare', '=', $search_data->year);
                    });
                    $query->orwhere(function($query) use ($search_data){
                        $query->whereNull('data_ora_finalizare')
                            ->whereMonth('data_ora_programare', '=', $search_data->month)
                            ->whereYear('data_ora_programare', '=', $search_data->year);
                    });
                    // $query->orwhere(function($query) use ($search_data){
                    //     $query->whereDate('data_ora_programare', '<=', $search_data)
                    //         ->whereDate('data_ora_finalizare', '>=', $search_data);
                    // });
                })
                ->orderBy('data_ora_programare');
            })
            ->whereHas('manopere', function (Builder $query){
                $query->where('mecanic_id', auth()->user()->id);
            })
            ->get();

            return view('mecanici.bonusuri.index', compact('programari', 'search_data'));
    }

}
