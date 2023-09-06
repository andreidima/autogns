<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use App\Models\Programare;
use App\Models\Manopera;
use App\Models\User;

use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class MecanicBonusController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('programare_return_url');
        $search_data = \Request::get('search_data') ?? Carbon::today();
        switch ($request->input('schimba_ziua')) {
            case 'o_zi_inapoi':
                $search_data = \Carbon\Carbon::parse($search_data)->subDay();
                break;
            case 'o_zi_inainte':
                $search_data = \Carbon\Carbon::parse($search_data)->addDay();
                break;
        }
        $dataInceputLuna = \Carbon\Carbon::parse($search_data)->startOfMonth();
        $dataSfarsitLuna = \Carbon\Carbon::parse($search_data)->endOfMonth();

        $programari = Programare::with('user', 'manopere')
            ->when($search_data, function ($query, $search_data) {
                $query->where(function($query) use ($search_data){
                    $query->where(function($query) use ($search_data){
                        $query
                            // ->whereNull('data_ora_programare')
                            ->whereMonth('data_ora_finalizare', '=', $search_data->month)
                            ->whereYear('data_ora_finalizare', '=', $search_data->year);
                    });
                    $query->orwhere(function($query) use ($search_data){
                        $query->whereNull('data_ora_finalizare')
                            ->whereMonth('data_ora_programare', '=', $search_data->month)
                            ->whereYear('data_ora_programare', '=', $search_data->year);
                    });
                    // $query->orwhere(function($query) use ($search_data){
                    //     $query->whereDate('data_ora_programare', '<=', $search_data)
                    //         ->whereDate('data_ora_finalizare', '>=', $search_data);
                    // });
                })
                ->orderBy('data_ora_finalizare');
            })
            ->whereHas('manopere', function (Builder $query){
                $query->where('mecanic_id', auth()->user()->id);
            })
            ->get();

            return view('mecanici.bonusuri.index', compact('programari', 'search_data'));
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
$programari = DB::select('
        SELECT t1.*
FROM programari t1
  LEFT OUTER JOIN programari t2
    ON (t1.nr_auto = t2.nr_auto AND t1.created_at < t2.created_at)
WHERE t2.nr_auto IS NULL
');
        // $programari = DB::select('select * from programari ORDER BY created_at ASC');
// $nr = 0;
// foreach ($programari as $programare){
//     echo $nr++ . '. ' . $programare->nr_auto . '&nbsp&nbsp&nbsp&nbsp&nbsp' . $programare->created_at . '<br>';
// }
// dd($programari);

        $mecanici = User::where('role', 'mecanic')->get();

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

        $programare = Programare::create($request->except('manopere', 'date'));

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
        $programari = DB::select('
                SELECT t1.*
        FROM programari t1
        LEFT OUTER JOIN programari t2
            ON (t1.nr_auto = t2.nr_auto AND t1.created_at < t2.created_at)
        WHERE t2.nr_auto IS NULL
        ');

        $mecanici = User::where('role', 'mecanic')->get();

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
        $this->validateRequest($request);

        $programare->update($request->except('manopere', 'date'));

        // Salvare in istoric
        $programare_istoric = new ProgramareIstoric;
        $programare_istoric->fill($programare->makeHidden(['created_at', 'updated_at'])->attributesToArray());
        $programare_istoric->operatie = 'Modificare';
        $programare_istoric->operatie_user_id = auth()->user()->id ?? null;
        $programare_istoric->save();


        // Actualizarea manoperelor
        if ($request->manopere) {
            // Se sterg manoperele care nu mai sunt la update
            $programare->manopere()->whereNotIn('id', collect($request->manopere)->where('id')->pluck('id'))->delete();

            foreach ($request->manopere as $manopera){
                $manopera['id'] ? ($manoperaDB = Manopera::find($manopera['id'])) : ($manoperaDB = new Manopera());
                $manoperaDB->programare_id = $programare->id;
                $manoperaDB->mecanic_id = $manopera['mecanic_id'] ?? null;
                $manoperaDB->denumire = $manopera['denumire'];
                $manoperaDB->pret = $manopera['pret'];
                $manoperaDB->bonus_mecanic = $manopera['bonus_mecanic'];
                $manoperaDB->observatii = $manopera['observatii'];
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
    protected function validateRequest(Request $request)
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
                'sms_revizie_ulei_filtre' => '',


                'manopere.*.denumire' => 'required|max:500',
                'manopere.*.mecanic_id' => 'nullable',
                'manopere.*.pret' => 'nullable|numeric|between:0,99999',
                'manopere.*.bonus_mecanic' => 'nullable|numeric|between:0,99999',
                'manopere.*.observatii' => 'nullable|max:2000',
            ],
            [
                'manopere.*.denumire.required' => 'Câmpul „Denumire” pentru manopera #:position este obligatoriu',
                'manopere.*.denumire.max' => 'Câmpul „Denumire” pentru manopera #:position trebuie sa fie de maxim 500 de caractere',
                'manopere.*.pret.numeric' => 'Câmpul „Pret” pentru manopera #:position trebuie sa fie un număr',
                'manopere.*.pret.between' => 'Câmpul „Pret” pentru manopera #:position trebuie sa fie un număr între 0 și 99999',
                'manopere.*.bonus_mecanic.numeric' => 'Câmpul „Bonus mecanic” pentru manopera #:position trebuie sa fie un număr',
                'manopere.*.bonus_mecanic.between' => 'Câmpul „Bonus mecanic” pentru manopera #:position trebuie sa fie un număr între 0 și 99999',
                'manopere.*.observatii.max' => 'Câmpul „Observații” pentru manopera #:position trebuie sa fie de maxim 2000 de caractere',
            ]
        );
    }
}
