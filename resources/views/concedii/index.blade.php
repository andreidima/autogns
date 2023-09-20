@extends ('layouts.app')

@php
    use \Carbon\Carbon;
@endphp

@section('content')
<div class="container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-2">
                <span class="badge culoare1 fs-5">
                    <i class="fa-solid fa-users-slash me-1"></i>
                    Concedii
                </span>
            </div>
            <div class="col-lg-8">
                <form class="needs-validation" novalidate method="GET" action="{{ route(Route::currentRouteName())  }}">
                    @csrf
                    <div class="row mb-1 custom-search-form justify-content-center" id="datepicker">
                        <div class="col-lg-5 d-flex justify-content-center align-items-center">
                            <label for="userId" class="me-1">Mecanic</label>
                            <select name="userId" class="form-select bg-white rounded-3 {{ $errors->has('userId') ? 'is-invalid' : '' }}">
                                <option selected></option>
                                @foreach ($useri as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == $userId ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
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
                        <a class="btn btn-sm bg-secondary text-white col-md-4 border border-dark rounded-3" href="/concedii" role="button">
                            <i class="far fa-trash-alt text-white me-1"></i>Resetează căutarea
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-lg-2 text-end">
                <a class="btn btn-sm bg-success text-white border border-dark rounded-3 col-md-8" href="/concedii/adauga" role="button">
                    <i class="fas fa-plus-square text-white me-1"></i>Adaugă concediu
                </a>
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
                            <th class="text-center px-3">Data început</th>
                            <th class="text-center px-3">Data sfârșit</th>
                            <th class="text-center px-3">Observații</th>
                            <th class="text-end">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($concedii as $concediu)
                            <tr>
                                <td align="">
                                    {{ ($concedii ->currentpage()-1) * $concedii ->perpage() + $loop->index + 1 }}
                                </td>
                                <td>
                                    {{ $concediu->user->name ?? '' }}
                                </td>
                                <td class="text-center">
                                    {{ $concediu->inceput ? Carbon::parse($concediu->inceput)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                                <td class="text-center">
                                    {{ $concediu->sfarsit ? Carbon::parse($concediu->sfarsit)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ $concediu->path() }}/modifica" class="flex me-1">
                                            <span class="badge bg-primary">Modifică</span>
                                        </a>
                                        <div style="flex" class="">
                                            <a
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#stergeConcediu{{ $concediu->id }}"
                                                title="Șterge Concediu"
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
                        {{$concedii->appends(Request::except('page'))->links()}}
                    </ul>
                </nav>
        </div>
    </div>

    {{-- Modalele pentru stergere pontaje --}}
    @foreach ($concedii as $concediu)
        <div class="modal fade text-dark" id="stergeConcediu{{ $concediu->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Concediu mecanic <b>{{ $concediu->user->name ?? '' }}</b></h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="text-align:left;">
                    Ești sigur ca vrei să ștergi Concediul?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>

                    <form method="POST" action="{{ $concediu->path() }}">
                        @method('DELETE')
                        @csrf
                        <button
                            type="submit"
                            class="btn btn-danger text-white"
                            >
                            Șterge Concediul
                        </button>
                    </form>

                </div>
                </div>
            </div>
        </div>
    @endforeach


@endsection
