<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Necesar;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class NecesarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('necesarReturnUrl');

        $userId = $request->userId;

        $necesare = Necesar::with('user')
            ->when($userId, function (Builder $query) use ($userId) {
                $query->whereHas('user', function (Builder $query) use ($userId) {
                    return $query->where('id', $userId);
                });
            })
            ->when((auth()->user()->role == "mecanic"), function ($query) {
                return $query->where('user_id', auth()->user()->id);
            })
            ->latest()
            ->simplePaginate(25);

        $useri = User::where('name', 'not like', "%Andrei Dima%")->orderBy('name')->get();

        return view('necesare.index', compact('necesare', 'useri', 'userId'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->get('necesarReturnUrl') ?? $request->session()->put('necesarReturnUrl', url()->previous());

        return view('necesare.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $necesar = Necesar::create($this->validateRequest($request));

        return redirect($request->session()->get('necesarReturnUrl') ?? ('/necesare'))
            ->with('status', 'Necesarul a fost adăugat cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Necesar  $necesar
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Necesar $necesar)
    {
        // $request->session()->get('necesarReturnUrl') ?? $request->session()->put('necesarReturnUrl', url()->previous());

        // return view('necesare.show', compact('necesar'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App Necesar  $necesar
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Necesar $necesar)
    {
        $request->session()->get('necesarReturnUrl') ?? $request->session()->put('necesarReturnUrl', url()->previous());

        if ((auth()->user()->role == "mecanic") && (auth()->user()->id != $necesar->user_id)){
        } else {
            return view('necesare.edit', compact('necesar'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App Necesar  $necesar
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Necesar $necesar)
    {
        if ((auth()->user()->role == "mecanic") && (auth()->user()->id != $necesar->user_id)){
        } else {
            $necesar->update($this->validateRequest($request));
        }

        return redirect($request->session()->get('necesarReturnUrl') ?? ('/necesare'))
            ->with('status', 'Necesarul a fost modificat cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App Necesar  $necesar
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Necesar $necesar)
    {
        if ((auth()->user()->role == "mecanic") && (auth()->user()->id != $necesar->user_id)){
        } else {
            $necesar->delete();
        }

        return back()->with('status', 'Necesarul a fost șters cu succes!');
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
        }

        return $request->validate(
            [
                'user_id' => '',
                'necesar' => 'required',
            ],
        );
    }
}
