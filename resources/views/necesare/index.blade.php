@extends ('layouts.app')

@php
    use \Carbon\Carbon;
@endphp

@section('content')
<div class="container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-3">
                <span class="badge culoare1 fs-5">
                    <i class="fa-solid fa-screwdriver-wrench me-1"></i>
                    Necesare
                </span>
            </div>
            <div class="col-lg-6">
                @if (auth()->user()->role !== "mecanic")
                    <form class="needs-validation" novalidate method="GET" action="{{ route(Route::currentRouteName())  }}">
                        @csrf
                        <div class="row mb-1 custom-search-form justify-content-center">
                            <div class="col-lg-6 d-flex justify-content-center align-items-center mx-auto">
                                <label for="userId" class="me-1">Utilizator</label>
                                <select name="userId" class="form-select bg-white rounded-3 {{ $errors->has('userId') ? 'is-invalid' : '' }}">
                                    <option selected></option>
                                    @foreach ($useri as $user)
                                        <option value="{{ $user->id }}" {{ $user->id == $userId ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row custom-search-form justify-content-center">
                            <button class="btn btn-sm btn-primary text-white col-md-4 me-3 border border-dark rounded-3" type="submit">
                                <i class="fas fa-search text-white me-1"></i>Caută
                            </button>
                            <a class="btn btn-sm bg-secondary text-white col-md-4 border border-dark rounded-3" href="/necesare" role="button">
                                <i class="far fa-trash-alt text-white me-1"></i>Resetează căutarea
                            </a>
                        </div>
                    </form>
                @endif
            </div>
            <div class="col-lg-3 text-end">
                <a class="btn btn-sm bg-success text-white border border-dark rounded-3 col-md-8" href="/necesare/adauga" role="button">
                    <i class="fas fa-plus-square text-white me-1"></i>Adaugă necesar
                </a>
            </div>
        </div>

        <div class="card-body px-0 py-3">

            @include ('errors')

            <div class="table-responsive rounded">
                <table class="table table-striped table-hover rounded">
                    <thead class="text-white rounded culoare2">
                        <tr class="" style="padding:2rem">
                            @if (auth()->user()->role !== "mecanic")
                                <th class="">#</th>
                                <th class="">Utilizator</th>
                                <th class="text-center">Data adăugării</th>
                                <th class="">Necesar</th>
                                <th class="text-end">Acțiuni</th>
                            @else
                                <th class="">#</th>
                                <th class="">Necesar</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($necesare as $necesar)
                            <tr>
                                @if (auth()->user()->role !== "mecanic")
                                    <td align="">
                                        {{ ($necesare ->currentpage()-1) * $necesare ->perpage() + $loop->index + 1 }}
                                    </td>
                                    <td class="">
                                        {{ $necesar->user->name ?? '' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $necesar->created_at ? Carbon::parse($necesar->created_at)->isoFormat('DD.MM.YYYY') : '' }}
                                    </td>
                                    <td class="">
                                        {{ $necesar->necesar }}
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end">
                                            <a href="{{ $necesar->path() }}/modifica" class="flex me-1">
                                                <span class="badge bg-primary">Modifică</span>
                                            </a>
                                            <div style="flex" class="">
                                                <a
                                                    href="#"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#stergeNecesar{{ $necesar->id }}"
                                                    title="Șterge Necesar"
                                                    >
                                                    <span class="badge bg-danger">Șterge</span>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td align="">
                                        {{ ($necesare ->currentpage()-1) * $necesare ->perpage() + $loop->index + 1 }}
                                    </td>
                                    <td class="">
                                        {{ $necesar->necesar }}
                                        <b>({{ $necesar->user->name ?? '' }})</b>
                                        @if ($necesar->user_id === auth()->user()->id))
                                            <a href="{{ $necesar->path() }}/modifica" class="me-1">
                                                <span class="badge bg-primary">Modifică</span></a>
                                            <a
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#stergeNecesar{{ $necesar->id }}"
                                                title="Șterge Necesar"
                                                >
                                                <span class="badge bg-danger">Șterge</span>
                                            </a>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @empty
                            {{-- <div>Nu s-au gasit rezervări în baza de date. Încearcă alte date de căutare</div> --}}
                        @endforelse
                        </tbody>
                </table>
            </div>

                <nav>
                    <ul class="pagination justify-content-center">
                        {{$necesare->appends(Request::except('page'))->links()}}
                    </ul>
                </nav>
        </div>
    </div>

    {{-- Modalele pentru stergere necesar --}}
    @foreach ($necesare as $necesar)
        <div class="modal fade text-dark" id="stergeNecesar{{ $necesar->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Necesar</h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="text-align:left;">
                    {{ $necesar->necesar }}
                    <br><br>
                    <b>Ești sigur ca vrei să ștergi Necesarul?</b>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>

                    <form method="POST" action="{{ $necesar->path() }}">
                        @method('DELETE')
                        @csrf
                        <button
                            type="submit"
                            class="btn btn-danger text-white"
                            >
                            Șterge necesarul
                        </button>
                    </form>

                </div>
                </div>
            </div>
        </div>
    @endforeach


@endsection
