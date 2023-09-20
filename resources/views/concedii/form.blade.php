@csrf

@php
    use \Carbon\Carbon;
@endphp

<div class="row mb-0 p-3 d-flex border-radius: 0px 0px 40px 40px" id="datepicker">
    <div class="col-lg-8 mb-0 mx-auto">
        <div class="col-lg-5 mb-2">
            <label for="userId" class="ps-3">Mecanic<span class="text-danger">*</span></label>
            <select name="userId" class="form-select bg-white rounded-3 {{ $errors->has('userId') ? 'is-invalid' : '' }}">
                <option selected></option>
                @foreach ($useri as $user)
                    <option value="{{ $user->id }}" {{ $user->id == ($concediu->user->id ?? '') ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="row mb-4">
            <div class="col-lg-3 d-flex align-items-center">
                Data:
            </div>
            <div class="col-lg-9">
                <vue-datepicker-next
                    data-veche="{{ old('interval', $concediu->inceput . ',' . $concediu->sfarsit) }}"
                    nume-camp-db="interval"
                    tip="date"
                    range="range"
                    value-type="YYYY-MM-DD"
                    format="DD.MM.YYYY"
                    :latime="{ width: '210px' }"
                ></vue-datepicker-next>
            </div>
        </div>

        </div>
        <div class="row mb-4">
            <div class="col-lg-12 mb-2 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <a class="btn btn-secondary rounded-3" href="{{ Session::get('concediuReturnUrl') }}">Renunță</a>
            </div>
        </div>
    </div>
</div>
