@csrf

@php
    use \Carbon\Carbon;
@endphp

<div class="row mb-0 p-3 d-flex border-radius: 0px 0px 40px 40px" id="datepicker">
    <div class="col-lg-12 mb-0 mx-auto">
        <div class="row">
            <div class="col-lg-6 mb-4">
                <label for="client" class="mb-0 ps-3">Client</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('client') ? 'is-invalid' : '' }}"
                    name="client"
                    placeholder=""
                    value="{{ old('client', $clientNeserios->client) }}">
            </div>
            <div class="col-lg-6 mb-4">
                <label for="nr_auto" class="mb-0 ps-3">Nr. auto</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('nr_auto') ? 'is-invalid' : '' }}"
                    name="nr_auto"
                    placeholder=""
                    value="{{ old('nr_auto', $clientNeserios->nr_auto) }}">
            </div>
            <div class="col-lg-6 mb-4">
                <label for="descriere" class="mb-0 ps-3">Descriere</label>
                <textarea class="form-control bg-white {{ $errors->has('descriere') ? 'is-invalid' : '' }}"
                    name="descriere" rows="3">{{ old('descriere', $clientNeserios->descriere) }}</textarea>
            </div>
            <div class="col-lg-6 mb-4">
                <label for="observatii" class="mb-0 ps-3">Observații</label>
                <textarea class="form-control bg-white {{ $errors->has('observatii') ? 'is-invalid' : '' }}"
                    name="observatii" rows="3">{{ old('observatii', $clientNeserios->observatii) }}</textarea>
            </div>
        </div>

        </div>
        <div class="row">
            <div class="col-lg-12 mb-2 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <a class="btn btn-secondary rounded-3" href="{{ Session::get('clientNeseriosReturnUrl') }}">Renunță</a>
            </div>
        </div>
    </div>
</div>
