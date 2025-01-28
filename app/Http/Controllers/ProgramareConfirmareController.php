<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Programare;
use App\Models\ProgramareIstoric;

use App\Traits\TrimiteSmsTrait;

class ProgramareConfirmareController extends Controller
{
    use TrimiteSmsTrait;

    // Trimite SMS la comanda
    public function cerereConfirmareSms(Request $request, Programare $programare)
    {
        $mesaj = 'Accesati ' . url('/status-programare/' . $programare->cheie_unica) . ', pentru a confirma sau anula programarea din ' . Carbon::parse($programare->data_ora_programare)->isoFormat('DD.MM.YYYY') .
                    ', ora ' . Carbon::parse($programare->data_ora_programare)->isoFormat('HH:mm') .
                    '. AutoGNS!';

        $this->trimiteSms('programari', 'confirmare', $programare->id, [$programare->telefon], $mesaj);

        return back();
    }

    public function statusProgramare(Request $request, Programare $programare)
    {
        $confirmat_deja = false;
        if (Carbon::parse($programare->data_ora_programare)->greaterThan(Carbon::now())){
            if ($request->has('confirmare')){
                if ($request->confirmare === 'da'){
                    $programare->confirmare = 1;
                    $programare->confirmare_client_timestamp = Carbon::now();
                    $programare->save();
                }
                if ($request->confirmare === 'nu'){
                    $programare->confirmare = 0;
                    $programare->confirmare_client_timestamp = Carbon::now();
                    $programare->save();
                }
                // Salvare in istoric
                $programare_istoric = new ProgramareIstoric;
                $programare_istoric->fill($programare->makeHidden(['created_at', 'updated_at'])->attributesToArray());
                $programare_istoric->operatie = 'Confirmare Infirmare';
                $programare_istoric->operatie_user_id = auth()->user()->id ?? null;
                $programare_istoric->save();
            $confirmat_deja = true;
            }
        }

        return view('programari.diverse.confirmareInfirmareProgramare', compact('programare', 'confirmat_deja'));
    }
}
