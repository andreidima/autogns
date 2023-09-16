@extends ('layouts.app')

@php
    use \Carbon\Carbon;
@endphp

@section('content')
{{-- <div class="container card" style="border-radius: 40px 40px 40px 40px;"> --}}
<div class="card mx-3" style="border-radius: 40px 40px 40px 40px;">
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
                                    <button class="btn btn-sm btn-primary text-white border border-dark rounded-3 shadow block"
                                        name="schimba_ziua" value="o_zi_inapoi" type="submit">
                                        <
                                    </button>
                                    <vue-datepicker-next
                                        data-veche="{{ $search_data }}"
                                        nume-camp-db="search_data"
                                        :zile-nelucratoare="{{ App\Models\ZiNelucratoare::select('data')->get()->pluck('data') }}"
                                        tip="date"
                                        value-type="YYYY-MM-DD"
                                        format="DD-MM-YYYY"
                                        :latime="{ width: '125px' }"
                                        style="margin: 0px 5px;"
                                    ></vue-datepicker-next>
                                    <button class="btn btn-sm btn-primary text-white border border-dark rounded-3 shadow block"
                                        name="schimba_ziua" value="o_zi_inainte" type="submit">
                                {{-- </div> --}}
                                    >
                                    </button>
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
                                <th class="text-center" style="min-width:170px;">Mecanic (pontaje)</th>
                                <th class="text-center"><i class="fa-solid fa-car fs-4"></i></th>
                                <th class="text-center"><span style="font-size: 100%">Conf.</span><i class="fa-solid fa-comment-sms fs-4"></i></th>
                                <th class="text-center">Operator</th>
                                <th class="text-end">Acțiuni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($programari as $programare)
                                @if ($programare->manopere->where('vazut', 0)->count())
                                    <tr style="background-color: #ff000038;">
                                @elseif ($programare->manopere->where('pret', '>', 0)->count())
                                    <tr style="background-color: #00ff0053;">
                                @else
                                    <tr>
                                @endif
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
                                        @foreach ($programare->manopere->unique('mecanic_id') as $manopera)
                                            <div class="border border-1">
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <form class="needs-validation me-1" novalidate method="GET" action="{{ route('pontaje.create')  }}">
                                                        @csrf
                                                        <input type="hidden" name="programareId" value="{{ $programare->id }}">
                                                        <input type="hidden" name="mecanicId" value="{{ $manopera->mecanic_id }}">
                                                        <input type="hidden" name="data" value="{{ $search_data }}">
                                                        <button
                                                            class="btn btn-sm bg-white"
                                                            type="submit" style="width:20px; height:20px; padding:0px;">
                                                            <i class="fas fa-plus-square text-success fa-xl" style=""></i>
                                                            {{-- <h1 class="m-0 p-0">+</h1> --}}
                                                        </button>
                                                    </form>
                                                    {{ $manopera->mecanic->name ?? ''}}
                                                </div>
                                                @foreach ($programare->pontaje as $pontaj)
                                                    @if (($manopera->mecanic->id ?? '') === $pontaj->mecanic_id)
                                                        <div class="d-flex justify-content-center align-items-center" style="overflow: auto; white-space: nowrap;">
                                                            ({{ $pontaj->inceput ? Carbon::parse($pontaj->inceput)->isoFormat('HH:mm') : '' }}-{{ $pontaj->sfarsit ? Carbon::parse($pontaj->sfarsit)->isoFormat('HH:mm') : '' }})
                                                            <form class="needs-validation me-1" novalidate method="GET" action="{{ route('pontaje.edit', ['pontaj' => $pontaj->id])  }}">
                                                                @csrf
                                                                <button
                                                                    class="btn btn-sm bg-white rounded-1 border-0"
                                                                    type="submit" style="width:20px; height:20px; padding:0px;">
                                                                    <i class="fa-solid fa-square-pen text-primary fa-xl" style=""></i>
                                                                </button>
                                                            </form>
                                                            <a href="#"
                                                                class="bg-danger rounded-1 border-0 p-0"
                                                                style="width:18px; height:18px; padding:0px;"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#stergePontaj{{ $pontaj->id }}"
                                                                title="Șterge Pontaj"
                                                                >
                                                                <i class="far fa-trash-alt text-white fa-sm"></i>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endforeach
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
                                    <td class="text-center">
                                        <div>
                                            @php
                                                $mesaj = '';
                                                if (is_null($programare->confirmare)){
                                                    $mesaj .= '<i class="fa-solid fa-question text-warning fs-4"></i>';
                                                } else if ($programare->confirmare == 0){
                                                    $mesaj .= '<i class="fa-solid fa-thumbs-down text-danger fs-4" title="';
                                                } else if ($programare->confirmare == 1){
                                                    $mesaj .= '<i class="fa-solid fa-thumbs-up text-success fs-4" title="';
                                                }
                                                if (!is_null($programare->confirmare)){
                                                    foreach ($programare->programare_istoric->where('confirmare_client_timestamp')->unique('confirmare_client_timestamp') as $programare_istoric){
                                                    $mesaj .= \Carbon\Carbon::parse($programare_istoric->confirmare_client_timestamp)->isoFormat('DD.MM.YYYY HH:mm');
                                                        if ($programare_istoric->confirmare === 0){
                                                            $mesaj .= ' NU --- ';
                                                        }
                                                        if ($programare_istoric->confirmare === 1){
                                                            $mesaj .= ' DA --- ';
                                                        }
                                                    }
                                                    $mesaj = substr_replace($mesaj ,"", -5); // remove last ---
                                                    $mesaj .='"></i>';
                                                }
                                            @endphp
                                            {!! $mesaj !!}

                                            {{-- @if (auth()->user()->id === 1)
                                                <a href="/programare-cerere-confirmare-sms/{{$programare->cheie_unica}}" class="flex me-1" title="Cere Confirmare">
                                                    C
                                                </a>
                                            @endif --}}
                                        </div>
                                        <div style="white-space: nowrap;">
                                            <span class="bg-secondary text-white px-1" title="Smsuri inregistrare confirmare finalizare trimise" style="">
                                                {{ $programare->smsuri->where('categorie', 'programari')->where('subcategorie', 'inregistrare')->where('trimis', 1)->count() }}
                                                {{ $programare->smsuri->where('categorie', 'programari')->where('subcategorie', 'confirmare')->where('trimis', 1)->count() }}
                                                {{ $programare->smsuri->where('categorie', 'programari')->where('subcategorie', 'finalizare')->where('trimis', 1)->count() }}
                                            </span>
                                        </div>
                                        {{-- <div style="white-space: nowrap;">
                                            <span class="bg-secondary text-white px-1" title="Smsuri " style="">{{ $programare->smsuri->where('subcategorie', 'inregistrare')->where('trimis', 0)->count() }}</span>
                                            <span class="bg-secondary text-white px-1" title="Smsuri " style="">{{ $programare->smsuri->where('subcategorie', 'confirmare')->where('trimis', 0)->count() }}</span>
                                            <span class="bg-secondary text-white px-1" title="Smsuri " style="">{{ $programare->smsuri->where('subcategorie', 'finalizare')->where('trimis', 0)->count() }}</span>
                                        </div> --}}
                                        {{-- @foreach ($programare->smsuri as $sms)

                                        @endforeach --}}
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
                                    <td style="text-end">
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ $programare->path() }}/fisa-pdf" target="_blank" class="me-1">
                                                <span class="badge bg-warning text-dark">
                                                    Fișa
                                                </span></a>
                                            <a href="{{ $programare->path() }}" class="">
                                                <span class="badge bg-success">
                                                    Vizualizează
                                                </span></a>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ $programare->path() }}/modifica" class="me-1">
                                                <span class="badge bg-primary">Modifică</span></a>
                                            <a href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#stergeProgramarea{{ $programare->id }}"
                                                title="Șterge Programarea"
                                                >
                                                <span class="badge bg-danger">Șterge</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                {{-- <div>Nu s-au gasit rezervări în baza de date. Încearcă alte date de căutare</div> --}}
                            @endforelse
                            </tbody>
                    </table>

                    <p class="ms-5 my-0 p-0">* Programările colorate cu roșu au informații noi de la mecanici. Dacă se intră pe vizualizare pentru a se vedea informațiile, culoarea va reveni la normal.</p>
                    <p class="ms-5 my-0 p-0">* Programările colorate cu verde au adăugate prețul la manopere.</p>
                </div>

                    <nav>
                        <ul class="pagination justify-content-center">
                            {{$programari->appends(Request::except('page'))->links()}}
                        </ul>
                    </nav>
            @elseif (Route::currentRouteName() === "programari.afisareCalendar")
                <div class="table-responsive rounded mb-4" style="height:90vh">
                    <table class="table table-striped table-hover table-sm rounded table-bordered">
                        <thead class="rounded" style="">
                            <tr class="culoare2" style="padding:2rem; position: sticky; top: 0; z-index: 1;">
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

    {{-- Modalele pentru stergere pontaje --}}
    @if (Route::currentRouteName() === "programari.index")
        @foreach ($programari as $programare)
            @foreach ($programare->pontaje as $pontaj)
                <div class="modal fade text-dark" id="stergePontaj{{ $pontaj->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title text-white" id="exampleModalLabel">Pontaj mecanic <b>{{ $pontaj->mecanic->name ?? '' }}</b>, masina <b>{{ $pontaj->programare->masina ?? '' }}</b></h5>
                            <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="text-align:left;">
                            Ești sigur ca vrei să ștergi Pontajul?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>

                            <form method="POST" action="{{ $pontaj->path() }}">
                                @method('DELETE')
                                @csrf
                                <button
                                    type="submit"
                                    class="btn btn-danger text-white"
                                    >
                                    Șterge Pontajul
                                </button>
                            </form>

                        </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    @endif


@endsection
