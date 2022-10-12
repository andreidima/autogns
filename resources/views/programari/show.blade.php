@extends ('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="shadow-lg" style="border-radius: 40px 40px 40px 40px;">
                <div class="border border-secondary p-2" style="border-radius: 40px 40px 0px 0px; background-color:#e66800">
                    <span class="badge text-light fs-5">
                        <i class="fa-solid fa-calendar-check me-1"></i>Programări / {{ $programare->client ?? '' }}
                    </span>
                </div>

                <div class="card-body py-2 border border-secondary"
                    style="border-radius: 0px 0px 40px 40px;"
                >

            @include ('errors')

                    <div class="table-responsive col-md-12 mx-auto">
                        <table class="table table-striped table-hover"
                        >
                            <tr>
                                <td class="pe-4">
                                    Client
                                </td>
                                <td>
                                    {{ $programare->client }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Telefon
                                </td>
                                <td>
                                    {{ $programare->telefon }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Email
                                </td>
                                <td>
                                    {{ $programare->email }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Mașina
                                </td>
                                <td>
                                    {{ $programare->masina }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Nr auto
                                </td>
                                <td>
                                    {{ $programare->nr_auto }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Dată și oră programare
                                </td>
                                <td>
                                    {{ $programare->data_ora_programare ? \Carbon\Carbon::parse($programare->data_ora_programare)->isoFormat('DD.MM.YYYY HH:mm') : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Dată și oră finalizare
                                </td>
                                <td>
                                    {{ $programare->data_ora_finalizare ? \Carbon\Carbon::parse($programare->data_ora_finalizare)->isoFormat('DD.MM.YYYY HH:mm') : '' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Lucrare
                                </td>
                                <td>
                                    {{ $programare->lucrare }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Tip lucrare
                                </td>
                                <td>
                                    @if ($programare->geometrie_turism === 1)
                                        <span class="me-1 px-1 culoare1 text-white">GM</span>
                                    @endif
                                    @if ($programare->geometrie_camion === 1)
                                        <span class="me-1 px-1 culoare1 text-white">GC</span>
                                    @endif
                                    @if ($programare->freon === 1)
                                        <span class="me-1 px-1 culoare1 text-white">F</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Piese client
                                </td>
                                <td>
                                    @switch($programare->piese)
                                        @case(0)
                                            Fără
                                            @break
                                        @case(1)
                                            Comandate
                                            @break
                                        @case(2)
                                            Venite
                                            @break
                                        @case(3)
                                            Client
                                            @break
                                        @default
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Stare mașină
                                </td>
                                <td>
                                    @switch($programare->stare_masina)
                                        @case(0)
                                            Nu este la service
                                            @break
                                        @case(1)
                                            În așteptare
                                            @break
                                        @case(2)
                                            În lucru
                                            @break
                                        @case(3)
                                            Finalizată
                                            @break
                                        @default
                                    @endswitch
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Observații
                                </td>
                                <td>
                                    {{ $programare->observatii }}
                                </td>
                            </tr>
                            <tr>
                                <td class="pe-4">
                                    Data introducere programare
                                </td>
                                <td>
                                    {{ $programare->created_at }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="form-row mb-2 px-2">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <a class="btn btn-primary text-white rounded-3" href="{{ Session::get('programare_return_url') }}">Înapoi</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
