@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @include ('errors')

                    Bine ai venit <b>{{ auth()->user()->name ?? '' }}</b>!

                    <br>
                    <br>
                    <br>

                    @if((auth()->user()->role ?? '') === "mecanic")
                        <a class="mb-3 btn btn-lg w-100 text-white culoare1" href="/mecanici/programari" role="button">
                            <i class="fa-solid fa-calendar-check me-1"></i>
                            Programări
                        </a>
                        <a class="mb-3 btn btn-lg w-100 text-white culoare1" href="/mecanici/bonusuri" role="button">
                            <i class="fa-solid fa-money-check-dollar me-1"></i>
                            Bonusuri
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

