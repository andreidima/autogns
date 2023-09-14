@extends ('layouts.app')

@section('content')
<div class="row m-0">
    <div class="col-md-4 container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-12">
                <span class="badge mb-2 fs-5" style="background-color:#e66800">
                    <i class="fa-solid fa-clock me-1"></i>Pontaj
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
@endsection
