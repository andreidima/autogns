<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pontaj;
use App\Models\Programare;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class PontajController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('pontajReturnUrl');

        $mecanicId = $request->mecanicId;
        $nrAuto = $request->nrAuto;
        $data = $request->data;

        $pontaje = Pontaj::with('mecanic', 'programare')
            ->when($mecanicId, function (Builder $query) use ($mecanicId) {
                $query->whereHas('mecanic', function (Builder $query) use ($mecanicId) {
                    return $query->where('id', $mecanicId);
                });
            })
            ->when($nrAuto, function (Builder $query) use ($nrAuto) {
                $query->whereHas('programare', function (Builder $query) use ($nrAuto) {
                    return $query->where('nr_auto', 'like', '%' . $nrAuto . '%');
                });
            })
            ->when($data, function (Builder $query) use ($data) {
                return $query->whereDate('inceput', $data);
            })
            ->latest()
            ->simplePaginate(25);

        $mecanici = User::where('role', 'mecanic')
            ->where('id', '<>', 18) // Andrei Dima Mecanic
            ->orderBy('name')->get();

        return view('pontaje.index', compact('pontaje', 'mecanici', 'mecanicId', 'nrAuto', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->get('pontajReturnUrl') ?? $request->session()->put('pontajReturnUrl', url()->previous());

        $programare = Programare::where('id', $request->programareId)->first();
        $mecanic = User::where('id', $request->mecanicId)->first();
        $data = Carbon::parse($request->data)->toDateString();

        return view('pontaje.create', compact('programare', 'mecanic', 'data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'programareId' => 'required',
            'mecanicId' => 'required',
            'data' => 'required',
            'inceput' => 'required',
            'sfarsit' => ''
        ]);

        $pontaj = new Pontaj;
        $pontaj->programare_id = $request->programareId;
        $pontaj->mecanic_id = $request->mecanicId;
        if ($request->inceput) {
            $pontaj->inceput = Carbon::parse($request->data)->setTimeFromTimeString($request->inceput)->toDateTimeString();
        }
        if ($request->sfarsit) {
            $pontaj->sfarsit = Carbon::parse($request->data)->setTimeFromTimeString($request->sfarsit)->toDateTimeString();
        }

        $pontaj->save();

        return redirect($request->session()->get('pontajReturnUrl') ?? ('/programari'))
            ->with('status', 'Pontajul pentru mecanicul „' . $pontaj->mecanic->name . '”, la mașina „' . $pontaj->programare->masina . '”, a fost adăugat cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pontaj  $pontaj
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Pontaj $pontaj)
    {
        // $request->session()->get('pontajReturnUrl') ?? $request->session()->put('pontajReturnUrl', url()->previous());

        // return view('pontaje.show', compact('pontaj'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App Pontaj  $pontaj
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Pontaj $pontaj)
    {
        $request->session()->get('pontajReturnUrl') ?? $request->session()->put('pontajReturnUrl', url()->previous());

        $programare = Programare::where('id', $pontaj->programare_id)->first();
        $mecanic = User::where('id', $pontaj->mecanic_id)->first();
        $data = Carbon::parse($pontaj->inceput)->toDateString();

        // dd($pontaj,$mecanic);

        return view('pontaje.edit', compact('pontaj', 'programare', 'mecanic', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App Pontaj  $pontaj
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pontaj $pontaj)
    {
        $request->validate([
            'data' => 'required',
            'inceput' => 'required',
            'sfarsit' => ''
        ]);

        if ($request->inceput) {
            $pontaj->inceput = Carbon::parse($request->data)->setTimeFromTimeString($request->inceput)->toDateTimeString();
        }
        if ($request->sfarsit) {
            $pontaj->sfarsit = Carbon::parse($request->data)->setTimeFromTimeString($request->sfarsit)->toDateTimeString();
        }

        $pontaj->save();

        return redirect($request->session()->get('pontajReturnUrl') ?? ('/pontaje'))
            ->with('status', 'Pontajul pentru mecanicul „' . $pontaj->mecanic->name . '”, la mașina „' . $pontaj->programare->masina . '”, a fost modificat cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App Pontaj  $pontaj
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Pontaj $pontaj)
    {
        $pontaj->delete();

        return back()->with('status', 'Pontajul pentru mecanicul „' . $pontaj->mecanic->name . '”, la mașina „' . $pontaj->programare->masina . '”, a fost șters cu succes!');
    }

    /**
     * Mecanicii citect codul QR
     */
    public function citireQr(Request $request, Programare $programare)
    {
        if (!in_array(auth()->user()->id, $programare->manopere->pluck('mecanic_id')->toArray())){
            return redirect('/mecanici/pontaje-mecanici/status')->with('eroare', 'Nu ai nici o manoperă alocată pentru mașina ' . $programare->masina . ' ' . $programare->nr_auto);
        }

        $pontaj = Pontaj::with('programare')
                        ->where('mecanic_id', auth()->user()->id)
                        ->whereNull('sfarsit')
                        ->latest()
                        ->first();

        return view ('pontaje.interfataMecanici.citireQr', compact('programare', 'pontaj'));
    }

    /**
     * Mecanicii incep sau termina un pontaj
     */
    public function postIncepeTerminaPontaj(Request $request, Programare $programare)
    {
        // Se cauta in baza de date daca este vreu pontaj ramas neterminat
        $pontaj = Pontaj::where('mecanic_id', auth()->user()->id)
                        ->whereNull('sfarsit')
                        ->latest()
                        ->first();

        // Daca s-a gasit un pontaj, acesta se inchide
        if ($pontaj) {
            // In cazul in care pontajul a ramas neinchis din alta zi
            if (Carbon::parse($pontaj->inceput)->toDateString() !== Carbon::now()->toDateString()){
                if (in_array(Carbon::parse($pontaj->inceput)->dayOfWeek, [1,2,3,4,5])){ // luni-vineri
                    $pontaj->sfarsit = Carbon::parse($pontaj->inceput)->hour(17)->minute(00);
                } else if (Carbon::parse($pontaj->inceput)->dayOfWeek === 6) { // sambata
                    $pontaj->sfarsit = Carbon::parse($pontaj->inceput)->hour(14)->minute(00);
                } else if (Carbon::parse($pontaj->inceput)->dayOfWeek === 0) { // duminica
                    $pontaj->sfarsit = Carbon::parse($pontaj->inceput)->hour(23)->minute(59);
                }
            } else {
                $pontaj->sfarsit = Carbon::now();
            }
            $pontaj->save();
        }

        // Daca nu s-a gasit nici un pontaj, sau daca pontajul gasit nu era cel pentru programarea scanata, atunci se deschide unul nou
        if (!$pontaj || ($pontaj->programare_id != $programare->id)) {
            $pontaj = new Pontaj;
            $pontaj->programare_id = $programare->id;
            $pontaj->mecanic_id = auth()->user()->id;
            $pontaj->inceput = Carbon::now();
            $pontaj->save();
        }

        return redirect('/mecanici/pontaje-mecanici/status');
    }

    /**
     * Mecanicii vad care e statusul pontajului curent, in cazul in care au vreunul deschis
     */
    public function status(Request $request)
    {
        $search_data = Carbon::parse($request->search_data) ?? Carbon::today();

        switch ($request->input('schimbaZiua')) {
            case 'oZiInapoi':
                $search_data = Carbon::parse($search_data)->subDay();
                break;
            case 'oZiInainte':
                $search_data = Carbon::parse($search_data)->addDay();
                break;
        }

        $pontaje = Pontaj::with('programare')
                        ->where('mecanic_id', auth()->user()->id)
                        ->whereDate('inceput', $search_data)
                        ->oldest()
                        ->get();

        $pontaj = Pontaj::where('mecanic_id', auth()->user()->id)
                        ->with('programare')
                        ->whereNull('sfarsit')
                        ->latest()
                        ->first();

        return view ('pontaje.interfataMecanici.status', compact('pontaje', 'pontaj', 'search_data'));
    }
}
