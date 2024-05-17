<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Notificare;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class NotificareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('notificareReturnUrl');

        $data = $request->data;

        $notificari = Notificare::
            when($data, function (Builder $query) use ($data) {
                return $query->whereDate('data', $data);
            })
            ->latest()
            ->simplePaginate(25);

        return view('notificari.index', compact('notificari', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->session()->get('notificareReturnUrl') ?? $request->session()->put('notificareReturnUrl', url()->previous());

        return view('notificari.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $notificare = Notificare::create($this->validateRequest($request));

        return redirect($request->session()->get('notificareReturnUrl') ?? ('/notificari'))->with('status', 'Notificarea ' . $notificare->nume . ' a fost adăugată cu succes!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Notificare  $notificare
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Notificare $notificare)
    {
        // $request->session()->get('notificareReturnUrl') ?? $request->session()->put('notificareReturnUrl', url()->previous());

        // return view('notificari.show', compact('pontaj'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App Notificare  $notificare
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Notificare $notificare)
    {
        $request->session()->get('notificareReturnUrl') ?? $request->session()->put('notificareReturnUrl', url()->previous());

        return view('notificari.edit', compact('notificare'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App Notificare  $notificare
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notificare $notificare)
    {
        $notificare->update($this->validateRequest($request));

        return redirect($request->session()->get('notificareReturnUrl') ?? ('/notificari'))->with('status', 'Notificarea ' . $notificare->nume . ' a fost modificată cu succes!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App Notificare  $notificare
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Notificare $notificare)
    {
        $notificare->delete();

        return back()->with('status', 'Notificarea ' . $notificare->nume . ' a fost ștearsă cu succes!');
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
                'nume' => 'required|max:500',
                'data' => 'required',
            ],
            [

            ]
        );
    }

    public function modificariInMasa(Request $request)
    {
        return view('notificari.diverse.notificariInMasa');
    }

    public function postModificariInMasa(Request $request)
    {
        $request->validate(
            [
                'text_vechi' => 'required|max:500',
                'text_nou' => 'nullable|max:500',
            ]
        );

        $text_vechi = $request->text_vechi;
        $text_nou = $request->text_nou;

        $notificariViitoareToate = Notificare::whereDate('data', '>', Carbon::today())->get();
        $notificariViitoareSelectate = Notificare::whereDate('data', '>', Carbon::today())
            ->when($text_vechi, function ($query, $text_vechi) {
                return $query->where('nume', 'like', '%' . $text_vechi . '%');
            })
            ->get();

        if ($request->action == 'Cauta') {
            return back()->with('status', 'Au fost găsite ' . $notificariViitoareSelectate->count() . ', dintr-un total de ' . $notificariViitoareToate->count() . ' de notificări.');
        } else if ($request->action == 'Modifica') {
                DB::update(
                    'update notificari set nume = replace(nume, ?, ?) where nume like ? and data > ?',
                    [
                        $text_vechi,
                        $text_nou,
                        '%' . $text_vechi . '%',
                        Carbon::today()->isoFormat('YYYY-MM-DD')
                    ]
                );
            return back()->with('status', 'Au fost modificate ' . $notificariViitoareSelectate->count() . ', dintr-un total de ' . $notificariViitoareToate->count() . ' de notificări.');
        } else {
            //invalid action!
        }

    }
}
