@csrf

@php
    use \Carbon\Carbon;
@endphp

<div class="row mb-0 p-3 d-flex border-radius: 0px 0px 40px 40px" id="datepicker">
    <div class="col-lg-8 mb-0 mx-auto">
        <input type="hidden" name="programareId" value="{{ $programare->id }}">
        <input type="hidden" name="mecanicId" value="{{ $mecanic->id }}">
        <input type="hidden" name="data" value="{{ $data }}">
        <div class="row mb-4">
            <div class="col-lg-3">
                Programare:
            </div>
            <div class="col-lg-9">
                {{ $programare->masina ?? '' }} {{ $programare->nr_auto }}
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-lg-3">
                Mecanic:
            </div>
            <div class="col-lg-9">
                {{ $mecanic->name }}
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-lg-3">
                Data:
            </div>
            <div class="col-lg-9">
                {{ Carbon::parse($data)->isoFormat('DD.MM.YYYY') }}
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-lg-3 d-flex align-items-center">
                Început:
            </div>
            <div class="col-lg-9">
                <vue-datepicker-next
                    data-veche="{{ old('inceput', ( $pontaj->inceput ? Carbon::parse($pontaj->inceput)->toTimeString() : '')) }}"
                    nume-camp-db="inceput"
                    {{-- :zile-nelucratoare="{{ App\Models\ZiNelucratoare::select('data')->whereDate('data', '<>', ($zi_nelucratoare->data ?? '2000-01-01'))->get()->pluck('data') }}" --}}
                    tip="time"
                    :hours="[8,9,10,11,12,13,14,15,16]"
                    :minute-step="10"
                    value-type="HH:mm"
                    format="HH:mm"
                    :latime="{ width: '80px' }"
                ></vue-datepicker-next>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-lg-3 d-flex align-items-center">
                Sfârșit:
            </div>
            <div class="col-lg-9">
                <vue-datepicker-next
                    data-veche="{{ old('sfarsit', ($pontaj->sfarsit ? Carbon::parse($pontaj->sfarsit)->toTimeString() : '')) }}"
                    nume-camp-db="sfarsit"
                    {{-- :zile-nelucratoare="{{ App\Models\ZiNelucratoare::select('data')->whereDate('data', '<>', ($zi_nelucratoare->data ?? '2000-01-01'))->get()->pluck('data') }}" --}}
                    tip="time"
                    :hours="[8,9,10,11,12,13,14,15,16]"
                    :minute-step="10"
                    value-type="HH:mm"
                    format="HH:mm"
                    :latime="{ width: '80px' }"
                ></vue-datepicker-next>
            </div>
        </div>

        </div>
        <div class="row mb-4">
            <div class="col-lg-12 mb-2 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <a class="btn btn-secondary rounded-3" href="{{ Session::get('pontajReturnUrl') }}">Renunță</a>
            </div>
        </div>
    </div>
</div>
