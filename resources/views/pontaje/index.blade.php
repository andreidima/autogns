@extends ('layouts.app')

@php
    use \Carbon\Carbon;
@endphp

@section('content')
<div class="container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-2">
                <span class="badge culoare1 fs-5">
                    <i class="fa-solid fa-clock me-1"></i>
                    Pontaje
                </span>
            </div>
            <div class="col-lg-8">
                <form class="needs-validation" novalidate method="GET" action="{{ route(Route::currentRouteName())  }}">
                    @csrf
                    <div class="row mb-1 custom-search-form justify-content-center" id="datepicker">
                        <div class="col-lg-5 d-flex justify-content-center align-items-center">
                            <label for="mecanicId" class="me-1">Mecanic</label>
                            <select name="mecanicId" class="form-select bg-white rounded-3 {{ $errors->has('mecanic') ? 'is-invalid' : '' }}">
                                <option selected></option>
                                @foreach ($mecanici as $mecanic)
                                    <option value="{{ $mecanic->id }}" {{ $mecanic->id == $mecanicId ? 'selected' : '' }}>
                                        {{ $mecanic->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <input type="text" class="form-control rounded-3" id="nrAuto" name="nrAuto" placeholder="Nr. auto" value="{{ $nrAuto }}">
                        </div>
                        <div class="col-lg-4 d-flex justify-content-center align-items-center">
                            <label class="me-1">Data:</label>
                            <vue-datepicker-next
                                data-veche="{{ $data }}"
                                nume-camp-db="data"
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
                        <button class="btn btn-sm btn-primary text-white col-md-4 me-3 border border-dark rounded-3" type="submit">
                            <i class="fas fa-search text-white me-1"></i>Caută
                        </button>
                        <a class="btn btn-sm bg-secondary text-white col-md-4 border border-dark rounded-3" href="/pontaje" role="button">
                            <i class="far fa-trash-alt text-white me-1"></i>Resetează căutarea
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-lg-2 text-end">
                {{-- <a class="btn btn-sm bg-success text-white border border-dark rounded-3 col-md-8" href="/pontaje/adauga" role="button">
                    <i class="fas fa-plus-square text-white me-1"></i>Adaugă pontaj
                </a> --}}
            </div>
        </div>

        <div class="card-body px-0 py-3">

            @include ('errors')

            <div class="table-responsive rounded">
                <table class="table table-striped table-hover rounded">
                    <thead class="text-white rounded culoare2">
                        <tr class="" style="padding:2rem">
                            <th class="">#</th>
                            <th class="text-center px-3">Mecanic</th>
                            <th class="text-center px-3">Programare</th>
                            <th class="text-center px-3">Data</th>
                            <th class="text-end">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pontaje as $pontaj)
                            <tr>
                                <td align="">
                                    {{ ($pontaje ->currentpage()-1) * $pontaje ->perpage() + $loop->index + 1 }}
                                </td>
                                <td>
                                    {{ $pontaj->mecanic->name ?? '' }}
                                </td>
                                <td>
                                    {{ $pontaj->programare->masina ?? '' }} {{ $pontaj->programare->nr_auto ?? '' }}
                                </td>
                                <td class="text-center">
                                    @if ($pontaj->inceput)
                                        {{ Carbon::parse($pontaj->inceput)->isoFormat('DD.MM.YYYY') }}
                                        <b>{{ Carbon::parse($pontaj->inceput)->isoFormat('HH:mm') }} - </b>
                                    @endif
                                    @if ($pontaj->sfarsit)
                                        <b>{{ Carbon::parse($pontaj->sfarsit)->isoFormat('HH:mm') }}
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ $pontaj->path() }}/modifica" class="flex me-1">
                                            <span class="badge bg-primary">Modifică</span>
                                        </a>
                                        <div style="flex" class="">
                                            <a
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#stergePontaj{{ $pontaj->id }}"
                                                title="Șterge Pontaj"
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
                        {{$pontaje->appends(Request::except('page'))->links()}}
                    </ul>
                </nav>
        </div>
    </div>

    {{-- Modalele pentru stergere pontaje --}}
    @foreach ($pontaje as $pontaj)
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


@endsection
