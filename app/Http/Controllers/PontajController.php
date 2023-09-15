<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pontaj;
use App\Models\Programare;
use App\Models\Mecanic;

use Carbon\Carbon;

class PontajController extends Controller
{
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
