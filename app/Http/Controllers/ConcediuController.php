<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Concediu;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ConcediuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('concediuReturnUrl');

        $userId = $request->userId;
        $data = $request->data;

        $concedii = Concediu::with('user')
            ->when($userId, function (Builder $query) use ($userId) {
                $query->whereHas('user', function (Builder $query) use ($userId) {
                    return $query->where('id', $userId);
                });
            })
            ->when($data, function (Builder $query) use ($data) {
                return $query->whereDate('inceput', '>=', $data)
                            ->whereDate('sfarsit', '<=', $data);
            })
            ->latest()
            ->simplePaginate(25);

        $useri = User::where('role', 'mecanic')
            ->where('id', '<>', 18) // Andrei Dima Mecanic
            ->orderBy('name')->get();

        return view('concedii.index', compact('concedii', 'useri', 'userId', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->get('concediuReturnUrl') ?? $request->session()->put('concediuReturnUrl', url()->previous());

        $useri = User::where('role', 'mecanic')
            ->where('id', '<>', 18) // Andrei Dima Mecanic
            ->orderBy('name')->get();

        return view('concedii.create', compact('useri'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Mecanicii nu pot adăuga pontaje mai vechi de 2 zile
        if (auth()->user()->role === "mecanic"){
            if (auth()->user()->id != $request->mecanicId){
                return back()->with('eroare', 'Nu se pot adăuga pontaje altor utilizatori.');
            } else if(Carbon::parse($request->data)->addDays(2)->lessThan(Carbon::today())){
                return back()->with('eroare', 'Nu se pot adăuga pontaje mai vechi de 2 zile.');
            }
            if (!in_array(auth()->user()->id, Programare::where('id', $request->programareId)->first()->manopere->pluck('mecanic_id')->toArray())){
                return back()->with('eroare', 'Nu ai nici o manoperă adăugată la această mașină');
            }
        }



        $request->validate([
            'programareId' => 'required',
            'mecanicId' => 'required',
            'data' => 'required',
            'inceput' => 'required',
            'sfarsit' => ''
        ]);

        $pontaj = new Concediu;
        $pontaj->programare_id = $request->programareId;
        $pontaj->mecanic_id = $request->mecanicId;
        if ($request->inceput) {
            $pontaj->inceput = Carbon::parse($request->data)->setTimeFromTimeString($request->inceput)->toDateTimeString();
        }
        if ($request->sfarsit) {
            $pontaj->sfarsit = Carbon::parse($request->data)->setTimeFromTimeString($request->sfarsit)->toDateTimeString();
        }

        $pontaj->save();

        return redirect($request->session()->get('concediuReturnUrl') ?? ('/programari'))
            ->with('status', 'Concediuul pentru mecanicul „' . $pontaj->mecanic->name . '”, la mașina „' . $pontaj->programare->masina . '”, a fost adăugat cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Concediu  $pontaj
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Concediu $pontaj)
    {
        // $request->session()->get('concediuReturnUrl') ?? $request->session()->put('concediuReturnUrl', url()->previous());

        // return view('concedii.show', compact('pontaj'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App Concediu  $pontaj
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Concediu $pontaj)
    {
        $request->session()->get('concediuReturnUrl') ?? $request->session()->put('concediuReturnUrl', url()->previous());

        $programare = Programare::where('id', $pontaj->programare_id)->first();
        $mecanic = User::where('id', $pontaj->mecanic_id)->first();
        $data = Carbon::parse($pontaj->inceput)->toDateString();

        // dd($pontaj,$mecanic);

        return view('concedii.edit', compact('pontaj', 'programare', 'mecanic', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App Concediu  $pontaj
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Concediu $pontaj)
    {
        // Mecanicii nu pot modifica pontaje mai vechi de 2 zile
        if (auth()->user()->role === "mecanic"){
            if (auth()->user()->id != $pontaj->mecanic_id){
                return back()->with('eroare', 'Nu se pot modifica pontajele altor utilizatori.');
            } else if(Carbon::parse($request->data)->addDays(2)->lessThan(Carbon::today()) || Carbon::parse($pontaj->inceput)->addDays(2)->lessThan(Carbon::today())){
                return back()->with('eroare', 'Nu se pot modifica pontaje mai vechi de 2 zile.');
            }
        }
        if ((auth()->user()->role === "mecanic") && Carbon::parse($request->data)->addDays(2)->lessThan(Carbon::today())){
            return back()->with('eroare', 'Nu se pot modifica pontaje mai vechi de 2 zile.');
        }

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
        } else {
            $pontaj->sfarsit = null;
        }

        $pontaj->save();

        return redirect($request->session()->get('concediuReturnUrl') ?? ('/pontaje'))
            ->with('status', 'Concediuul pentru mecanicul „' . $pontaj->mecanic->name . '”, la mașina „' . $pontaj->programare->masina . '”, a fost modificat cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App Concediu  $pontaj
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Concediu $pontaj)
    {
        // Mecanicii nu pot sterge pontaje mai vechi de 2 zile
        if (auth()->user()->role === "mecanic"){
            if (auth()->user()->id != $pontaj->mecanic_id){
                return back()->with('eroare', 'Nu se pot șterge pontajele altor utilizatori.');
            } else if(Carbon::parse($pontaj->inceput)->addDays(2)->lessThan(Carbon::today())){
                return back()->with('eroare', 'Nu se pot șterge pontaje mai vechi de 2 zile.');
            }
        }


        $pontaj->delete();

        return back()->with('status', 'Concediuul pentru mecanicul „' . $pontaj->mecanic->name . '”, la mașina „' . $pontaj->programare->masina . '”, a fost șters cu succes!');
    }
}
