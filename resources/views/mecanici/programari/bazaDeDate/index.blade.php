@extends ('layouts.app')

@section('content')
<div class="container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-12 d-flex justify-content-between">
                <span class="badge mb-2 fs-5" style="background-color: #e66800">
                    <i class="fa-solid fa-database me-1"></i>Baza de date Programări
                </span>
            </div>
            <form class="needs-validation" novalidate method="GET" action="{{ url()->current() }}">
                @csrf
                <div class="col-lg-12 justify-content-between">
                    <div class="row mb-2 custom-search-form justify-content-center" id="programari">
                        <div class="col-lg-6">
                            <input type="text" class="form-control bg-white rounded-3" id="search_masina" name="search_masina" placeholder="Masina" value="{{ $search_masina }}">
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control bg-white rounded-3" id="search_nr_auto" name="search_nr_auto" placeholder="Nr. auto" value="{{ $search_nr_auto }}">
                        </div>
                        <div class="col-lg-6 d-flex justify-content-center align-items-center">
                            <label class="me-1">Data:</label>
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
                        </div>
                    </div>
                    <div class="row custom-search-form justify-content-center">
                        <button class="btn btn-sm btn-primary text-white col-md-4 mx-3 border border-dark rounded-3" type="submit">
                            <i class="fas fa-search text-white me-1"></i>Caută
                        </button>
                        <a class="btn btn-sm bg-secondary text-white col-md-4 mx-3 border border-dark rounded-3" href="/mecanici/baza-de-date-programari" role="button">
                            <i class="far fa-trash-alt text-white me-1"></i>Resetează căutarea
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body px-0 py-3">

            @include ('errors')

            @foreach ($programari as $programare)
                <div class="row">
                    <div class="col-lg-12 d-flex" style="border-bottom: 4px solid black">
                        <div class="" style="min-width: 90px;">
                            <b>
                                {{ $programare->data_ora_programare ? \Carbon\Carbon::parse($programare->data_ora_programare)->isoFormat('DD.MM.YYYY') : '' }}
                            </b>
                            <br>
                            <a href="/mecanici/baza-de-date-programari/{{ $programare->id }}" class="">
                                <span class="badge bg-success">Vizualizează</span></a>
                        </div>
                        <div>
                            <span class="px-1 rounded-3" style="color:#ffffff; background-color:#e66800">
                                {{ $programare->masina }} <b>{{ $programare->nr_auto }}</b>
                            </span>
                            <br>
                            {{ $programare->lucrare }}
                            <br>
                            Manopere:
                            @foreach ($programare->manopere as $manopera)
                                {{ $manopera->mecanic->name ?? ''}} ({{ $manopera->denumire }})
                                @if (!$loop->last)
                                    <br>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="row">
                <div class="col-lg-12 my-2">
                    <nav>
                        <ul class="pagination justify-content-center">
                            {{$programari->appends(Request::except('page'))->links()}}
                        </ul>
                    </nav>
                </div>
            </div>

        </div>
    </div>

@endsection
