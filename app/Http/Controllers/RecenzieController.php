<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Recenzie;
use App\Models\User;

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

        $recenzii = Recenzie::with('manopera')
            // ->when($userId, function (Builder $query) use ($userId) {
            //     $query->whereHas('user', function (Builder $query) use ($userId) {
            //         return $query->where('id', $userId);
            //     });
            // })
            // ->when($data, function (Builder $query) use ($data) {
            //     return $query->whereDate('inceput', '<=', $data)
            //                 ->whereDate('sfarsit', '>=', $data);
            // })
            ->latest()
            ->simplePaginate(25);

        $useri = User::where('role', 'mecanic')
            ->whereNotIn('id', [18, 20]) // Andrei Dima Mecanic, Viorel Mecanic
            ->orderBy('name')->get();

        return view('recenzii.index', compact('recenzii', 'useri', 'userId'));
    }

}
