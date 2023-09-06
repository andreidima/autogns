@extends ('layouts.app')

@section('content')
<div class="container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <form class="needs-validation" novalidate method="GET" action="{{ route(Route::currentRouteName())  }}">
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
                    <div class="col-lg-12 d-flex">
                        <div class="" style="width: 50px;">
                            {{ $programare->data_ora_programare ? \Carbon\Carbon::parse($programare->data_ora_programare)->isoFormat('HH:mm') : '' }}
                        </div>
                        <div class="">
                            {{ $programare->masina }}
                            <br>
                            {{ $programare->lucrare }}
                        </div>
                    </div>
                </div>
            @endforeach



            {{-- <div class="table-responsive rounded">
                <table class="table table-striped table-hover rounded">
                    <thead class="text-white rounded culoare2">
                        <tr class="" style="padding:2rem">
                            <th class="">#</th>
                            <th class="text-center px-3">Dată și oră programare</th>
                            <th class="text-center px-3">Mașină/ Telefon</th>
                            <th class="text-center">Lucrare</th>
                            <th class="text-center">Tip lucrare</th>
                            <th class="text-center"><i class="fa-solid fa-car fs-4"></i></th>
                            <th class="text-center"><span style="font-size: 100%">Conf.</span><i class="fa-solid fa-comment-sms fs-4"></i></th>
                            <th class="text-center">Operator</th>
                            <th class="text-end">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($programari as $programare)
                            <tr>
                                <td align="">
                                    {{ ($programari ->currentpage()-1) * $programari ->perpage() + $loop->index + 1 }}
                                </td>
                                <td class="text-center">
                                    {{ $programare->data_ora_programare ? \Carbon\Carbon::parse($programare->data_ora_programare)->isoFormat('HH:mm') : '' }}
                                    <br>
                                    {{ $programare->data_ora_programare ? \Carbon\Carbon::parse($programare->data_ora_programare)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                                <td class="px-3">
                                    {{ $programare->masina ?? '' }}
                                    <br>
                                    {{ $programare->telefon ?? '' }}
                                </td>
                                <td>
                                    {{ $programare->lucrare ?? '' }}
                                </td>
                                <td class="text-center">
                                    @if ($programare->geometrie_turism === 1)
                                        <span class="me-1 px-1 culoare1 text-white">GT</span>
                                    @endif
                                    @if ($programare->geometrie_camion === 1)
                                        <span class="me-1 px-1 culoare1 text-white">GC</span>
                                    @endif
                                    @if ($programare->freon === 1)
                                        <span class="me-1 px-1 culoare1 text-white">F</span>
                                    @endif
                                    @if (($programare->geometrie_turism === 0) && ($programare->geometrie_camion === 0) && ($programare->freon === 0))
                                        <span class="me-1 px-1 culoare1 text-white">M</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @switch($programare->stare_masina)
                                        @case(0)
                                            <i class="fa-solid fa-xmark fs-4 text-info" title="Nu este la service"></i>
                                            @break
                                        @case(1)
                                            <i class="fa-solid fa-key fs-4 text-danger" title="În așteptare"></i>
                                            @break
                                        @case(2)
                                            <i class="fa-solid fa-wrench fs-4 text-warning" title="În lucru"></i>
                                            @break
                                        @case(3)
                                            <i class="fa-solid fa-check-double fs-4 text-success" title="Finalizată"></i>
                                            @break
                                        @default

                                    @endswitch
                                </td>

                            </tr>
                        @endforelse
                        </tbody>
                </table>
            </div> --}}
        </div>
    </div>

@endsection
