@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row my-4 justify-content-center">
        <div class="col-md-9 p-0">
            <div class="shadow-lg bg-white" style="border-radius: 40px 40px 40px 40px;">
                <div class="p-2 culoare1 text-white" style="border-radius: 40px 40px 0px 0px;"
                >
                    <div class="row">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <h3 class="my-2 text-center"><i class="fa-solid fa-calendar-check me-1 fs-3"></i>Programarea dumneavoastră la AutoGNS</h3>
                        </div>
                    </div>
                </div>

                <div class="card-body py-2 border border-0 border-dark" style="border-radius: 0px 0px 40px 40px">

                @include ('errors')

                    @if (\Carbon\Carbon::parse($programare->data_ora_programare)->lessThan(\Carbon\Carbon::now()))
                        <div class="row">
                            <div class="col-lg-7 py-2 mx-auto">
                                <h5 class="ps-3 py-2 mb-0 text-center bg bg-danger text-white">
                                    Perioada de confirmare a acestei programări a trecut deja.
                                </h5>
                            </div>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-lg-7 py-2 mx-auto">
                                @if (is_null($programare->confirmare))
                                    <h5 class="ps-3 py-2 mb-0 text-center bg bg-warning text-dark">
                                        Vă rugăm să confirmați programarea.
                                    </h5>
                                @elseif ($programare->confirmare == 0)
                                    <h5 class="ps-3 py-2 mb-0 text-center bg bg-danger text-white">
                                        Ați anulat programarea!
                                        <br>
                                        <br>
                                        <small>Vă mulțumim pentru răspuns. Vă așteptăm la service cu altă ocazie.</small>
                                    </h5>
                                @elseif ($programare->confirmare == 1)
                                    <h5 class="ps-3 py-2 mb-0 text-center bg bg-success text-white">
                                        Ați confirmat programarea!
                                        <br>
                                        <br>
                                        <small>Vă mulțumim pentru răspuns. Vă așteptăm la service.</small>
                                    </h5>
                                @endif
                            </div>
                            <div class="col-lg-7 mx-auto">
                                <div class="row align-items-center">
                                    <div class="col-1">
                                    </div>
                                    <div class="col-3">
                                        <label for="client" class="col-form-label">Nume:</label>
                                    </div>
                                    <div class="col-8">
                                        <label for="client" class="col-form-label">
                                            <b>
                                                {{ $programare->client }}
                                            </b>
                                        </label>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-1">
                                    </div>
                                    <div class="col-3">
                                        <label for="nr_auto" class="col-form-label">Nr. auto:</label>
                                    </div>
                                    <div class="col-8">
                                        <label for="nr_auto" class="col-form-label">
                                            <b>
                                                {{ $programare->nr_auto }}
                                            </b>
                                        </label>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-1">
                                    </div>
                                    <div class="col-3">
                                        <label for="data" class="col-form-label">Data:</label>
                                    </div>
                                    <div class="col-8">
                                        <label for="data" class="col-form-label">
                                            <b>
                                                {{ \Carbon\Carbon::parse($programare->data_ora_programare)->dayName }}, {{ \Carbon\Carbon::parse($programare->data_ora_programare)->isoFormat('DD MMMM YYYY') }}
                                            </b>
                                        </label>
                                    </div>
                                </div>
                                <div class="row align-items-center">
                                    <div class="col-1">
                                    </div>
                                    <div class="col-3">
                                        <label for="ora" class="col-form-label">Ora:</label>
                                    </div>
                                    <div class="col-8">
                                        <label for="ora" class="col-form-label">
                                            <b>
                                                {{ \Carbon\Carbon::parse($programare->data_ora_programare)->isoFormat('HH:mm') }}
                                            </b>
                                        </label>
                                    </div>
                                </div>

                                @if (!$confirmat_deja)
                                    <div class="row mb-5 justify-content-center">
                                        <div class="col-6 d-grid">
                                            <form class="needs-validation d-grid px-1" novalidate method="GET" action="/status-programare/{{$programare->cheie_unica}}">
                                                {{-- <div class="row custom-search-form justify-content-center"> --}}
                                                    <input type="hidden" name="confirmare" value="da">
                                                    <button class="btn btn-success text-white border border-dark rounded-3 shadow block" type="submit">
                                                        Confirm programarea
                                                    </button>
                                                {{-- </div> --}}
                                            </form>
                                        </div>
                                        <div class="col-6 d-grid">
                                            <form class="needs-validation d-grid px-1" novalidate method="GET" action="/status-programare/{{$programare->cheie_unica}}">
                                                {{-- <div class="row custom-search-form justify-content-center"> --}}
                                                    <input type="hidden" name="confirmare" value="nu">
                                                    <button class="btn btn-danger text-white border border-dark rounded-3 shadow block" type="submit">
                                                        Anulez programarea
                                                    </button>
                                                {{-- </div> --}}
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-lg-7 py-2 mx-auto">
                            <div class="row justify-content-center">
                                <div class="col-lg-12 d-flex justify-content-center">
                                    {{-- <a class="btn btn-primary text-white border border-dark rounded-3 shadow block" href="https://autogns.ro/">Mergi la site-ul principal</a> --}}
                                    <a class="" href="https://autogns.ro/">Închide și mergi la site-ul principal</a>
                                </div>
                            </div>
                        </div>
                    </div>


                    <br>




                </div>
            </div>
        </div>
    </div>
</div>
@endsection
