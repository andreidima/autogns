@extends ('layouts.app')

@php
    use \Carbon\Carbon;
@endphp

@section('content')
<div class="container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-3">
                <span class="badge culoare1 fs-5">
                    <i class="fa-solid fa-users-slash me-1"></i>
                    Clienți neserioși
                </span>
            </div>
            <div class="col-lg-6">
                <form class="needs-validation" novalidate method="GET" action="{{ url()->current() }}">
                    @csrf
                    <div class="row mb-1 custom-search-form justify-content-center">
                        <div class="col-lg-6">
                            <input type="text" class="form-control rounded-3" id="client" name="client" placeholder="Nr auto" value="{{ $client }}">
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control rounded-3" id="nr_auto" name="nr_auto" placeholder="Telefon" value="{{ $nr_auto }}">
                        </div>
                    </div>
                    <div class="row custom-search-form justify-content-center">
                        <button class="btn btn-sm btn-primary text-white col-md-4 me-3 border border-dark rounded-3" type="submit">
                            <i class="fas fa-search text-white me-1"></i>Caută
                        </button>
                        <a class="btn btn-sm bg-secondary text-white col-md-4 border border-dark rounded-3" href="{{ url()->current() }}" role="button">
                            <i class="far fa-trash-alt text-white me-1"></i>Resetează căutarea
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-lg-3 text-end">
                <a class="btn btn-sm bg-success text-white border border-dark rounded-3 col-md-8" href="{{ url()->current() }}/adauga" role="button">
                    <i class="fas fa-plus-square text-white me-1"></i>Adaugă client neserios
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
                            <th class="px-3">Client</th>
                            <th class="px-3">Nr. auto</th>
                            <th class="px-3">Descriere</th>
                            <th class="px-3">Observații</th>
                            <th class="text-end">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($clientiNeseriosi as $clientNeserios)
                            <tr>
                                <td align="">
                                    {{ ($clientiNeseriosi ->currentpage()-1) * $clientiNeseriosi ->perpage() + $loop->index + 1 }}
                                </td>
                                <td>
                                    {{ $clientNeserios->client }}
                                </td>
                                <td>
                                    {{ $clientNeserios->nr_auto }}
                                </td>
                                <td>
                                    {{ $clientNeserios->descriere }}
                                </td>
                                <td>
                                    {{ $clientNeserios->observatii }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ $clientNeserios->path() }}/modifica" class="flex me-1">
                                            <span class="badge bg-primary">Modifică</span>
                                        </a>
                                        <div style="flex" class="">
                                            <a
                                                href="#"
                                                data-bs-toggle="modal"
                                                data-bs-target="#stergeClientNeserios{{ $clientNeserios->id }}"
                                                title="Șterge Client Neserios"
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
                        {{ $clientiNeseriosi->appends(Request::except('page'))->links() }}
                    </ul>
                </nav>
        </div>
    </div>

    {{-- Modalele pentru stergere notificari --}}
    @foreach ($clientiNeseriosi as $clientNeserios)
        <div class="modal fade text-dark" id="stergeClientNeserios{{ $clientNeserios->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="exampleModalLabel">Client Neserios: {{ $clientNeserios->client }} / {{ $clientNeserios->nr_auto }}</h5>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="text-align:left;">
                    Ești sigur că vrei să ștergi Client Neserios?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Renunță</button>

                    <form method="POST" action="{{ $clientNeserios->path() }}">
                        @method('DELETE')
                        @csrf
                        <button
                            type="submit"
                            class="btn btn-danger text-white"
                            >
                            Șterge Client Neserios
                        </button>
                    </form>

                </div>
                </div>
            </div>
        </div>
    @endforeach


@endsection
