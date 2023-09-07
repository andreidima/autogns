@extends ('layouts.app')

@section('content')
<div class="container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <form class="needs-validation" novalidate method="GET" action="{{ url()->current() }}">
                @csrf
                <div class="col-lg-12 d-flex justify-content-between">
                    <input type="hidden" name="search_data" value="{{ $search_data }}">

                    <span class="badge mb-2 fs-5" style="background-color: #e66800">
                        <i class="fa-solid fa-calendar-check me-1"></i>Bonusuri
                    </span>

                    <div class="mb-2 d-flex justify-content-center align-items-center">
                        <button class="btn btn-sm btn-primary text-white border border-dark rounded-3 shadow block"
                            name="schimbaLuna" value="oLunaInapoi" type="submit">
                            <
                        </button>
                        <span class="badge fs-5 p-0 mx-1 text-black" style="background-color: #ffffff">
                            <label class="mx-0">{{ \Carbon\Carbon::parse($search_data)->isoFormat('MMMM YYYY') }}</label>
                        </span>

                        <button class="btn btn-sm btn-primary text-white border border-dark rounded-3 shadow block"
                            name="schimbaLuna" value="oLunaInainte" type="submit">
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
                            <th class="text-center px-1">Ziua</th>
                            <th class="text-center px-1">Mașina</th>
                            <th class="text-center">Manopera</th>
                            <th class="text-center px-1">Preț</th>
                            <th class="text-center px-1">Bonus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $nrManopere = 0;
                            $pretTotal = 0;
                            $bonusTotal = 0;
                        @endphp
                        @foreach ($programari as $programare)
                            @foreach ($programare->manopere as $manopera)
                                @php
                                    $nrManopere ++;
                                    $pretTotal += $manopera->pret;
                                    $bonusTotal += $manopera->bonus_mecanic;
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        @if ($programare->data_ora_finalizare)
                                            {{ \Carbon\Carbon::parse($programare->data_ora_finalizare)->isoFormat('DD') }}
                                        @elseif ($programare->data_ora_programare)
                                            {{ \Carbon\Carbon::parse($programare->data_ora_programare)->isoFormat('DD') }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $programare->masina }}
                                    </td>
                                    <td>
                                        {{ $manopera->denumire }}
                                    </td>
                                    <td style="text-align: right">
                                        <b>{{ $manopera->pret }}</b>
                                    </td>
                                    <td style="text-align: right">
                                        <b>{{ $manopera->bonus_mecanic }}</b>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>

                <h4 class="text-center" style="font-weight: bold">
                    Total lucrări: {{ $nrManopere }}
                    <br>
                    Total manopera: {{ $pretTotal }} lei
                    <br>
                    Total bonus: {{ $bonusTotal }} lei
                </h5>
            </div>
        </div>
    </div>

@endsection
