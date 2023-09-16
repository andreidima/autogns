@csrf

<div class="row mb-0 p-3 d-flex border-radius: 0px 0px 40px 40px" id="programari">
    <div class="col-lg-12 mb-4">
        <textarea class="form-control bg-white {{ $errors->has('necesar') ? 'is-invalid' : '' }}"
            name="necesar" rows="4">{{ old('necesar', $necesar->necesar) }}</textarea>
    </div>

    <div class="col-lg-12 mb-2 d-flex justify-content-center">
        <button type="submit" class="btn btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
        <a class="btn btn-secondary rounded-3" href="{{ Session::get('necesarReturnUrl') }}">Renunță</a>
    </div>
</div>
