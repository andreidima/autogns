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

                @if ($programare)
                    <div class="row my-2 mx-0">
                        <div class="col-lg-7 py-2 mx-auto">
                            <h5 class="mb-0 text-center" style="color:rgb(13, 94, 186); font-weight:bold">
                                {{ $programare->client }}
                            </h5>
                        </div>
                        <div class="col-lg-7 py-2 mx-auto">
                            <h5 class="mb-0 py-1 text-center rounded-3" style="background-color:rgb(255, 255, 255); color:rgb(39, 185, 6); font-weight:bold">
                                Chestionarul a fost salvat cu succes
                                <br>
                                <br>
                                Îți mulțumim că ne-ai împărtășit opinia ta
                            </h5>
                        </div>
                    </div>
                @endif

                <div class="row mb-0 mx-0 px-3">
                    <div class="col-lg-7 my-2 py-3 mx-auto rounded-3 text-black align-items-center shadow-sm border" style="background-color:rgb(142, 234, 251)">
                        <h5 class="ps-3 mb-2 text-center">
                            Te invităm să ne oferi și o recenzie Google
                        </h5>
                        <div class="text-center">
                            <a class="btn btn-lg btn-primary" href="https://search.google.com/local/writereview?placeid=ChIJ14H6PKsYtEART1H0fVp_J3o" role="button">
                                Recenzia ta
                            </a>
                        </div>
                    </div>
                </div>

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
