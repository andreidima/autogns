@extends ('layouts.app')

@section('content')
<div class="container card" style="border-radius: 40px 40px 40px 40px;">
        <div class="row card-header text-center" style="border-radius: 40px 40px 0px 0px;">
            Programare: {{ $manopera->programare->masina ?? '' }}
            <br>
            Manopera: {{ $manopera->denumire }}
        </div>

        <div class="card-body px-0 py-3">

            @include ('errors')

            <form class="needs-validation" novalidate method="POST" action="{{ url()->current() }}">
                @csrf
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <label for="mecanic_timp" class="mb-0 ps-3">Timp necesar</label>
                        <input
                            type="text"
                            class="form-control bg-white rounded-3 {{ $errors->has('mecanic_timp') ? 'is-invalid' : '' }}"
                            name="mecanic_timp"
                            value="{{ old('mecanic_timp', $manopera->mecanic_timp) }}">
                    </div>
                    <div class="col-lg-4 mb-4">
                        <label for="mecanic_consumabile" class="mb-0 ps-3">Consumabile</label>
                        <textarea class="form-control bg-white {{ $errors->has('mecanic_consumabile') ? 'is-invalid' : '' }}"
                            name="mecanic_consumabile" rows="4">{{ old('mecanic_consumabile', $manopera->mecanic_consumabile) }}</textarea>
                    </div>
                    <div class="col-lg-4 mb-4">
                        <label for="mecanic_observatii" class="mb-0 ps-3">Observații</label>
                        <textarea class="form-control bg-white {{ $errors->has('mecanic_observatii') ? 'is-invalid' : '' }}"
                            name="mecanic_observatii" rows="4">{{ old('mecanic_observatii', $manopera->mecanic_observatii) }}</textarea>
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
