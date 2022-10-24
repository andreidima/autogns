<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Programare;

use App\Traits\TrimiteSmsTrait;

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
            $programari = Programare::with('user', 'smsuri', 'programare_istoric:id_pk,id,confirmare,confirmare_client_timestamp')
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
        $programari = Programare::select('client', 'telefon', 'email', 'masina', 'nr_auto', 'created_at')->whereNotNull('nr_auto')->groupBy('nr_auto')->latest()->get();
$nr = 0;
foreach ($programari as $programare){
    echo $nr++ . '. ' . $programare->nr_auto . '&nbsp&nbsp&nbsp&nbsp&nbsp' . $programare->created_at . '<br>';
}
dd($programari);
        $request->session()->get('programare_return_url') ?? $request->session()->put('programare_return_url', url()->previous());

        return view('programari.create', compact('programari'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $programare = Programare::create($this->validateRequest($request));

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
        $programare->update($this->validateRequest($request));

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
        \App\Models\ProgramareIstoric::where('id', $programare->id)->where('operatie', 'Stergere')->update(['user_id' => $request->user()->id]);

        return back()->with('status', 'Programarea pentru mașina „' . ($programare->masina ?? '') . '” a fost ștearsă cu succes!');
    }

    /**
     * Validate the request attributes.
     *
     * @return array
     */
    protected function validateRequest(Request $request)
    {
        $request->request->add(['user_id' => $request->user()->id]);

        if ($request->isMethod('post')) {
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
                'lucrare' => 'nullable|max:2000',
                'geometrie_turism' => '',
                'geometrie_camion' => '',
                'freon' => '',
                'piese' => '',
                'stare_masina' => '',
                'observatii' => 'nullable|max:2000',
                'user_id' => '',
                'confirmare' => '',
                'cheie_unica' => '',
                'sms_revizie_ulei_filtre' => ''
            ],
            [

            ]
        );
    }
}
