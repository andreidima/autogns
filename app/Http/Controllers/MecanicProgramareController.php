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

    public function modificareProgramare(Request $request, Programare $programare)
    {
        $request->session()->get('programariMecaniciReturnUrl') ?? $request->session()->put('programariMecaniciReturnUrl', url()->previous());

        return view('mecanici.programari.modificareProgramare', compact('programare'));
    }

    public function postModificareProgramare(Request $request, Programare $programare)
    {
        $programare->update($request->validate(
            [
                'km' => 'nullable|integer|between:1,9999999',
            ]
        ));

        return redirect(session('programariMecaniciReturnUrl') ?? ('/mecanici/programari-mecanici'))
            ->with('status', 'Programare pentru mașina „' . ($programare->masina ?? '') . '” a fost modificată cu succes!');
    }

    public function modificareManopera(Request $request, Manopera $manopera)
    {
        if(auth()->user()->id !== $manopera->mecanic_id){
            echo "<br><h1 style='text-align:center'>Nu ai acces la această manoperă</h1>";
            dd();
        }

        if ($manopera->programare->stare_masina == 3){
            return redirect(session('programariMecaniciReturnUrl') ?? ('/mecanici/programari-mecanici'))
                ->with('error', 'Programare pentru mașina „' . ($manopera->programare->masina ?? '') . '” este deja finalizată. Nu se mai pot adăuga sau modifica informații!');
        }

        $request->session()->get('programariMecaniciReturnUrl') ?? $request->session()->put('programariMecaniciReturnUrl', url()->previous());

        return view('mecanici.programari.modificareManopera', compact('manopera'));
    }

    public function postModificareManopera(Request $request, Manopera $manopera)
    {
        if ($manopera->programare->stare_masina == 3){
            return redirect(session('programariMecaniciReturnUrl') ?? ('/mecanici/programari-mecanici'))
                ->with('error', 'Programare pentru mașina „' . ($manopera->programare->masina ?? '') . '” este deja finalizată. Nu se mai pot adăuga sau modifica informații!');
        }

        $manopera->update($request->validate(
            [
                'constatare_atelier' => 'nullable|max:2000',
                'mecanic_consumabile' => 'nullable|max:2000',
                'mecanic_observatii' => 'nullable|max:2000',
            ]));

        // Daca au fost facute modificari, manoperei i se va schimba si atributul „vazut”, pentru a sti administratorul in interfata lui ca mecanicul a bagat o noua informatie
        $manopera->wasChanged() ? $manopera->update(['vazut' => 0]) : '';

        return redirect($request->session()->get('programariMecaniciReturnUrl') ?? ('/mecanici/programari-mecanici'))
            ->with('status', 'Manopera pentru mașina „' . ($manopera->programare->masina ?? '') . '” a fost modificată cu succes!');
    }

    public function bazaDeDateIndex(Request $request)
    {
        $request->session()->forget('programare_return_url');

        $search_masina = \Request::get('search_masina');
        $search_data = \Request::get('search_data');
        $search_nr_auto = \Request::get('search_nr_auto');

        $programari = Programare::with('manopere.mecanic')
            ->when($search_masina, function ($query, $search_masina) {
                return $query->where('masina', 'like', '%' . $search_masina . '%');
            })
            ->when($search_nr_auto, function ($query, $search_nr_auto) {
                return $query->where('nr_auto', 'like', '%' . $search_nr_auto . '%');
            })
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
                });
            })
                ->when(!$search_data, function ($query){
                    $query->latest();
                })
            ->simplePaginate(25);

            return view('mecanici.programari.bazaDeDate.index', compact('programari', 'search_masina', 'search_data', 'search_nr_auto'));
    }

    public function bazaDeDateShow(Request $request, Programare $programare)
    {
        $request->session()->get('programare_return_url') ?? $request->session()->put('programare_return_url', url()->previous());

        return view('mecanici.programari.bazaDeDate.show', compact('programare'));
    }
}
