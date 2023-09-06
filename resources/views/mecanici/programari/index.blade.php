@extends ('layouts.app')

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

                        {{-- <label class="mx-2">Data: {{ \Carbon\Carbon::parse($search_data)->isoFormat('DD.MM.YYYY') }}</label> --}}
                        <span class="badge fs-5 p-0 mx-1 text-black" style="background-color: #ffffff">
                            <label class="mx-0">{{ \Carbon\Carbon::parse($search_data)->isoFormat('DD.MM.YYYY') }}</label>
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
                        {{-- <div class="col-2"> --}}
                            <b>
                                {{ $programare->data_ora_programare ? \Carbon\Carbon::parse($programare->data_ora_programare)->isoFormat('HH:mm') : '' }}
                            </b>
                        </div>
                        <div class="">
                            <b>
                                {{ $programare->masina }} {{ $programare->nr_auto }}
                            </b>
                            <br>
                            {{ $programare->lucrare }}
                            <br>
                            @foreach ($programare->manopere as $manopera)
                                <div class="d-flex">
                                    <div class="" style="min-width: 70px;">
                                        <b>
                                            {{ $manopera->mecanic->name }}
                                        </b>
                                    </div>
                                    <div>
                                        {{ $manopera->denumire }}
                                        <br>
                                        {{ $manopera->observatii }}
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