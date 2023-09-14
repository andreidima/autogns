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
        $pontaj = Pontaj::with('programare')
                        ->where('mecanic_id', auth()->user()->id)
                        ->whereNull('sfarsit')
                        ->latest()
                        ->first();

        return view ('pontaje.interfataMecanici.citireQr', compact('programare', 'pontaj'));
    }

    public function postIncepeTerminaPontaj(Request $request, Programare $programare)
    {
        // dd('stop2');
        // Se cauta in baza de date daca este vreu pontaj ramas neterminat
        $pontaj = Pontaj::where('mecanic_id', auth()->user()->id)
                        ->whereNull('sfarsit')
                        ->latest()
                        ->first();

        // Daca s-a gasit un pontaj, acesta se inchide
        if ($pontaj) {
            $pontaj->sfarsit = Carbon::now();
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
        // dd('stop');
        $pontaj = Pontaj::where('mecanic_id', auth()->user()->id)
                        ->with('programare')
                        ->whereNull('sfarsit')
                        ->latest()
                        ->first();

        return view ('pontaje.interfataMecanici.status', compact('pontaj'));
    }
}
