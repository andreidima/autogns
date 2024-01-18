@csrf

@php
    use \Carbon\Carbon;
@endphp

<div class="row mb-0 p-3 d-flex border-radius: 0px 0px 40px 40px" id="datepicker">
    <div class="col-lg-8 mb-0 mx-auto">
        <div class="row justify-content-center">
            <div class="col-lg-6 mb-4 text-center">
                <label class="me-1">Data<span class="text-danger">*</span></label>
                <vue-datepicker-next
                    data-veche="{{ old('data', ($notificare->data ?? '')) }}"
                    nume-camp-db="data"
                    tip="date"
                    value-type="YYYY-MM-DD"
                    format="DD.MM.YYYY"
                    :latime="{ width: '125px' }"
                ></vue-datepicker-next>
            </div>
            <div class="col-lg-12 mb-4">
                <label for="nume" class="mb-0 ps-3">Notificare<span class="text-danger">*</span></label>
                <textarea class="form-control bg-white {{ $errors->has('nume') ? 'is-invalid' : '' }}"
                    name="nume" rows="3">{{ old('nume', $notificare->nume) }}</textarea>
            </div>
        </div>

        </div>
        <div class="row mb-4">
            <div class="col-lg-12 mb-2 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <a class="btn btn-secondary rounded-3" href="{{ Session::get('notificareReturnUrl') }}">Renunță</a>
            </div>
        </div>
    </div>
</div>
