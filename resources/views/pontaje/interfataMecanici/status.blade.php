@extends ('layouts.app')

@section('content')
<div class="row m-0 mb-5">
    <div class="col-md-4 container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-12">
                <span class="badge mb-2 fs-5" style="background-color:#e66800">
                    <i class="fa-solid fa-clock me-1"></i>Pontaj actual
                </span>
            </div>
        </div>

        <div class="row card-body px-0 py-3">

            @include ('errors')

            @if ($pontaj)
                <div class="col-lg-12">
                    <div class="px-2 rounded-2 text-center">
                        <span class="rounded-3" style="font-weight:bold; color:#e66800; padding:0px 5px;">
                            Acum lucrezi la:
                        </span>
                    </div>
                </div>
                <div class="col-lg-12 d-flex">
                    <div style="min-width: 70px">
                        Mașina:
                    </div>
                    <div>
                        {{ $pontaj->programare->masina ?? '' }} {{ $pontaj->programare->nr_auto ?? '' }}
                    </div>
                </div>
                <div class="col-lg-12 d-flex">
                    <div style="min-width: 70px">
                        Lucrare:
                    </div>
                    <div>
                        {{ $pontaj->programare->lucrare ?? '' }}
                    </div>
                </div>
                <div class="col-lg-12 d-flex">
                    <div style="min-width: 70px">
                        Început:
                    </div>
                    <div>
                        @if ($pontaj->inceput)
                            {{ \Carbon\Carbon::parse($pontaj->inceput)->isoFormat('DD.MM.YYYY') }}
                            <span style="font-weight:bold; color:white; background-color:#e66800; padding:0px 5px;">{{ \Carbon\Carbon::parse($pontaj->inceput)->isoFormat('HH:mm') }}</span>
                        @endif
                    </div>
                </div>

                @if ($pontaj->programare->id)
                    <form class="needs-validation" novalidate method="POST" action="/mecanici/pontaje-mecanici/incepe-termina-pontaj/{{ $pontaj->programare->id }}">
                        @csrf
                        <div class="col-6 mx-auto py-4 text-center d-grid gap-2">
                            <button class="btn btn-primary text-white border border-dark rounded-3 shadow" type="submit">
                                Termină
                            </button>
                        </div>
                    </form>
                @endif
            @else
                <div class="col-lg-12">
                    <div class="px-2 pb-4 rounded-2 text-center">
                        <span class="rounded-3" style="font-weight:bold; padding:0px 5px;">
                            În acest moment nu lucrezi la nici o mașină
                            <br>
                            <br>
                            Scanează codul QR de pe Fișa unei mașini pentru a începe lucrul
                        </span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>


<div class="row m-0">
    <div class="container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <form class="needs-validation" novalidate method="GET" action="{{ url()->current() }}">
                @csrf
                <div class="col-lg-12 d-flex justify-content-between">
                    <input type="hidden" name="search_data" value="{{ $search_data }}">

                    <span class="badge mb-2 fs-5" style="background-color: #e66800">
                        <i class="fa-solid fa-calendar-check me-1"></i>Pontaje din data de:
                    </span>

                    <div class="mb-2 d-flex justify-content-center align-items-center">
                        <button class="btn btn-sm btn-primary text-white border border-dark rounded-3 shadow block"
                            name="schimbaZiua" value="oZiInapoi" type="submit">
                            <
                        </button>
                        <span class="badge fs-5 p-0 mx-1 text-black" style="background-color: #ffffff">
                            <label class="mx-0">{{ \Carbon\Carbon::parse($search_data)->isoFormat('DD.MM.YYYY') }}</label>
                        </span>

                        <button class="btn btn-sm btn-primary text-white border border-dark rounded-3 shadow block"
                            name="schimbaZiua" value="oZiInainte" type="submit">
                            >
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="row card-body px-0 py-3">

            @include ('errors')


            <div class="px-0 table-responsive rounded">
                <table class="table table-sm table-striped table-hover table-bordered rounded">
                    <thead class="text-white rounded culoare2">
                        <tr class="" style="padding:2rem">
                            <th class="text-center px-0">Început</th>
                            <th class="text-center px-0">Sfârșit</th>
                            <th class="text-center px-0">Mașina</th>
                            <th class="text-center px-0">Lucrare</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pontaje as $pontaj)
                            <tr>
                                <td class="text-center">
                                    {{ $pontaj->inceput ? \Carbon\Carbon::parse($pontaj->inceput)->isoFormat('HH:mm') : '' }}
                                </td>
                                <td class="text-center">
                                    {{ $pontaj->sfarsit ? \Carbon\Carbon::parse($pontaj->sfarsit)->isoFormat('HH:mm') : '' }}
                                </td>
                                <td>
                                    {{ $pontaj->programare->masina ?? '' }}
                                </td>
                                <td>
                                    {{ $pontaj->programare->lucrare ?? '' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
@endsection
