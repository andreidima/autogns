<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Notificare;
use App\Models\User;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

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
}
