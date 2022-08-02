<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Programare;

class ProgramareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $serviciu = null)
    {
        $request->session()->forget('programare_return_url');
        if ($request->route()->getName() === "programari.index"){
            $search_client = \Request::get('search_client');
            $search_telefon = \Request::get('search_telefon');
            $search_data = \Request::get('search_data');
            $search_nr_auto = \Request::get('search_nr_auto');

            $programari = Programare::
                when($search_client, function ($query, $search_client) {
                    return $query->where('client', 'like', '%' . $search_client . '%');
                })
                ->when($search_telefon, function ($query, $search_telefon) {
                    return $query->where('telefon', 'like', '%' . $search_telefon . '%');
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
                ->latest()
                ->simplePaginate(25);

            return view('programari.index', compact('programari', 'search_client', 'search_telefon', 'search_data', 'search_nr_auto'));
        } else if ($request->route()->getName() === "programari.afisareCalendar"){
            $search_data_inceput = \Request::get('search_data_inceput') ?? \Carbon\Carbon::now()->startOfWeek()->toDateString();
            $search_data_sfarsit = \Request::get('search_data_sfarsit') ?? \Carbon\Carbon::now()->endOfWeek()->toDateString();

            $programari = Programare::
                where(function($query) use ($search_data_inceput, $search_data_sfarsit){
                    $query->where(function($query) use ($search_data_inceput, $search_data_sfarsit){
                        $query->whereNull('data_ora_programare')
                            ->whereBetween('data_ora_finalizare', [$search_data_inceput, $search_data_sfarsit]);
                    });
                    $query->orwhere(function($query) use ($search_data_inceput, $search_data_sfarsit){
                        $query->whereNull('data_ora_finalizare')
                            ->whereBetween('data_ora_programare', [$search_data_inceput, $search_data_sfarsit]);
                    });
                    $query->orwhere(function($query) use ($search_data_inceput, $search_data_sfarsit){
                        $query->whereDate('data_ora_programare', '<=', $search_data_sfarsit)
                            ->whereDate('data_ora_finalizare', '>=', $search_data_inceput);
                    });
                })
                ->orderBy('lucrare_canal', 'desc')
                ->orderBy('lucrare_geometrie', 'desc')
                ->orderBy('lucrare_freon', 'desc')
                ->get();
// dd($programari);
            foreach ($programari as $programare){
                if (is_null($programare->data_ora_programare)){
                    $programare->data_ora_programare = $programare->data_ora_finalizare;
                } else if (is_null($programare->data_ora_finalizare)){
                    $programare->data_ora_finalizare = $programare->data_ora_programare;
                }
            }
// dd($programari);
            return view('programari.index', compact('programari', 'search_data_inceput', 'search_data_sfarsit'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $serviciu = null)
    {
        $request->session()->get('programare_return_url') ?? $request->session()->put('programare_return_url', url()->previous());

        return view('programari.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->request->add(['user_id' => $request->user()->id]);
        $programare = Programare::create($this->validateRequest($request));

        return redirect($request->session()->get('programare_return_url') ?? ('/programari'))
            ->with('status', 'Programarea pentru clientul „' . ($programare->client ?? '') . '” a fost adăugată cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Programare  $programare
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Programare $programare)
    {
        $request->session()->get('programare_return_url') ?? $request->session()->put('programare_return_url', url()->previous());

        return view('programari.show', compact('programare'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Programare  $programare
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Programare $programare)
    {

        $request->session()->get('programare_return_url') ?? $request->session()->put('programare_return_url', url()->previous());

        return view('programari.edit', compact('programare'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Programare  $programare
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Programare $programare)
    {
        $request->request->add(['user_id' => $request->user()->id]);
        $programare->update($this->validateRequest($request));

        return redirect($request->session()->get('programare_return_url') ?? ('/programari'))
            ->with('status', 'Programarea pentru clientul „' . ($programare->client ?? '') . '” a fost modificată cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Programare  $programare
     * @return \Illuminate\Http\Response
     */
    public function destroy(Programare $programare)
    {
        $programare->delete();

        return back()->with('status', 'Programarea pentru clientul „' . ($programare->client ?? '') . '” a fost ștearsă cu succes!');
    }

    /**
     * Validate the request attributes.
     *
     * @return array
     */
    protected function validateRequest(Request $request, $serviciu = null)
    {
        // if (is_null($request->data_ora_finalizare)){
        //     $request->data_ora_finalizare = $request->data_ora_programare;
        // }
        return $request->validate(
            [
                'client' => 'required|max:500',
                'telefon' => 'nullable|max:500',
                'email' => 'nullable|max:500',
                'data_ora_programare' => '',
                'data_ora_finalizare' => '',
                'masina' => 'nullable|max:500',
                'nr_auto' => 'nullable|max:500',
                'lucrare' => 'nullable|max:2000',
                'lucrare_canal' => '',
                'lucrare_geometrie' => '',
                'lucrare_freon' => '',
                'piese_client' => '',
                'observatii' => 'nullable|max:2000',
                'user_id' => ''
            ],
            [

            ]
        );
    }
}
