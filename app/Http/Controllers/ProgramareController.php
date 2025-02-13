<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Models\Programare;
use App\Models\ProgramareIstoric;
use App\Models\Manopera;
use App\Models\Pontaj;
use App\Models\User;
use App\Models\Concediu;
use App\Models\Recenzie;
use App\Models\Notificare;

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
            // $programari = Programare::with('user', 'smsuri', 'programare_istoric:id_pk,id,confirmare,confirmare_client_timestamp', 'manopere.mecanic', 'clientNeseriosDupaClient', 'clientNeseriosDupaNrAuto')
            $programari = Programare::with('user', 'smsuri', 'programare_istoric:id_pk,id,confirmare,confirmare_client_timestamp', 'manopere.mecanic', 'clientNeseriosDupaClient', 'clientNeseriosDupaTelefon')
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

            // Se incarca si concediile pentru ziua respectiva ca sa le vada operatorul programarilor
            $concedii = null;
            if ($search_data){
                $concedii = Concediu::whereDate('inceput', '<=', $search_data)
                    ->whereDate('sfarsit', '>=', $search_data)
                    ->get();
            }

            // Se incarca si notificarile pentru ziua respectiva ca sa le vada operatorul programarilor
            $notificari = null;
            if ($search_data){
                $notificari = Notificare::whereDate('data', $search_data)->get();
            }

            return view('programari.index', compact('programari', 'concedii', 'notificari', 'search_client', 'search_telefon', 'search_data', 'search_nr_auto'));
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
        // Programari selectate unic dupa nr_auto
        $programari1 = Programare::select('client', 'telefon', 'email', 'masina', 'nr_auto', 'vin')
            ->whereNotNull('nr_auto')
            ->latest()
            ->get()
            ->unique('nr_auto');

        // Programari care nu au nr_auto, si care nu au clientul deja in cele de mai sus
        $programari2 = Programare::select('client', 'telefon', 'email', 'masina', 'nr_auto', 'vin')
            ->whereNull('nr_auto')
            ->whereNotIn('client', $programari1->pluck('client'))
            ->latest()
            ->get()
            ->unique('client');

        $programari = collect([$programari1, $programari2]);
        $programari = $programari->flatten(1);

        $mecanici = User::where('role', 'mecanic')
            ->where('id', '<>', 18) // Andrei Dima Mecanic
            ->where('id', '<>', 20) // Viorel Mecanic
            ->where('activ', 1) //
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
        // if ($programare->stare_masina == "3"){
        //     $programare->data_ora_finalizare = Carbon::now()->toDateTimeString();
        // }

        // Daca are bifa pentru sms_revizie_ulei_filtre, se cauta in spate daca masina a mai avut in ultimul an o astfel de bifa, si se scoate, ca sa nu se mai trimita sms pentru aceea
        if ($programare->sms_revizie_ulei_filtre == "1"){
            if ($programare->vin || $programare->nr_auto){ // sa fie vin sau nr_auto trecute, caci dupa acestea se cauta
                Programare::whereDate('data_ora_programare', '>', Carbon::now()->subYear())
                    ->where('sms_revizie_ulei_filtre', 1)
                    ->where(function ($query) use($programare){
                        $query->when($programare->vin, function ($query, $vin) {
                            return $query->where('vin', $vin);
                            })
                            ->when($programare->nr_auto, function ($query, $nr_auto) {
                                return $query->orWhere('nr_auto', $nr_auto);
                            });
                    })
                ->update(['sms_revizie_ulei_filtre' => 0]);
            }
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
                $manoperaDB->mecanic_id = $manopera['mecanic_id'] ?? null;
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

        $this->trimiteSms('programari', 'inregistrare', $programare->id, [$programare->telefon], $mesaj);

        // Trimitere sms la finalozare lucrare
        if (($request->stare_masina == 3) && (!$programare->sms_finalizare->count())){
            $mesaj = 'Masina dumneavoastra este gata si o puteti ridica de la service. Cu stima, AutoGNS +40723114595!';

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
            // ->where('client', 'corox')
            ->groupBy(DB::raw('ifnull(nr_auto,client)'))
            ->orderBy('created_at', 'desc')
            // ->distinct('nr_auto', 'telefon')
            ->get();

        $mecanici = User::where('role', 'mecanic')
            ->where('id', '<>', 18) // Andrei Dima Mecanic
            ->where('id', '<>', 20) // Viorel Mecanic
            ->where('activ', 1) //
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

        // When 'programare' is set as finalized, 'data_ora_finalizare' is automatically set to the current time
        // if (($programare->stare_masina != $request->stare_masina) && ($request->stare_masina == 3)){
        //     $request['data_ora_finalizare'] = Carbon::now()->toDateTimeString();
        // }
        $programare->update($request->except('manopere', 'date'));

        // Daca este bifata acum ca este finalizata, se trece si data ora finalizare ca fiind acum
        // if (($programare->stare_masina == "3") && ($programare->wasChanged ('stare_masina'))){
        //     $programare->data_ora_finalizare = Carbon::now()->toDateTimeString();
        //     $programare->save();
        // }

        // Daca are bifa pentru sms_revizie_ulei_filtre, se cauta in spate daca masina a mai avut in ultimul an o astfel de bifa, si se scoate, ca sa nu se mai trimita sms pentru aceea
        if ($programare->wasChanged('sms_revizie_ulei_filtre') && $programare->sms_revizie_ulei_filtre == "1"){
            if ($programare->vin || $programare->nr_auto){ // sa fie vin sau nr_auto trecute, caci dupa acestea se cauta
                Programare::whereDate('data_ora_programare', '>', Carbon::now()->subYear())
                    ->whereNot('id', $programare->id) // se sare peste programarea in cauza
                    ->where('sms_revizie_ulei_filtre', 1)
                    ->where(function ($query) use($programare){
                        $query->when($programare->vin, function ($query, $vin) {
                            return $query->where('vin', $vin);
                            })
                            ->when($programare->nr_auto, function ($query, $nr_auto) {
                                return $query->orWhere('nr_auto', $nr_auto);
                            });
                    })
                ->update(['sms_revizie_ulei_filtre' => 0]);
            }
        }



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

        // If data_ora_programare was changed, the client get a new notification sms, and his confirm status is deleted
        if ($programare->wasChanged('data_ora_programare')){
            // The client get a new notification sms
            $mesaj = 'Masina \'' . $programare->nr_auto . '\' a fost reprogramata. ' .
                        'Va asteptam la service in data de ' . \Carbon\Carbon::parse($programare->data_ora_programare)->isoFormat('DD.MM.YYYY') .
                        ', la ora ' . \Carbon\Carbon::parse($programare->data_ora_programare)->isoFormat('HH:mm') . '. ' .
                        'Cu stima, AutoGNS +40723114595!';

            $this->trimiteSms('programari', 'inregistrare', $programare->id, [$programare->telefon], $mesaj);

            // In case that the client allready confirmed/infirmed this Programare, the fields „confirmare” and „confirmare_client_timestamp” are reseted (set to null)
            $programare->confirmare = null;
            $programare->confirmare_client_timestamp = null;
            $programare->save();
        }

        // Trimitere sms daca s-a finalizat lucrarea si nu a fost deja trimis un sms anterior
        // if (($request->stare_masina == 3) && (!$programare->sms_finalizare->count())){
        // If "stare_masina" was changed, and is number 3, then will be sent the sms that the work is finished. "stare_masina" can be changed back to "in_lucru" or something similar, so when it will be changed again to 3, a new sms has to be sent
        // dd($programare, $programare->stare_masina);
        if ($programare->wasChanged('stare_masina') && ($programare->stare_masina == 3)){
            $mesaj = 'Masina dumneavoastra cu numarul ' . $programare->nr_auto . ' este gata si o puteti ridica de la service. Cu stima, AutoGNS +40723114595!';

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

        // When 'programare' is set as finalized, 'data_ora_finalizare' is automatically set to the current time
        if (!$programare) { // store method doesn't have 'programare' set yet
            if ($request->stare_masina == 3){
                $request['data_ora_finalizare'] = Carbon::now()->toDateTimeString();
            }
        } else if (($programare->stare_masina != $request->stare_masina) && ($request->stare_masina == 3)){ // update method
            $request['data_ora_finalizare'] = Carbon::now()->toDateTimeString();
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
                'piese_vechi_in_masina' => '',
                'stare_masina' => ($request->_method === "PATCH") ?
                        function ($attribute, $value, $fail) use ($request, $programare) {
                            if ($value == "3"){ // Daca se bifeaza ca programarea este finalizata
                                foreach ($programare->manopere->whereNotNull('mecanic_id')->whereNotIn('mecanic_id', [10,15,17]) as $manopera) { // Daca au lucrat si alti mecanici in afara de Cosmin, Razvan sau Iulian
                                    if (Pontaj::where('programare_id', $programare->id)->where('mecanic_id', $manopera->mecanic_id)->count() === 0) {
                                        $fail('Mecanicul ' . ($manopera->mecanic->name ?? '') . ' nu are pontajul adăugat.');
                                    } else if (Pontaj::where('programare_id', $programare->id)->where('mecanic_id', $manopera->mecanic_id)->whereNull('sfarsit')->count() > 0) {
                                        $fail('Mecanicul ' . ($manopera->mecanic->name ?? '') . ' nu are pontajul sfârșit.');
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

    public function recenzieClientChestionar($key = null)
    {
        $programare = Programare::with('manopere')->where('cheie_unica', $key)->first();

        return view('programari.diverse.recenzieChestionar', compact('programare'));
    }

    public function postRecenzieClientChestionar(Request $request, $key = null)
    {
        $request->validate([
                'manopere.*.nota' => 'required',
                'manopere.*.comentariu' => 'nullable|max:2000'
            ],
            [
                'manopere.*.nota.required' => 'Manopera #:position are nevoie de o notă',
                'manopere.*.comentariu.max' => 'Comentariul pentru manopera #:position nu poate depăși 2000 de caractere',
            ]
        );

        foreach ($request->manopere as $manopera){
            $recenzie = new Recenzie;
            $recenzie->manopera_id = $manopera['id'];
            $recenzie->nota = $manopera['nota'];
            $recenzie->comentariu = $manopera['comentariu'] ?? null;
            $recenzie->save();
        }

        return redirect('recenzie/recenzie-google/' . $key);
    }

    public function recenzieClientRecenzieGoogle($key = null)
    {
        $programare = Programare::where('cheie_unica', $key)->first();

        return view('programari.diverse.recenzieGoogle', compact('programare'));
    }
}
