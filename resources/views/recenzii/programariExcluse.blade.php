@extends ('layouts.app')

@php
    use \Carbon\Carbon;
@endphp

@section('content')
<div class="container card px-0" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-12">
                <span class="badge culoare1 fs-5">
                    <i class="fa-solid fa-star me-1"></i>
                    Programari excluse de la recenzie
                </span>
            </div>
        </div>

        <div class="card-body px-0 py-3">

            @include ('errors')

            <div class="table-responsive rounded">
                <table class="table table-striped table-hover rounded">
                    <thead class="text-white rounded culoare2">
                        <tr class="" style="padding:2rem">
                            <th class="">#</th>
                            <th class="px-3">Programare</th>
                            <th class="text-center px-3">Data</th>
                            <th class="px-3">Motiv</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($programari as $programare)
                            <tr>
                                <td>
                                    {{ ($programari ->currentpage()-1) * $programari ->perpage() + $loop->index + 1 }}
                                </td>
                                <td class="">
                                    <a href="{{ $programare->path() }}">
                                        {{ $programare->masina ?? $programare->client ?? 'Programare' }}
                                    </a>
                                </td>
                                <td class="text-center">
                                    {{ $programare->data_ora_programare ? Carbon::parse($programare->data_ora_programare)->isoFormat('DD.MM.YYYY') : '' }}
                                </td>
                                <td class="">
                                    {{ $programare->sms_recenzie_motiv_nu }}
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
        </div>
    </div>

@endsection
