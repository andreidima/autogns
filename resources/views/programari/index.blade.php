@extends ('layouts.app')

@section('content')
<div class="container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-3">
                <span class="badge culoare1 fs-5">
                    <i class="fa-solid fa-calendar-check me-1"></i>Programări
                </span>
            </div>
            @php
                // dd(Route::currentRouteName(), Route::currentRouteAction());
            @endphp

            <div class="col-lg-6">
                @if (Route::currentRouteName() === "programari.index")
                    <form class="needs-validation" novalidate method="GET" action="{{ route(Route::currentRouteName())  }}">
                        @csrf
                        <div class="row mb-1 custom-search-form justify-content-center" id="programari">
                            <div class="col-lg-6">
                                <input type="text" class="form-control rounded-3" id="search_client" name="search_client" placeholder="Client" value="{{ $search_client }}">
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control rounded-3" id="search_telefon" name="search_telefon" placeholder="Telefon" value="{{ $search_telefon }}">
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control rounded-3" id="search_nr_auto" name="search_nr_auto" placeholder="Nr. auto" value="{{ $search_nr_auto }}">
                            </div>
                            <div class="col-lg-6 d-flex justify-content-center align-items-center">
                                {{-- <div> --}}
                                    <label class="me-1">Data:</label>
                                    <vue-datepicker-next
                                        data-veche="{{ $search_data }}"
                                        nume-camp-db="search_data"
                                        tip="date"
                                        value-type="YYYY-MM-DD"
                                        format="DD-MM-YYYY"
                                        :latime="{ width: '125px' }"
                                        style="margin-right: 20px;"
                                    ></vue-datepicker-next>
                                {{-- </div> --}}
                            </div>
                        </div>
                        <div class="row custom-search-form justify-content-center">
                            <button class="btn btn-sm btn-primary text-white col-md-4 me-3 border border-dark rounded-3" type="submit">
                                <i class="fas fa-search text-white me-1"></i>Caută
                            </button>
                            <a class="btn btn-sm bg-secondary text-white col-md-4 border border-dark rounded-3" href="/programari" role="button">
                                <i class="far fa-trash-alt text-white me-1"></i>Resetează căutarea
                            </a>
                        </div>
                    </form>
                @elseif (Route::currentRouteName() === "programari.afisareCalendar")
                    <form class="needs-validation" novalidate method="GET" action="{{ route(Route::currentRouteName())  }}">
                        @csrf
                        <div class="row mb-1 custom-search-form justify-content-center" id="programari">
                            <div class="col-lg-6 d-flex justify-content-center align-items-center">
                                <label class="me-1">Data început:</label>
                                <vue-datepicker-next
                                    data-veche="{{ $search_data_inceput }}"
                                    nume-camp-db="search_data_inceput"
                                    tip="date"
                                    value-type="YYYY-MM-DD"
                                    format="DD-MM-YYYY"
                                    :latime="{ width: '125px' }"
                                    style="margin-right: 20px;"
                                ></vue-datepicker-next>
                            </div>
                            <div class="col-lg-6 d-flex justify-content-center align-items-center">
                                <label class="me-1">Data sfârșit:</label>
                                <vue-datepicker-next
                                    data-veche="{{ $search_data_sfarsit }}"
                                    nume-camp-db="search_data_sfarsit"
                                    tip="date"
                                    value-type="YYYY-MM-DD"
                                    format="DD-MM-YYYY"
                                    :latime="{ width: '125px' }"
                                    style="margin-right: 20px;"
                                ></vue-datepicker-next>
                            </div>
                        </div>
                        <div class="row mb-1 custom-search-form justify-content-center">
                            <div class="col-md-4 d-grid gap-2">
                                <button class="btn btn-sm btn-primary text-white border border-dark rounded-3" type="submit">
                                    <i class="fas fa-search text-white me-1"></i>Caută
                                </button>
                            </div>
                            <div class="col-md-4 d-grid gap-2">
                                <a class="btn btn-sm bg-secondary text-white border border-dark rounded-3" href="/programari/afisare-calendar" role="button">
                                    <i class="far fa-trash-alt text-white me-1"></i>Resetează căutarea
                                </a>
                            </div>
                        </div>
                    </form>
                    <div class="row mb-1 d-flex justify-content-center">
                        <form class="needs-validation col-md-6 d-grid gap-2" novalidate method="GET" action="{{ route(Route::currentRouteName())  }}">
                            {{-- <div class="row custom-search-form justify-content-center"> --}}
                                <input type="hidden" name="search_data_inceput" value="{{ \Carbon\Carbon::parse($search_data_inceput)->subDays(7)->startOfWeek()->toDateString() }}">
                                <input type="hidden" name="search_data_sfarsit" value="{{ \Carbon\Carbon::parse($search_data_inceput)->subDays(7)->endOfWeek()->toDateString() }}">
                                <button class="btn btn-sm btn-primary text-white border border-dark rounded-3 shadow block" type="submit">
                                    << Săptămâna anterioară
                                </button>
                            {{-- </div> --}}
                        </form>
                        <form class="needs-validation col-md-6 d-grid gap-2" novalidate method="GET" action="{{ route(Route::currentRouteName())  }}">
                            {{-- <div class="row custom-search-form justify-content-center"> --}}
                                <input type="hidden" name="search_data_inceput" value="{{ \Carbon\Carbon::parse($search_data_sfarsit)->addDays(7)->startOfWeek()->toDateString() }}">
                                <input type="hidden" name="search_data_sfarsit" value="{{ \Carbon\Carbon::parse($search_data_sfarsit)->addDays(7)->endOfWeek()->toDateString() }}">
                                <button class="btn btn-sm btn-primary text-white border border-dark rounded-3 shadow" type="submit">
                                    Săptămâna următoare >>
                                </button>
                            {{-- </div> --}}
                        </form>
                    </div>
                @endif
            </div>
            <div class="col-lg-3 text-end">
                <a class="btn btn-sm bg-success text-white border border-dark rounded-3 col-md-8" href="/programari/adauga" role="button">
                    <i class="fas fa-plus-square text-white me-1"></i>Adaugă programare
                </a>
            </div>
        </div>

        <div class="card-body px-0 py-3">

            @include ('errors')

            @if (Route::currentRouteName() === "programari.index")
                <div class="table-responsive rounded">
                    <table class="table table-striped table-hover rounded">
                        <thead class="text-white rounded culoare2">
                            <tr class="" style="padding:2rem">
                                <th class="">#</th>
                                <th class="text-center px-3">Dată și oră programare</th>
                                <th class="text-center px-3">Mașină/ Telefon</th>
                                <th class="text-center">Lucrare</th>
                                <th class="text-center">Tip lucrare</th>
                                <th class="text-center"><i class="fa-solid fa-car"></i></th>
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
                                    <td>
                                        {{ $programare->user->name ?? '' }}
                                    </td>
                                    {{-- <td class="text-center">
                                        <b>{{ $programare->data_ora_finalizare ? \Carbon\Carbon::parse($programare->data_ora_finalizare)->isoFormat('DD.MM.YYYY HH:mm') : '' }}</b>
                                    </td>
                                    <td class="text-center">
                                        <b>{{ $programare->nr_auto ?? '' }}</b>
                                    </td> --}}
                                    <td>
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ $programare->path() }}" class="flex me-1">
                                                <span class="badge bg-success">Vizualizează</span>
                                            </a>
                                            <a href="{{ $programare->path() }}/modifica" class="flex me-1">
                                                <span class="badge bg-primary">Modifică</span>
                                            </a>
                                            <div style="flex" class="">
                                                <a
                                                    href="#"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#stergeProgramarea{{ $programare->id }}"
                                                    title="Șterge Programarea"
                                                    >
                                                    <span class="badge bg-danger">Șterge</span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- <div>Nu s-au gasit rezervări în baza de date. Încearcă alte date de căutare</div> --}}
                            @endforelse
                            </tbody>
                    </table>
                </div>

                    <nav>
                        <ul class="pagination justify-content-center">
                            {{$programari->appends(Request::except('page'))->links()}}
                        </ul>
                    </nav>
            @elseif (Route::currentRouteName() === "programari.afisareCalendar")
                <div class="table-responsive rounded mb-4">
                    <table class="table table-striped table-hover table-sm rounded table-bordered">
                        <thead class="rounded culoare2" style="">
                            <tr class="" style="padding:2rem">
                                {{-- <th class="px-0 text-center">Ora</th>
                                <th class="px-0 text-center">Minute</th> --}}
                                @for ($ziua = \Carbon\Carbon::parse($search_data_inceput); $ziua <= \Carbon\Carbon::parse($search_data_sfarsit); $ziua->addDay())
                                    @if ($ziua->dayOfWeekIso != 7)
                                        <td colspan="3" class="px-0 text-center" style="">
                                    @else
                                        <td colspan="3" class="px-0 text-center culoare1" style="">
                                    @endif
                                            {{ ucfirst($ziua->dayName) }}
                                            <br>
                                            {{ $ziua->isoFormat('DD.MM') }}
                                    </td>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @for ($ora = \Carbon\Carbon::now()->hour(8)->minute(0)->second(0); $ora <= \Carbon\Carbon::now()->hour(16)->minute(50)->second(0) ; $ora->addMinutes(10))
                                {{-- La ora 12 este pauza de masa, asa ca se sare peste aceasta ora --}}
                                {{-- @if ($ora->hour === 12)
                                    <tr class="culoare1" style="line-height: 25 px; min-height: 25 px;height:5px; font: size 5px;">
                                        <td colspan="{{ (\Carbon\Carbon::parse($search_data_sfarsit)->diffInDays(\Carbon\Carbon::parse($search_data_inceput)) + 1) * 3}}" class="px-0 py-0 text-center text-white align-middle">
                                            <b>{{ $ora->isoFormat('HH:00') }} - pauză de masă</b>
                                        </td>
                                    </tr>
                                    @php
                                        $ora->hour(12)->minute(50);
                                    @endphp
                                @else --}}
                                    <tr style="
                                            line-height: 15px;
                                            min-height: 15px;height:5px; font: size 5px;">
                                        @for ($ziua = \Carbon\Carbon::parse($search_data_inceput); $ziua <= \Carbon\Carbon::parse($search_data_sfarsit); $ziua->addDay())


                                            @php
                                                $ziua->hour($ora->hour)->minute($ora->minute);
                                            @endphp

                                                @if ((($ziua->dayOfWeekIso < 6) && ($ora->hour === 12)))
                                                    {{-- Pauza de masa --}}
                                                    @if ($ora->minute === 0)
                                                        <td rowspan="6" class="px-1 py-0 text-center text-white culoare2 align-middle" style="width: 1%; white-space: nowrap; opacity:0.6; ">
                                                            <b>{{ $ora->isoFormat('HH') }}</b>
                                                        </td>
                                                    @endif
                                                    <td class="px-1 py-0 text-center text-white culoare2" style="width: 1%; white-space: nowrap; font-size:90%; opacity:1;">
                                                        {{ $ora->isoFormat('mm') }}
                                                    </td>
                                                    <td></td>
                                                @elseif (
                                                        (($ziua->dayOfWeekIso === 6) && ($ora->hour >= 13)) || // Sambata program pana la 13:00
                                                        ($ziua->dayOfWeekIso === 7) // Duminica liber
                                                    )
                                                    <td colspan="3"></td>
                                                @else
                                                    @if ($ora->minute === 0)
                                                        <td rowspan="6" class="px-1 py-0 text-center text-white culoare2 align-middle" style="width: 1%; white-space: nowrap; opacity:0.6; ">
                                                            <b>{{ $ora->isoFormat('HH') }}</b>
                                                        </td>
                                                    @endif
                                                    <td class="px-1 py-0 text-center text-white culoare2" style="width: 1%; white-space: nowrap; font-size:90%; opacity:1;">
                                                        {{ $ora->isoFormat('mm') }}
                                                    </td>
                                                    <td class="px-0 py-0 text-start">
                                                        <div class="d-flex" style="min-width: 50px;">
                                                            @php
                                                                $nr_masini = 0;
                                                                // $canal = 0;
                                                                // $geometrie = 0;
                                                                // $freon = 0;
                                                            @endphp
                                                            @foreach ($programari->where('data_ora_programare', '<=', $ziua)->where('data_ora_finalizare', '>=', $ziua->addMinutes(10)) as $programare)
                                                                <a href="{{ $programare->path() }}/modifica">
                                                                    @switch($nr_masini)
                                                                        @case(0)
                                                                            <div class="text-white text-center rounded-3" style="min-width:30px; height: 100%; background-color:rgb(0, 110, 37);">
                                                                            @break
                                                                        @case(1)
                                                                            <div class="text-white text-center rounded-3" style="min-width:30px; height: 100%; background-color:rgb(48, 151, 0);">
                                                                            @break
                                                                        @case(2)
                                                                            <div class="text-white text-center rounded-3" style="min-width:30px; height: 100%; background-color:rgb(145, 161, 0);">
                                                                            @break
                                                                        @case(3)
                                                                            <div class="text-white text-center rounded-3" style="min-width:30px; height: 100%; background-color:RED;">
                                                                            @break
                                                                        @default
                                                                            <div class="text-white text-center rounded-3" style="min-width:30px; height: 100%; background-color:rgb(196, 0, 0);">
                                                                    @endswitch
                                                                    @php
                                                                        ++$nr_masini;
                                                                        $mesaj = '';
                                                                    @endphp
                                                                        @if ($programare->geometrie_turism === 1)
                                                                            @php
                                                                                $mesaj .= 'GT';
                                                                            @endphp
                                                                        @endif
                                                                        @if ($programare->geometrie_camion === 1)
                                                                            @php
                                                                                $mesaj .= 'GC';
                                                                            @endphp
                                                                        @endif
                                                                        @if ($programare->freon === 1)
                                                                            @php
                                                                                $mesaj .= 'F';
                                                                            @endphp
                                                                        @endif
                                                                        @if ($mesaj === '')
                                                                            @php
                                                                                $mesaj .= '&nbsp;';
                                                                            @endphp
                                                                        @endif
                                                                        <p class="m-0 p-0" style="white-space: nowrap;"
                                                                            title="{{ $programare->nr_auto . ': ' . $programare->lucrare }}">
                                                                            {!! $mesaj !!}
                                                                        </p>
                                                                    </div>

                                                                    {{-- @if ($programare->lucrare_canal === 1)
                                                                        @php
                                                                            $canal++;
                                                                        @endphp
                                                                    @endif
                                                                    @if ($programare->lucrare_geometrie === 1)
                                                                        @php
                                                                            $geometrie++;
                                                                        @endphp
                                                                    @endif
                                                                    @if ($programare->lucrare_freon === 1)
                                                                        @php
                                                                            $freon++;
                                                                        @endphp
                                                                    @endif
                                                                    @if (($programare->lucrare_canal === 0) && ($programare->lucrare_geometrie === 0) && ($programare->lucrare_freon === 0))
                                                                        @php
                                                                            $nr_masini++;
                                                                        @endphp
                                                                    @endif --}}
                                                                </a>

                                                            @endforeach

                                                            {{-- @for ($i = 0; $i < $canal; $i++)
                                                                <span class="badge rounded-pill" style="background-color:#00a100">&nbsp;</span>
                                                            @endfor
                                                            @for ($i = 0; $i < $geometrie; $i++)
                                                                <span class="badge rounded-pill" style="background-color:#ff4141">&nbsp;</span>
                                                            @endfor
                                                            @for ($i = 0; $i < $freon; $i++)
                                                                <span class="badge rounded-pill" style="background-color:#298dff">&nbsp;</span>
                                                            @endfor
                                                            @for ($i = 0; $i < ($nr_masini - $canal - $geometrie - $freon); $i++)
                                                                <span class="badge rounded-pill" style="background-color:#757575">&nbsp;</span>
                                                            @endfor --}}

                                                        </div>
                                                @endif

                                            @php
                                                $ziua->hour(0)->minute(0); // altfel ultima zi din iteratie nu va mai fi egala cu search_data_sfarsit, va fi mai mai cu acele ore adaugate
                                            @endphp
                                            </td>
                                        @endfor
                                    </tr>
                                {{-- @endif --}}
                            @endfor
                        </tbody>
                    </table>
                </div>

        {{-- <div class="row mb-2">
            @for ($ziua = \Carbon\Carbon::parse($search_data_inceput); $ziua <= \Carbon\Carbon::parse($search_data_sfarsit); $ziua->addDay())
                <div class="col-md-2 p-0">
                    <div class="" style="border-radius: 20px 20px 0px 0px;">
                        <div class="border border-secondary p-2" style="border-radius: 20px 20px 0px 0px; background-color:#005a5a">
                            <h5 class="my-0 text-center" style="color:white">
                                <b>{{ $ziua->isoFormat('dddd') }}</b>
                                <br>
                                {{ $ziua->isoFormat('DD') }}
                            </h5>
                        </div>
                        <div class="d-flex">
                                    <div class="" style="width: 30px;">
                                        @for ($i = 8; $i <= 16; $i++)
                                            <div class="border border-secondary text-center" style="width: 100%; height:60px; background-color:white;">
                                                {{ $i }}
                                            </div>
                                        @endfor
                                    </div>

                                    <div class="" style="width:100%">
                                        @php
                                            $ora_initiala = \Carbon\Carbon::now();
                                            $ora_initiala->hour = 9;
                                            $ora_initiala->minute = 0;
                                            $ora_initiala->second = 0;

                                            $ora_finala = \Carbon\Carbon::now();
                                            $ora_finala->hour = 19;
                                            $ora_finala->minute = 0;
                                            $ora_finala->second = 0;

                                            $total_minute_zi = 0;
                                        @endphp

                                        @forelse ($programari->where('programare_data', \Carbon\Carbon::now()->weekday($week_day)->toDateString()) as $programare)
                                            @php
                                                //variabila pentru crearea divului gol
                                                $durata_minute_inainte = $ora_initiala->diffInMinutes($programare->programare_ora);
                                                //variabila pentru crearea divului cu programarea
                                                $durata_minute =
                                                    (
                                                        \Carbon\Carbon::parse($programare->programare_durata)->isoFormat('HH') * 60
                                                        +
                                                        \Carbon\Carbon::parse($programare->programare_durata)->isoFormat('mm')
                                                    );
                                                //setarea orei de unde incepe calcularea la programarea urmatoare
                                                $ora_initiala = \Carbon\Carbon::parse($programare->programare_ora)->addMinutes($durata_minute);
                                                //variabila de control
                                                $total_minute_zi += $durata_minute_inainte + $durata_minute;
                                            @endphp
                                            <div class="border border-secondary" style="width: 100%; background-color:white; height:{{ $durata_minute_inainte }}px;">
                                            </div>
                                            <div class="border border-secondary text-center p-0 text-white" style="width: 100%; background-color:darkgreen; height:{{ $durata_minute }}px;
                                                line-height: 1;"
                                                title="
                                                        {{
                                                            \Carbon\Carbon::parse($programare->programare_ora)->isoFormat('HH:mm') .
                                                            '-' .
                                                            \Carbon\Carbon::parse($programare->programare_ora)->addMinutes($durata_minute)->isoFormat('HH:mm')
                                                        }}. {{ $programare->pacient->nume ?? '' }} {{ $programare->pacient->prenume ?? ''}}.@foreach ($programare->servicii as $serviciu) {{ $serviciu->nume ?? '' }}.@endforeach
                                                        "
                                                >
                                                {{
                                                    $programare->pacient->nume ?? ''
                                                }}
                                                {{
                                                    $programare->pacient->prenume ?? ''
                                                }}
                                                <br>
                                                {{
                                                            \Carbon\Carbon::parse($programare->programare_ora)->isoFormat('HH:mm') .
                                                            '-' .
                                                            \Carbon\Carbon::parse($programare->programare_ora)->addMinutes($durata_minute)->isoFormat('HH:mm')
                                                }}
                                            </div>

                                            @if ($loop->last)
                                                @php
                                                    $durata_minute_final = $ora_finala->diffInMinutes($ora_initiala);
                                                    $total_minute_zi += $durata_minute_final;
                                                @endphp
                                                @if ($total_minute_zi > 600)
                                                    <script type="application/javascript">
                                                        programariSuprapuse{!! json_encode($week_day) !!}=true;
                                                    </script>
                                                @else
                                                    <div class="border border-secondary" style="width: 100%; background-color:white; height:{{ $durata_minute_final }}px;">
                                                    </div>
                                                @endif
                                            @endif
                                        @empty
                                            <div class="border border-secondary" style="width: 100%; background-color:white; height:600px;">
                                            </div>
                                        @endforelse
                                    </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div> --}}

            @endif
        </div>
    </div>

    {{-- Modalele pentru stergere programare --}}
    @if (Route::currentRouteName() === "programari.index")
        @foreach ($programari as $programare)
            <div class="modal fade text-dark" id="stergeProgramarea{{ $programare->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="exampleModalLabel">Programarea: <b>{{ $programare->client ?? '' }}</b></h5>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="text-align:left;">
                        Ești sigur ca vrei să ștergi Programarea?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>

                        <form method="POST" action="{{ $programare->path() }}">
                            @method('DELETE')
                            @csrf
                            <button
                                type="submit"
                                class="btn btn-danger text-white"
                                >
                                Șterge Programarea
                            </button>
                        </form>

                    </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif


@endsection
