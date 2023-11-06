@extends ('layouts.app')

@php
    use \Carbon\Carbon;
@endphp

@section('content')
<div class="container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-2">
                <span class="badge culoare1 fs-5">
                    <i class="fa-solid fa-star me-1"></i>
                    Recenzii
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
                    </div>
                    <div class="row custom-search-form justify-content-center">
                        <button class="btn btn-sm btn-primary text-white col-md-4 me-3 border border-dark rounded-3" type="submit">
                            <i class="fas fa-search text-white me-1"></i>Caută
                        </button>
                        <a class="btn btn-sm bg-secondary text-white col-md-4 border border-dark rounded-3" href="/recenzii" role="button">
                            <i class="far fa-trash-alt text-white me-1"></i>Resetează căutarea
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-lg-2 text-end">
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
                            <th class="text-center px-3">Data recenzie</th>
                            <th class="text-center px-3">Nota</th>
                            <th class="text-center px-3">Comentariu</th>
                            <th class="text-end px-3">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recenzii as $recenzie)
                            <tr>
                                <td align="">
                                    {{ ($recenzii ->currentpage()-1) * $recenzii ->perpage() + $loop->index + 1 }}
                                </td>
                                <td>
                                    {{ $recenzie->manopera->mecanic->name ?? '' }}
                                </td>
                                <td class="text-center">
                                    {{ $recenzie->created_at ? Carbon::parse($recenzie->created_at)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                                <td class="text-center">
                                    {{ $recenzie->nota }}
                                </td>
                                <td class="text-center">
                                    {{ $recenzie->comentariu }}
                                </td>
                                <td class="">
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ $recenzie->path() }}" class="">
                                            <span class="badge bg-success">
                                                Vizualizează
                                            </span></a>
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
                        {{$recenzii->appends(Request::except('page'))->links()}}
                    </ul>
                </nav>
        </div>
    </div>

@endsection
