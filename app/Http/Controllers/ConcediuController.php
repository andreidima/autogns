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
                return $query->whereDate('inceput', '<=', $data)
                            ->whereDate('sfarsit', '>=', $data);
            })
            ->latest()
            ->simplePaginate(25);

        $useri = User::where('role', 'mecanic')
            ->where('id', '<>', 18) // Andrei Dima Mecanic
            ->where('id', '<>', 20) // Viorel Mecanic
            ->where('activ', 1) //
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
            ->where('id', '<>', 20) // Viorel Mecanic
            ->where('activ', 1) //
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
        $this->validateRequest($request);

        $concediu = new Concediu;
        $concediu->user_id = $request->mecanic;
        $concediu->inceput = strtok($request->interval, ',');
        $concediu->sfarsit = strtok('');
        $concediu->observatii = $request->observatii;

        $concediu->save();

        return redirect($request->session()->get('concediuReturnUrl') ?? ('/concedii'))
            ->with('status', 'Concediul pentru mecanicul ' . ($concediu->user->name ?? '') . ' a fost adăugat cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Concediu  $concediu
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Concediu $concediu)
    {
        // $request->session()->get('concediuReturnUrl') ?? $request->session()->put('concediuReturnUrl', url()->previous());

        // return view('concedii.show', compact('pontaj'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App Concediu  $concediu
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Concediu $concediu)
    {
        $request->session()->get('concediuReturnUrl') ?? $request->session()->put('concediuReturnUrl', url()->previous());

        $useri = User::where('role', 'mecanic')
            ->where('id', '<>', 18) // Andrei Dima Mecanic
            ->where('id', '<>', 20) // Viorel Mecanic
            ->where('activ', 1) //
            ->orderBy('name')->get();

        return view('concedii.edit', compact('concediu', 'useri'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App Concediu  $concediu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Concediu $concediu)
    {
        $this->validateRequest($request);

        $concediu->user_id = $request->mecanic;
        $concediu->inceput = strtok($request->interval, ',');
        $concediu->sfarsit = strtok('');
        $concediu->observatii = $request->observatii;

        $concediu->save();

        return redirect($request->session()->get('concediuReturnUrl') ?? ('/concedii'))
            ->with('status', 'Concediul pentru mecanicul ' . ($concediu->user->name ?? '') . ' a fost modificat cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App Concediu  $concediu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Concediu $concediu)
    {
        $concediu->delete();

        return back()->with('status', 'Concediul pentru mecanicul ' . ($concediu->user->name ?? '') . ' a fost șters cu succes!');
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
                'mecanic' => 'required',
                'interval' => 'required|min:20', // ramane doar o virgula daca data se sterge manual din controller, si atunci nu mai functioneaza doar regula „required”
                'observatii' => '',
            ],
            [

            ]
        );
    }
}
