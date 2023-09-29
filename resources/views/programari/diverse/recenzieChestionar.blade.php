@extends('layouts.app')

@php
    use \Carbon\Carbon;
@endphp

<script type="application/javascript">
    manopere = {!! json_encode(old('manopere' , $programare->manopere)) !!}
</script>

@section('content')
<div class="container">
    <div class="row my-0 justify-content-center">
        <div class="col-md-9 p-0">
            <div class="shadow-lg bg-white" style="border-radius: 40px 40px 40px 40px;">
                <div class="p-2 culoare1 text-white" style="border-radius: 40px 40px 0px 0px;">
                    <div class="row m-0">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <h3 class="my-2 text-center">Recomanzi AutoGNS?</h3>
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
                    <div class="row my-2 mb-4 mx-0">
                        <div class="col-lg-7 py-1 mx-auto">
                            <h4 class="mb-3 text-center" style="color:rgb(13, 94, 186); font-weight:bold">
                                {{ $programare->client }}
                                <br>
                                {{ $programare->nr_auto }}
                            </h4>
                            <span>
                                @if ($programare->lucrare)
                                    Lucrare: {{ $programare->lucrare }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="row my-2 mx-0">
                        <div class="col-lg-7 py-0 mx-auto text-center">
                            <h5 class="mb-0" style="color:rgb(13, 94, 186); font-weight:bold">
                                Ai fost mulțumit de interacțiunea cu AutoGNS?
                            </h5>
                        </div>
                    </div>

                    </div>

                    {{-- <div class="row mb-4 mx-0 px-3">
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
                    </div> --}}
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
                                {{-- <div class="col-lg-7 mx-auto rounded-3 shadow-sm border border-1" style="background-color:rgb(212, 231, 248)">
                                    <h5 class="my-2 text-center">
                                        Chestionar intern AutoGNS
                                    </h5>
                                </div> --}}

                                <div class="col-lg-7 px-2 mx-auto" id="chestionar">

                                    <form class="needs-validation d-grid px-1 mb-0" novalidate method="POST" action="{{ url()->current() }}">
                                        @csrf

                                        <input type="hidden" name="programareId" value="{{ $programare->id }}">

                                        <div v-for="(manopera, index) in manopere" :key="index"  class="row mb-3 rounded-3 shadow-sm border" style="background-color:rgb(212, 231, 248)">
                                            <div class="col-lg-12 py-2 text-center mx-auto">
                                                {{-- @{{ index+1 }}.  --}}
                                                @{{ manopera.denumire }}
                                            </div>
                                            <div class="col-lg-12 mb-0 mx-auto d-flex justify-content-center" style="">
                                                <input type="hidden" :name="'manopere[' + index + '][id]'" :value="manopera.id">
                                                <input type="hidden" :name="'manopere[' + index + '][denumire]'" :value="manopera.denumire">
                                                <input type="hidden" :name="'manopere[' + index + '][nota]'" :value="manopera.nota">

                                                <button type="button" class="mx-1 rounded-3 text-center border border-info text-info" style="width: 40px; background-color:white; font-weight:bold"
                                                    v-bind:class="[(manopera['nota'] == 1) ? 'bg-info text-white' : '']"
                                                    @click="manopera['nota'] = 1">1</button>
                                                <button type="button" class="mx-1 rounded-3 text-center border border-info text-info" style="width: 40px; background-color:white; font-weight:bold"
                                                    v-bind:class="[(manopera['nota'] == 2) ? 'bg-info text-white' : '']"
                                                    @click="manopera['nota'] = 2">2</button>
                                                <button type="button" class="mx-1 rounded-3 text-center border border-info text-info" style="width: 40px; background-color:white; font-weight:bold"
                                                    v-bind:class="[(manopera['nota'] == 3) ? 'bg-info text-white' : '']"
                                                    @click="manopera['nota'] = 3">3</button>
                                                <button type="button" class="mx-1 rounded-3 text-center border border-info text-info" style="width: 40px; background-color:white; font-weight:bold"
                                                    v-bind:class="[(manopera['nota'] == 4) ? 'bg-info text-white' : '']"
                                                    @click="manopera['nota'] = 4">4</button>
                                                <button type="button" class="mx-1 rounded-3 text-center border border-info text-info" style="width: 40px; background-color:white; font-weight:bold"
                                                    v-bind:class="[(manopera['nota'] == 5) ? 'bg-info text-white' : '']"
                                                    @click="manopera['nota'] = 5">5</button>
                                            </div>
                                            <div class="col-lg-12 mb-2 mx-auto text-center" style="">
                                                1 = nu am fost deloc mulțumit, 5 = foarte mulțumit
                                            </div>
                                            <div class="col-lg-12 mb-2 mx-auto text-center" style="">
                                                Dacă vrei, apasă
                                                <a href="#"
                                                    {{-- v-if="!manopera['comentariuAfisare'] || (manopera['comentariuAfisare'] === 'nu')"  --}}
                                                    class="" style="line-height: 80%; height:80%; font-size:100%; font-weight:bold" @click="(manopera['comentariuAfisare'] === 'da') ? (manopera['comentariuAfisare'] = 'nu') : (manopera['comentariuAfisare'] = 'da')">
                                                    aici</a>
                                                {{-- <span v-if="manopera['comentariuAfisare'] === 'da'" class="btn px-1 rounded-3 bg-danger text-white d-flex align-items-center" style="line-height: 80%; height:80%" @click="(manopera['comentariuAfisare'] === 'da') ? (manopera['comentariu'] = 'nu') : (manopera['comentariu'] = 'da')">
                                                    aici
                                                </span> --}}
                                                ca să lași și un comentariu.
                                            </div>
                                            <div v-if="manopera['comentariuAfisare'] === 'da'" class="col-lg-8 mb-2 mx-auto">
                                                <textarea class="form-control bg-white {{ $errors->has('comentariu') ? 'is-invalid' : '' }}"
                                                    :name="'manopere[' + index + '][comentariu]'" v-model="manopera['comentariu']" rows="3"></textarea>
                                            </div>
                                        </div>

                                        <div class="row my-2">
                                            <div class="col-lg-12 mb-2 mx-auto text-center" style="">
                                                <button class="btn btn-lg btn-primary">
                                                    Trimite
                                                </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif

                    {{-- <div class="row mx-0">
                        <div class="col-lg-7 py-0 mx-auto">
                            <div class="row justify-content-center">
                                <div class="col-lg-12 d-flex justify-content-center">
                                    <a class="" href="https://autogns.ro/">Închide și mergi la site-ul principal</a>
                                </div>
                            </div>
                        </div>
                    </div> --}}


                    <br>




                </div>
            </div>
        </div>
    </div>
</div>
@endsection
