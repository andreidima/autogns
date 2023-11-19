<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Recenzie;
use App\Models\User;
use App\Models\Programare;

use Illuminate\Contracts\Database\Eloquent\Builder;

class RecenzieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->session()->forget('recenzieReturnUrl');

        $userId = $request->userId;

        $recenzii = Recenzie::with('manopera.mecanic', 'manopera.programare')
            ->when($userId, function (Builder $query) use ($userId) {
                $query->whereHas('manopera', function (Builder $query) use ($userId) {
                    $query->whereHas('mecanic', function (Builder $query) use ($userId) {
                        return $query->where('mecanic_id', $userId);
                    });
                });
            })
            // ->when($data, function (Builder $query) use ($data) {
            //     return $query->whereDate('inceput', '<=', $data)
            //                 ->whereDate('sfarsit', '>=', $data);
            // })
            ->latest()
            ->simplePaginate(100);

        $useri = User::where('role', 'mecanic')
            ->whereNotIn('id', [18, 20]) // Andrei Dima Mecanic, Viorel Mecanic
            ->orderBy('name')->get();

        return view('recenzii.index', compact('recenzii', 'useri', 'userId'));
    }

    public function programariExcluse(Request $request)
    {
        $request->session()->forget('recenzieReturnUrl');

        $programari = Programare::select('id', 'client', 'masina', 'data_ora_programare', 'sms_recenzie', 'sms_recenzie_motiv_nu')->where('sms_recenzie', 0)->latest()->simplePaginate(100);

        return view('recenzii.programariExcluse', compact('programari'));
    }

}
