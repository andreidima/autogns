<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ClientNeserios;
use App\Models\Programare;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ClientNeseriosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('clientNeseriosReturnUrl');

        $client = $request->client;
        // $nr_auto = $request->nr_auto;
        $telefon = $request->telefon;

        $clientiNeseriosi = ClientNeserios::
            when($client, function (Builder $query) use ($client) {
                return $query->where('client', $client);
            })
            // ->when($nr_auto, function (Builder $query) use ($nr_auto) {
            //     return $query->where('nr_auto', $nr_auto);
            // })
            ->when($telefon, function (Builder $query) use ($telefon) {
                return $query->where('telefon', $telefon);
            })
            ->latest()
            ->simplePaginate(25);

        return view('clientiNeseriosi.index', compact('clientiNeseriosi', 'client', 'telefon'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Programare $programare = null)
    {
        $request->session()->get('clientNeseriosReturnUrl') ?? $request->session()->put('clientNeseriosReturnUrl', url()->previous());

        $clientNeserios = new ClientNeserios;

        // If the clientNeserios is added from programari, we autocomplete some values
        $clientNeserios->client = $programare->client ?? '';
        // $clientNeserios->nr_auto = $programare->nr_auto ?? '';
        $clientNeserios->telefon = $programare->telefon ?? '';

        return view('clientiNeseriosi.create', compact('clientNeserios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $clientNeserios = ClientNeserios::create($this->validateRequest($request));

        return redirect($request->session()->get('clientNeseriosReturnUrl') ?? ('/clienti-neseriosi'))->with('status', 'Clientul neserios ' . $clientNeserios->client . ' a fost adăugat cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ClientNeserios  $clientNeserios
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, ClientNeserios $clientNeserios)
    {
        // $request->session()->get('clientNeseriosReturnUrl') ?? $request->session()->put('clientNeseriosReturnUrl', url()->previous());

        // return view('clientiNeseriosi.show', compact('pontaj'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App ClientNeserios  $clientNeserios
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, ClientNeserios $clientNeserios)
    {
        $request->session()->get('clientNeseriosReturnUrl') ?? $request->session()->put('clientNeseriosReturnUrl', url()->previous());

        return view('clientiNeseriosi.edit', compact('clientNeserios'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App ClientNeserios  $clientNeserios
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientNeserios $clientNeserios)
    {
        $clientNeserios->update($this->validateRequest($request));

        return redirect($request->session()->get('clientNeseriosReturnUrl') ?? ('/clienti-neseriosi'))->with('status', 'Clientul neserios ' . $clientNeserios->client . ' a fost modificat cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App ClientNeserios  $clientNeserios
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ClientNeserios $clientNeserios)
    {
        $clientNeserios->delete();

        return back()->with('status', 'Clientul neserios ' . $clientNeserios->client . ' a fost șters cu succes!');
    }

    /**
     * Validate the request attributes.
     *
     * @return array
     */
    protected function validateRequest(Request $request)
    {
        return $request->validate(
            [
                'client' => 'nullable|max:500',
                // 'nr_auto' => 'nullable|max:500',
                'telefon' => 'nullable|max:500',
                'descriere' => 'nullable|max:2000',
                'observatii' => 'nullable|max:2000',
            ],
            [

            ]
        );
    }
}
