@extends ('layouts.app')

@section('content')
<div class="container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header align-items-center" style="border-radius: 40px 40px 0px 0px;">
            <div class="col-lg-2">
                <span class="badge culoare1 fs-5">
                    <i class="fas fa-bars me-1"></i>Manopere export
                </span>
            </div>
            <div class="col-lg-7">
            </div>
        </div>

        <div class="card-body px-0 py-3">

            @include ('errors')

            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <form class="needs-validation" novalidate method="GET" action="{{ url()->current()  }}">
                        @csrf

                        <div class="py-2 rounded-3 d-flex align-items-center justify-content-center" style="background-color:aliceblue">
                            <p class="m-0 me-3 p-0">Manopere ziua:</p>
                            <div class="" id="programari">
                                <vue-datepicker-next
                                    data-veche="{{ $search_data }}"
                                    nume-camp-db="search_data"
                                    tip="date"
                                    value-type="YYYY-MM-DD"
                                    format="DD-MM-YYYY"
                                    :latime="{ width: '125px' }"
                                    style="margin-right: 20px;"
                                ></vue-datepicker-next>
                            </div>
                            <button type="submit" name="action" value="manopereOZi" class="btn btn-success" aria-current="true">
                                Export
                            </button>
                        </div>
                    </form>
                    {{-- <button type="submit" name="action" value="manopereTotIntervalul" class="list-group-item list-group-item-action" aria-current="true">
                        Manopere tot intervalul
                    </button> --}}
                </div>
            </div>

        </div>
    </div>

@endsection
