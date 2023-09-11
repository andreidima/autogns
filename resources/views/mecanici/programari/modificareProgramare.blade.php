@extends ('layouts.app')

@section('content')
<div class="container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header text-center" style="border-radius: 40px 40px 0px 0px;">
            Mașina: {{ $programare->masina }}
            <br>
            Lucrare: {{ $programare->lucrare }}
        </div>

        <div class="card-body px-0 py-3">

            @include ('errors')

            <form class="needs-validation" novalidate method="POST" action="{{ url()->current() }}">
                @csrf
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <label for="km" class="mb-0 ps-3">Km mașină</label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3 {{ $errors->has('km') ? 'is-invalid' : '' }}"
                            name="km"
                            value="{{ old('km', $programare->km) }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12 my-2 d-flex justify-content-center">
                        <button type="submit" class="btn btn-lg btn-primary text-white me-3 rounded-3">Salvează</button>
                        <a class="btn btn-lg btn-secondary rounded-3" href="{{ Session::get('programariMecaniciReturnUrl') }}">Renunță</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
