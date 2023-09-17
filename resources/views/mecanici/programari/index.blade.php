@extends ('layouts.app')

@php
    use \Carbon\Carbon;
@endphp

@section('content')
<div class="container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <form class="needs-validation" novalidate method="GET" action="{{ url()->current() }}">
                @csrf
                <div class="col-lg-12 d-flex justify-content-between">
                    <input type="hidden" name="search_data" value="{{ $search_data }}">

                    <span class="badge mb-2 fs-5" style="background-color: #e66800">
                        <i class="fa-solid fa-calendar-check me-1"></i>Programări
                    </span>

                    <div class="mb-2 d-flex justify-content-center align-items-center">
                        <button class="btn btn-sm btn-primary text-white border border-dark rounded-3 shadow block"
                            name="schimba_ziua" value="o_zi_inapoi" type="submit">
                            <
                        </button>

                        <span class="badge fs-5 p-0 mx-1 text-black" style="background-color: #ffffff">
                            <label class="mx-0">{{ Carbon::parse($search_data)->isoFormat('DD.MM.YYYY') }}</label>
                        </span>

                        <button class="btn btn-sm btn-primary text-white border border-dark rounded-3 shadow block"
                            name="schimba_ziua" value="o_zi_inainte" type="submit">
                            >
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body px-0 py-3">

            @include ('errors')

            @foreach ($programari as $programare)
                <div class="row">
                    <div class="col-lg-12 d-flex" style="border-bottom: 4px solid black">
                        <div class="" style="min-width: 50px;">
                            <b>
                                {{ $programare->data_ora_programare ? Carbon::parse($programare->data_ora_programare)->isoFormat('HH:mm') : '' }}
                            </b>
                        </div>
                        <div class="">
                            <b>
                                <span class="" style="color:#e66800">
                                    {{ $programare->masina }} {{ $programare->nr_auto }}
                                </span>
                                <br>
                                @if ($programare->km)
                                    <span class="px-1 me-4 rounded-3 bg-success text-white" style="white-space: nowrap; overflow: hidden;">
                                        <a class="text-white text-decoration-none" href="/mecanici/programari-mecanici/modificare-programare/{{ $programare->id }}">
                                            {{ $programare->km }} km
                                            <i class="fa-solid fa-pen-to-square fs-5"></i>
                                        </a>
                                    </span>
                                @else
                                    <span class="px-1 me-4 rounded-3 bg-danger text-white" style="white-space: nowrap; overflow: hidden;">
                                        <a class="text-white text-decoration-none" href="/mecanici/programari-mecanici/modificare-programare/{{ $programare->id }}">
                                            Adaugă km
                                            <i class="fa-solid fa-pen-to-square fs-5"></i>
                                        </a>
                                    </span>
                                @endif

                                @if ($programare->nr_auto)
                                    <span class="px-1 rounded-3 bg-secondary text-light" style="white-space: nowrap; overflow: hidden;">
                                        <a class="text-light text-decoration-none" href="/mecanici/baza-de-date-programari?search_nr_auto={{ $programare->nr_auto }}">
                                            Istoric mașină
                                        </a>
                                    </span>
                                @endif

                            </b>
                            <br>
                            {{ $programare->lucrare }}
                            <br>
                            @foreach ($programare->manopere as $manopera)
                                <div class="d-flex">
                                    <div class="" style="min-width: 70px;">
                                        <b>
                                            {{ $manopera->mecanic->name ?? ''}}
                                        </b>
                                    </div>
                                    <div>
                                        @if ($manopera->mecanic_id === auth()->user()->id)
                                            <div class="p-1 rounded-3" style="font-weight:bold; background-color:#cfeef9">
                                                {{-- <a href="/mecanici/programari-mecanici/modificare-manopera/{{ $manopera->id }}"><i class="fa-solid fa-pen-to-square fs-5"></i></a> --}}
                                                {{ $manopera->denumire }}
                                                <br>
                                                @if ($manopera->observatii)
                                                    {{ $manopera->observatii }}
                                                    <br>
                                                @endif
                                                <div class="d-flex justify-content-between">
                                                    <span class="px-1 py-0 me-4 rounded-3 bg-primary text-white text-center" style="">
                                                        <a class="text-white text-decoration-none" href="/mecanici/programari-mecanici/modificare-manopera/{{ $manopera->id }}" style="font-size:90%;">
                                                            Adaugă informații
                                                        </a>
                                                    </span>
                                                    @if (Carbon::parse($search_data)->addDays(2)->gte(Carbon::today()))
                                                        <form class="needs-validation me-1" novalidate method="GET" action="{{ route('pontaje.create')  }}">
                                                            @csrf
                                                            <input type="hidden" name="programareId" value="{{ $programare->id }}">
                                                            <input type="hidden" name="mecanicId" value="{{ $manopera->mecanic_id }}">
                                                            <input type="hidden" name="data" value="{{ $search_data }}">
                                                            <button class="btn btn-sm btn-warning rounded-3 py-0 px-1" style="height:90%; font-size:85%; font-weight:bold" type="submit">
                                                                Adaugă pontaj
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="p-1 rounded-3" style="font-weight:bold; background-color:#fcf69e">
                                                @if ($manopera->constatare_atelier)
                                                    {{ $manopera->constatare_atelier }}
                                                    <br>
                                                @endif
                                                @if ($manopera->mecanic_consumabile)
                                                    {{ $manopera->mecanic_consumabile }}
                                                    <br>
                                                @endif
                                                @if ($manopera->mecanic_observatii)
                                                    {{ $manopera->mecanic_observatii }}
                                                    <br>
                                                @endif
                                            </div>
                                        @else
                                            <div>
                                                {{ $manopera->denumire }}
                                                @if ($manopera->observatii)
                                                    <br>
                                                    {{ $manopera->observatii }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection
