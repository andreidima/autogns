<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Models\Programare;
use App\Models\Manopera;
use App\Models\User;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class MecanicProgramareController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('programariMecaniciReturnUrl');
        $search_data = \Request::get('search_data') ?? Carbon::today();
        switch ($request->input('schimba_ziua')) {
            case 'o_zi_inapoi':
                $search_data = \Carbon\Carbon::parse($search_data)->subDay()->toDateString();
                break;
            case 'o_zi_inainte':
                $search_data = \Carbon\Carbon::parse($search_data)->addDay()->toDateString();
                break;
        }

        $programari = Programare::with('user', 'manopere')
            ->when($search_data, function ($query, $search_data) {
                $query->where(function($query) use ($search_data){
                    $query->where(function($query) use ($search_data){
                        $query->whereNull('data_ora_programare')
                            ->whereDate('data_ora_finalizare', '=', $search_data);
                    });
                    $query->orwhere(function($query) use ($search_data){
                        $query->whereNull('data_ora_finalizare')
                            ->whereDate('data_ora_programare', '=', $search_data);
                    });
                    $query->orwhere(function($query) use ($search_data){
                        $query->whereDate('data_ora_programare', '<=', $search_data)
                            ->whereDate('data_ora_finalizare', '>=', $search_data);
                    });
                })
                ->orderBy('data_ora_programare');
            })
            ->whereHas('manopere', function (Builder $query){
                $query->where('mecanic_id', auth()->user()->id);
            })
            ->get();

            return view('mecanici.programari.index', compact('programari', 'search_data'));
    }

    public function modificareManopera(Request $request, Manopera $manopera)
    {
        if(auth()->user()->id !== $manopera->mecanic_id){
            echo "<br><h1 style='text-align:center'>Nu ai acces la această manoperă</h1>";
            dd();
        }

        $request->session()->get('programariMecaniciReturnUrl') ?? $request->session()->put('programariMecaniciReturnUrl', url()->previous());

        return view('mecanici.programari.modificareManopera', compact('manopera'));
    }

    public function postModificareManopera(Request $request, Manopera $manopera)
    {
        $manopera->update($request->validate(
            [
                'mecanic_timp' => 'nullable|max:2000',
                'mecanic_consumabile' => 'nullable|max:2000',
                'mecanic_observatii' => 'nullable|max:2000',
            ]
        ));

        return redirect($request->session()->get('programariMecaniciReturnUrl') ?? ('/mecanici/programari-mecanici'))
            ->with('status', 'Manopera pentru mașina „' . ($manopera->programare->masina ?? '') . '” a fost modificată cu succes!');
    }
}
