@extends('layouts.app')

@php
    use \Carbon\Carbon;
@endphp

@section('content')
<div class="container">
    <div class="row my-0 justify-content-center">
        <div class="col-md-9 p-0">
            <div class="shadow-lg bg-white" style="border-radius: 40px 40px 40px 40px;">
                <div class="p-2 culoare1 text-white" style="border-radius: 40px 40px 0px 0px;"
                >
                    <div class="row m-0">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <h3 class="my-2 text-center"><i class="fa-solid fa-calendar-check me-1 fs-3"></i>Programarea ta la AutoGNS</h3>
                        </div>
                    </div>
                </div>

                <div class="card-body py-2 border border-0 border-dark" style="border-radius: 0px 0px 40px 40px">

                @include ('errors')

                @if(!$programare)
                    <div class="row my-5 mx-0">
                        <div class="col-lg-7 py-2 mx-auto">
                            <h5 class="ps-3 py-2 mb-0 rounded-3 text-center bg-danger text-white">
                                Această programare nu există în sistemul nostru!
                            </h5>
                        </div>
                    </div>
                @else
                    <div class="row my-2 mx-0">
                        <div class="col-lg-7 py-2 mx-auto">
                            <h4 class="mb-3 text-center">
                                {{ $programare->client }}
                                <br>
                                {{ $programare->nr_auto }}
                            </h4>
                            <span>
                                Lucrare: {{ $programare->lucrare }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-4 mx-0 px-3">
                        <div class="col-lg-7 my-2 py-3 mx-auto rounded-3 text-black align-items-center shadow-sm border" style="background-color:rgb(212, 231, 248)">
                            <h5 class="ps-3 mb-2 text-center">
                                Te invităm să ne oferi o recenzie Google
                            </h5>
                            <div class="text-center">
                                <a class="btn btn-lg btn-primary" href="http://search.google.com/local/writereview?placeid=ChIJoX8PeK8YtEARgtFebuluoUo" target="_blank" role="button">
                                    Recenzia ta
                                </a>
                            </div>
                        </div>
                    </div>
                    @if (Carbon::parse($programare->data_ora_finalizare)->gt(Carbon::now()->addMonth()))
                        <div class="row mx-0 px-3">
                            <div class="col-lg-7 py-2 mx-auto">
                                <h5 class="ps-3 py-2 mb-0 text-center bg-danger text-white">
                                    Chestionarul de recenzie a expirat
                                    {{ Carbon::parse($programare->data_ora_finalizare)->isoFormat('dddd, D MMMM') }}.
                                </h5>
                            </div>
                        </div>
                    @else
                        @if ($programare->manopere)
                            <div class="row mx-0 px-3">
                                <div class="col-lg-7 mx-auto bg-light shadow-sm border border-1 border-dark">
                                    <h5 class="my-2 text-center">
                                        Chestionar intern AutoGNS
                                    </h5>
                                </div>

                                <div class="col-lg-7 px-2 mx-auto">

                                    <form class="needs-validation d-grid px-1" novalidate method="GET" action="/status-programare/{{$programare->cheie_unica}}">
                                        @foreach ($programare->manopere as $manopera)
                                            <div class="row mb-0 bg-light shadow-sm border border-dark">
                                                <div class="col-lg-12 py-2 text-center mx-auto">
                                                    Manopera „{{ $manopera->denumire }}”
                                                </div>
                                                <div class="col-lg-12 mb-2 mx-auto">
                                                    <label for="recenzie" class="mb-0 ps-3">Recenzie</label>
                                                    <textarea class="form-control bg-white {{ $errors->has('recenzie') ? 'is-invalid' : '' }}"
                                                        name="recenzie" rows="3">{{ old('recenzie', $manopera->recenzie) }}</textarea>
                                                </div>
                                            </div>
                                        @endforeach
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif

                    <div class="row mx-0">
                        <div class="col-lg-7 py-2 mx-auto">
                            <div class="row justify-content-center">
                                <div class="col-lg-12 d-flex justify-content-center">
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
