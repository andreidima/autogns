<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Models\Programare;
use App\Models\ProgramareIstoric;
use App\Models\Manopera;
use App\Models\Pontaj;
use App\Models\User;

use Carbon\Carbon;

use App\Traits\TrimiteSmsTrait;

use Illuminate\Contracts\Database\Eloquent\Builder;

class ProgramareController extends Controller
{
    use TrimiteSmsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('programare_return_url');
        $request->session()->forget('pontajReturnUrl');

        if ($request->route()->getName() === "programari.index"){
            $search_client = \Request::get('search_client');
            $search_telefon = \Request::get('search_telefon');
            $search_data = \Request::get('search_data');
            $search_nr_auto = \Request::get('search_nr_auto');

            switch ($request->input('schimba_ziua')) {
                case 'o_zi_inapoi':
                    $search_data = \Carbon\Carbon::parse($search_data)->subDay()->toDateString();
                    break;
                case 'o_zi_inainte':
                    $search_data = \Carbon\Carbon::parse($search_data)->addDay()->toDateString();
                    break;
            }

            // $programari = Programare::with('user', 'smsuri', 'programare_istoric')
            $programari = Programare::with('user', 'smsuri', 'programare_istoric:id_pk,id,confirmare,confirmare_client_timestamp', 'manopere.mecanic')
                ->with(['pontaje' => function (Builder $query) use ($search_data) {
                    $query->whereDate('inceput', $search_data);
                }])
                ->when($search_client, function ($query, $search_client) {
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
                    })
                    ->orderBy('data_ora_programare');
                    // ->orderBy('created_at');
                })
                 ->when(!$search_data, function ($query){
                     $query->latest();
                 })
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
                ->orderBy('geometrie_turism', 'desc')
                ->orderBy('geometrie_camion', 'desc')
                ->orderBy('freon', 'desc')
                ->get();

            foreach ($programari as $programare){
                if (is_null($programare->data_ora_programare)){
                    $programare->data_ora_programare = $programare->data_ora_finalizare;
                } else if (is_null($programare->data_ora_finalizare)){
                    $programare->data_ora_finalizare = $programare->data_ora_programare;
                }
            }

            return view('programari.index', compact('programari', 'search_data_inceput', 'search_data_sfarsit'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // $programari = Programare::select('client', 'telefon', 'email', 'masina', 'nr_auto', 'created_at')->whereNotNull('nr_auto')->orderBy('id', 'desc')->get();
        // $programari = $programari->groupBy('nr_auto');
        // dd($programari->first());
        // $programari = groupBy('nr_auto');
        // $programari = DB::select('select * from (select * from programari ORDER BY created_at DESC) AS x where nr_auto = "VN84DIM" GROUP BY id');
// $programari = DB::select('
//         SELECT t1.*
// FROM programari t1
//   LEFT OUTER JOIN programari t2
//     ON (t1.nr_auto = t2.nr_auto AND t1.created_at < t2.created_at)
// WHERE t2.nr_auto IS NULL
// ');
        // $programari = DB::select('select * from programari ORDER BY created_at ASC');
// $nr = 0;
// foreach ($programari as $programare){
//     echo $nr++ . '. ' . $programare->nr_auto . '&nbsp&nbsp&nbsp&nbsp&nbsp' . $programare->created_at . '<br>';
// }
// dd($programari);

        // $programari = Programare::select('client', 'telefon', 'email', 'masina', 'nr_auto', 'vin')
        //     ->where(function (Builder $query) {
        //         $query->whereNotNull('nr_auto')
        //               ->orWhereNotNull('client');
        //     })
        //     ->distinct('nr_auto')
        //     ->get();

        $programari = Programare::select('client', 'telefon', 'email', 'masina', 'nr_auto', 'vin')
            ->where(function (Builder $query) {
                $query->whereNotNull('nr_auto')
                      ->orWhereNotNull('client');
            })
            ->where('client', 'corox')
            ->groupBy(DB::raw('ifnull(nr_auto,client)'))
            ->orderBy('created_at', 'desc')
            // ->distinct('nr_auto', 'telefon')
            ->get();

// dd($programari->count(), $programari2->count(), $programari2->take(5));
// dd($programari);

        $mecanici = User::where('role', 'mecanic')
            ->where('id', '<>', 18) // Andrei Dima Mecanic
            ->orderBy('name')->get();

        $request->session()->get('programare_return_url') ?? $request->session()->put('programare_return_url', url()->previous());

        return view('programari.create', compact('programari', 'mecanici'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateRequest($request);

        $programare = Programare::make($request->except('manopere', 'date'));
        // Daca este bifata ca este finalizata, se trece si data ora finalizare ca fiind acum
        if ($programare->stare_masina == "3"){
            $programare->data_ora_finalizare = Carbon::now()->toDateTimeString();
        }
        $programare->save();

        // Salvare in istoric
        $programare_istoric = new ProgramareIstoric;
        $programare_istoric->fill($programare->makeHidden(['created_at', 'updated_at'])->attributesToArray());
        $programare_istoric->operatie = 'Adaugare';
        $programare_istoric->operatie_user_id = auth()->user()->id ?? null;
        $programare_istoric->save();

        // Salvarea manoperelor
        if ($request->manopere) {
            foreach($request->manopere as $manopera){
                $manoperaDB = new Manopera;
                $manoperaDB->programare_id = $programare->id;
                $manoperaDB->mecanic_id = $manopera['mecanic_id'];
                $manoperaDB->denumire = $manopera['denumire'];
                $manoperaDB->pret = $manopera['pret'];
                $manoperaDB->bonus_mecanic = $manopera['bonus_mecanic'];
                $manoperaDB->observatii = $manopera['observatii'];
                $manoperaDB->constatare_atelier = $manopera['constatare_atelier'];
                $manoperaDB->vazut = 1;
                $manoperaDB->save();
            }
        }

        // Trimitere Sms la inregistrare
        $mesaj = 'Programarea pentru masina \'' . $programare->nr_auto . '\' a fost inregistrata. ' .
                    'Va asteptam la service in data de ' . \Carbon\Carbon::parse($programare->data_ora_programare)->isoFormat('DD.MM.YYYY') .
                    ', la ora ' . \Carbon\Carbon::parse($programare->data_ora_programare)->isoFormat('HH:mm') . '. ' .
                    'Cu stima, AutoGNS +40723114595!';
        // Referitor la diacritice, puteti face conversia unui string cu diacritice intr-unul fara diacritice, in mod automatizat cu aceasta functie PHP:
        $mesaj = \Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', \Transliterator::FORWARD)->transliterate($mesaj);
        $this->trimiteSms('programari', 'inregistrare', $programare->id, [$programare->telefon], $mesaj);

        // Trimitere sms la finalozare lucrare
        if (($request->stare_masina == 3) && (!$programare->sms_finalizare->count())){
            $mesaj = 'Masina dumneavoastra este gata si o puteti ridica de la service. Cu stima, AutoGNS +40723114595!';
            // Referitor la diacritice, puteti face conversia unui string cu diacritice intr-unul fara diacritice, in mod automatizat cu aceasta functie PHP:
            $mesaj = \Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', \Transliterator::FORWARD)->transliterate($mesaj);
            $this->trimiteSms('programari', 'finalizare', $programare->id, [$programare->telefon], $mesaj);
        }

        return redirect($request->session()->get('programare_return_url') ?? ('/programari'))
            ->with('status', 'Programarea pentru mașina „' . ($programare->masina ?? '') . '” a fost adăugată cu succes!');
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

        // Daca este utilizatorul AutoGNS, se bifeaza in baza de date ca vazute informatiile adaugate la manopere de catre mecanici
        if (auth()->user()->id == 2) {
            $programare->manopere()->update(['vazut' => 1]);
        }

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
        // $programari = DB::select('
        //         SELECT t1.*
        // FROM programari t1
        // LEFT OUTER JOIN programari t2
        //     ON (t1.nr_auto = t2.nr_auto AND t1.created_at < t2.created_at)
        // WHERE t2.nr_auto IS NULL
        // ');

        // $programari = Programare::select('client', 'telefon', 'email', 'masina', 'nr_auto', 'vin')
        //     ->where(function (Builder $query) {
        //         $query->whereNotNull('nr_auto')
        //               ->orWhereNotNull('telefon');
        //     })
        //     ->distinct('nr_auto', 'telefon')
        //     ->get();

        $programari = Programare::select('client', 'telefon', 'email', 'masina', 'nr_auto', 'vin')
            ->where(function (Builder $query) {
                $query->whereNotNull('nr_auto')
                      ->orWhereNotNull('client');
            })
            ->where('client', 'corox')
            ->groupBy(DB::raw('ifnull(nr_auto,client)'))
            ->orderBy('created_at', 'desc')
            // ->distinct('nr_auto', 'telefon')
            ->get();

        $mecanici = User::where('role', 'mecanic')
            ->where('id', '<>', 18) // Andrei Dima Mecanic
            ->orderBy('name')->get();

        $request->session()->get('programare_return_url') ?? $request->session()->put('programare_return_url', url()->previous());

        return view('programari.edit', compact('programare', 'programari', 'mecanici'));
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
        $this->validateRequest($request, $programare);

        $programare->update($request->except('manopere', 'date'));
        // Daca este bifata acum ca este finalizata, se trece si data ora finalizare ca fiind acum
        if (($programare->stare_masina == "3") && ($programare->wasChanged ('stare_masina'))){
            $programare->data_ora_finalizare = Carbon::now()->toDateTimeString();
        }
        $programare->save();


        // Salvare in istoric
        $programare_istoric = new ProgramareIstoric;
        $programare_istoric->fill($programare->makeHidden(['created_at', 'updated_at'])->attributesToArray());
        $programare_istoric->operatie = 'Modificare';
        $programare_istoric->operatie_user_id = auth()->user()->id ?? null;
        $programare_istoric->save();

        // Actualizarea manoperelor
        // Se sterg manoperele care nu mai sunt la update
        $programare->manopere()->whereNotIn('id', collect($request->manopere)->where('id')->pluck('id'))->delete();
        // Se adauga/ modifica manoperele din request
        if ($request->manopere) {
            foreach ($request->manopere as $manopera){
                if ($manopera['id']) {
                    $manoperaDB = Manopera::find($manopera['id']);
                } else {
                    $manoperaDB = new Manopera();
                    $manoperaDB->vazut = 1;
                }
                // $manopera['id'] ? ($manoperaDB = Manopera::find($manopera['id'])) : ($manoperaDB = new Manopera());
                $manoperaDB->programare_id = $programare->id;
                $manoperaDB->mecanic_id = $manopera['mecanic_id'] ?? null;
                $manoperaDB->denumire = $manopera['denumire'];
                $manoperaDB->pret = $manopera['pret'];
                $manoperaDB->bonus_mecanic = $manopera['bonus_mecanic'];
                $manoperaDB->observatii = $manopera['observatii'];
                $manoperaDB->constatare_atelier = $manopera['constatare_atelier'];
                $manoperaDB->save();
            }
        }

        // Trimitere sms daca a fost schimbata data_ora_programare
        if ($programare->wasChanged('data_ora_programare')){
            $mesaj = 'Masina \'' . $programare->nr_auto . '\' a fost reprogramata. ' .
                        'Va asteptam la service in data de ' . \Carbon\Carbon::parse($programare->data_ora_programare)->isoFormat('DD.MM.YYYY') .
                        ', la ora ' . \Carbon\Carbon::parse($programare->data_ora_programare)->isoFormat('HH:mm') . '. ' .
                        'Cu stima, AutoGNS +40723114595!';
            // Referitor la diacritice, puteti face conversia unui string cu diacritice intr-unul fara diacritice, in mod automatizat cu aceasta functie PHP:
            $mesaj = \Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', \Transliterator::FORWARD)->transliterate($mesaj);
            $this->trimiteSms('programari', 'inregistrare', $programare->id, [$programare->telefon], $mesaj);
        }

        // Trimitere sms daca s-a finalozat lucrarea si nu a fost deja trimis un sms anterior
        if (($request->stare_masina == 3) && (!$programare->sms_finalizare->count())){
            $mesaj = 'Masina dumneavoastra cu numarul ' . $programare->nr_auto . ' este gata si o puteti ridica de la service. Cu stima, AutoGNS +40723114595!';
            // Referitor la diacritice, puteti face conversia unui string cu diacritice intr-unul fara diacritice, in mod automatizat cu aceasta functie PHP:
            $mesaj = \Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', \Transliterator::FORWARD)->transliterate($mesaj);
            $this->trimiteSms('programari', 'finalizare', $programare->id, [$programare->telefon], $mesaj);
        }

        return redirect($request->session()->get('programare_return_url') ?? ('/programari'))
            ->with('status', 'Programarea pentru mașina „' . ($programare->masina ?? '') . '” a fost modificată cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Programare  $programare
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Programare $programare)
    {
        $programare->delete();
        $programare->manopere()->delete();

        // Salvare in istoric
        $programare_istoric = new ProgramareIstoric;
        $programare_istoric->fill($programare->makeHidden(['created_at', 'updated_at'])->attributesToArray());
        $programare_istoric->operatie = 'Stergere';
        $programare_istoric->operatie_user_id = auth()->user()->id ?? null;
        $programare_istoric->save();

        return back()->with('status', 'Programarea pentru mașina „' . ($programare->masina ?? '') . '” a fost ștearsă cu succes!');
    }

    /**
     * Validate the request attributes.
     *
     * @return array
     */
    protected function validateRequest(Request $request, $programare = null)
    {
        // Se adauga doar la adaugare, iar la modificare nu se schimba
        if ($request->isMethod('post')) {
            $request->request->add(['user_id' => $request->user()->id]);
            $request->request->add(['cheie_unica' => uniqid()]);
        }

        return $request->validate(
            [
                'client' => 'required|max:500',
                'telefon' => 'nullable|max:500',
                'email' => 'nullable|max:500',
                'data_ora_programare' => 'required',
                'data_ora_finalizare' => '',
                'masina' => 'nullable|max:500',
                'nr_auto' => 'nullable|max:500',
                'vin' => 'nullable|max:200',
                'lucrare' => 'nullable|max:2000',
                'geometrie_turism' => '',
                'geometrie_camion' => '',
                'freon' => '',
                'piese' => '',
                'stare_masina' => ($request->_method === "PATCH") ?
                        function ($attribute, $value, $fail) use ($request, $programare) {
                            if ($value == "3"){ // Daca se bifeaza ca programarea este finalizata
                                foreach ($programare->manopere->whereNotIn('mecanic_id', [10,17]) as $manopera) { // Daca au lucrat si alti mecanici in afara de Cosmin si Iulian
                                    if (Pontaj::where('programare_id', $programare->id)->where('mecanic_id', $manopera->mecanic_id)->count() === 0) {
                                        $fail('Mecanicul ' . $manopera->mecanic->name . ' nu are pontajul adăugat.');
                                    } else if (Pontaj::where('programare_id', $programare->id)->where('mecanic_id', $manopera->mecanic_id)->whereNull('sfarsit')->count() > 0) {
                                        $fail('Mecanicul ' . $manopera->mecanic->name . ' nu are pontajul sfârșit.');
                                    }
                                }
                            }
                        } : '',
                'observatii' => 'nullable|max:2000',
                'user_id' => '',
                'confirmare' => '',
                'cheie_unica' => '',
                'sms_revizie_ulei_filtre' => '',
                'sms_recenzie' => 'nullable|required_if:stare_masina,3',
                'sms_recenzie_motiv_nu' => 'nullable|required_if:sms_recenzie,0',


                'manopere.*.denumire' => 'required|max:500',
                'manopere.*.mecanic_id' => 'nullable',
                'manopere.*.pret' => 'nullable|required_if:stare_masina,3|numeric|between:0,99999',
                'manopere.*.bonus_mecanic' => 'nullable|numeric|between:0,99999',
                'manopere.*.observatii' => 'nullable|max:2000',
                'manopere.*.constatare_atelier' => 'nullable|max:2000',
            ],
            [
                'sms_recenzie.required_if' => 'Câmpul „Sms recenzie” este necesar când stare masina este „Finalizată”',
                'sms_recenzie_motiv_nu.required_if' => 'Câmpul „Motiv pentru NU” este necesar când „Sms recenzie” este NU',

                'manopere.*.denumire.required' => 'Câmpul „Denumire” pentru manopera #:position este obligatoriu',
                'manopere.*.denumire.max' => 'Câmpul „Denumire” pentru manopera #:position trebuie sa fie de maxim 500 de caractere',
                'manopere.*.pret.required_if' => 'Câmpul „Pret” pentru manopera #:position este necesar când stare masina este „Finalizată”',
                'manopere.*.pret.numeric' => 'Câmpul „Pret” pentru manopera #:position trebuie sa fie un număr',
                'manopere.*.pret.between' => 'Câmpul „Pret” pentru manopera #:position trebuie sa fie un număr între 0 și 99999',
                'manopere.*.bonus_mecanic.numeric' => 'Câmpul „Bonus mecanic” pentru manopera #:position trebuie sa fie un număr',
                'manopere.*.bonus_mecanic.between' => 'Câmpul „Bonus mecanic” pentru manopera #:position trebuie sa fie un număr între 0 și 99999',
                'manopere.*.observatii.max' => 'Câmpul „Observații” pentru manopera #:position trebuie sa fie de maxim 2000 de caractere',
                'manopere.*.constatare_atelier.max' => 'Câmpul „Constatare atelier” pentru manopera #:position trebuie sa fie de maxim 2000 de caractere',
            ]
        );
    }

    public function exportFisaPdf(Request $request, Programare $programare)
    {
        // return view('programari.export.exportFisaPdf', compact('programare'));
        $pdf = \PDF::loadView('programari.export.exportFisaPdf', compact('programare'))
            ->setPaper('a4', 'portrait');
        $pdf->getDomPDF()->set_option("enable_php", true);
        // return $pdf->download('AutoGNS Manopere ' . \Carbon\Carbon::parse($search_data)->isoFormat('DD.MM.YYYY') . '.pdf');
        return $pdf->stream();
    }
}
