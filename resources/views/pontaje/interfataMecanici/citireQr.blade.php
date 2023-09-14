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

            <form class="needs-validation" novalidate method="POST" action="/mecanici/pontaje-mecanici/incepe-termina-pontaj/{{ $programare->id }}">
                @csrf

                    <div class="col-lg-12">
                        <div class="px-2 rounded-2 text-center">
                            <span class="rounded-3" style="font-weight:bold; color:#e66800; padding:0px 5px;">
                                Ai scanat mașina:
                            </span>
                        </div>
                    </div>

                    <div class="col-lg-12 d-flex">
                        <div style="min-width: 70px">
                            Mașina:
                        </div>
                        <div>
                            {{ $programare->masina }} {{ $programare->nr_auto }}
                        </div>
                    </div>
                    <div class="col-lg-12 d-flex">
                        <div style="min-width: 70px">
                            Lucrare:
                        </div>
                        <div>
                            {{ $programare->lucrare }}
                        </div>
                    </div>
                    @if (!$pontaj) {{-- Daca nu exista pontaj --}}
                        <div class="col-6 mx-auto py-4 text-center d-grid gap-2">
                            <button class="btn btn-lg btn-primary text-white border border-dark rounded-3 shadow" type="submit">
                                Începe
                            </button>
                        </div>
                    @elseif ($pontaj && ($pontaj->programare_id != $programare->id)) {{-- Daca exista pontaj dar nu pentru programarea aceasta --}}
                        <div class="col-lg-12 my-4 py-1 rounded-3 px-2 bg-warning">
                            <div class="text-center">
                                ATENȚIE!
                                <br>
                                Ai deja în derulare un pontaj deschis la:
                            </div>
                            <div class="d-flex">
                                <div style="min-width: 70px">
                                    Mașina:
                                </div>
                                <div>
                                    {{ $pontaj->programare->masina ?? '' }} {{ $pontaj->programare->nr_auto ?? '' }}
                                </div>
                            </div>
                            <div class="d-flex">
                                <div style="min-width: 70px">
                                    Lucrare:
                                </div>
                                <div>
                                    {{ $pontaj->programare->lucrare ?? '' }}
                                </div>
                            </div>
                            <div class="d-flex">
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

                            <div class="px-2 rounded-2 text-center" style="background-color:rgb(199, 196, 0)">
                                Acest pontaj se va incheia automat dacă începi pontajul la noua mașină.
                            </div>
                        </div>

                        <div class="col-6 mx-auto py-4 text-center d-grid gap-2">
                            <button class="btn btn-lg btn-primary text-white border border-dark rounded-3 shadow" type="submit">
                                Începe
                            </button>
                        </div>
                    @elseif ($pontaj && ($pontaj->programare_id == $programare->id)) {{-- Daca exista pontaj pentru programarea aceasta --}}
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
                        <div class="col-6 mx-auto py-4 text-center d-grid gap-2">
                            <button class="btn btn-primary text-white border border-dark rounded-3 shadow" type="submit">
                                Termină
                            </button>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
