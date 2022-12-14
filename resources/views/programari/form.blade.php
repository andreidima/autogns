@csrf

<script type="application/javascript">
    programariVechi = {!! json_encode($programari) !!}
    clientVechi={!! json_encode(old('client', $programare->client)) !!}
    telefonVechi={!! json_encode(old('telefon', $programare->telefon)) !!}
    emailVechi={!! json_encode(old('email', $programare->email)) !!}
    masinaVechi={!! json_encode(old('masina', $programare->masina)) !!}
    nr_autoVechi={!! json_encode(old('nr_auto', $programare->nr_auto)) !!}
</script>
<div class="row mb-0 p-3 d-flex border-radius: 0px 0px 40px 40px">
    <div class="col-lg-12 mb-0">
        <div class="row mb-0" id="programariForm">

            <div class="col-lg-4 mb-5 mx-auto">
                <label for="client" class="mb-0 ps-3">Client<span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('client') ? 'is-invalid' : '' }}"
                    name="client"
                    v-model="client"
                    placeholder=""
                    {{-- value="{{ old('client', $programare->client) }}" --}}
                    required>
            </div>
            <div class="col-lg-4 mb-5 mx-auto">
                <label for="telefon" class="mb-0 ps-3">Telefon</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('telefon') ? 'is-invalid' : '' }}"
                    name="telefon"
                    v-model="telefon"
                    placeholder=""
                    {{-- value="{{ old('telefon', $programare->telefon) }}" --}}
                    required>
            </div>
            <div class="col-lg-4 mb-5 mx-auto">
                <label for="email" class="mb-0 ps-3">Email</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    name="email"
                    v-model="email"
                    placeholder=""
                    {{-- value="{{ old('email', $programare->email) }}" --}}
                    required>
            </div>
            <div class="col-lg-4 mb-5 mx-auto">
                <label for="masina" class="mb-0 ps-3">Ma??ina</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('masina') ? 'is-invalid' : '' }}"
                    name="masina"
                    v-model="masina"
                    placeholder=""
                    {{-- value="{{ old('masina', $programare->masina) }}" --}}
                    required>
            </div>
            <div class="col-lg-2 mb-5 mx-auto">
                <label for="nr_auto" class="mb-0 ps-3">Nr. auto</label>
                <input
                    type="text"
                    class="form-control bg-white rounded-3 {{ $errors->has('nr_auto') ? 'is-invalid' : '' }}"
                    name="nr_auto"
                    v-model="nr_auto"
                    placeholder=""
                    {{-- value="{{ old('nr_auto', $programare->nr_auto) }}" --}}
                    required
                    autocomplete="off"
                    v-on:keyup="autocomplete()"
                    >
                <div v-cloak v-if="programari_lista_autocomplete.length" class="panel-footer overflow-auto" style="max-height: 200px;">
                    <div class="list-group">
                            <button type="button" class="list-group-item list-group-item list-group-item-action py-0"
                                v-for="programare in programari_lista_autocomplete"
                                v-on:click="
                                    client = programare.client;
                                    nr_auto = programare.nr_auto;
                                    telefon = programare.telefon;
                                    email = programare.email;
                                    masina = programare.masina;

                                    programari_lista_autocomplete = ''
                                "
                                >
                                    @{{ programare.nr_auto }}
                            </button>
                        </li>
                    </div>
                </div>
            </div>
            @php
                // $zile_nelucratoare = App\Models\ZiNelucratoare::select('data')->get()->pluck('data');
                // dd(App\Models\ZiNelucratoare::select('data')->get()->pluck('data'));
            @endphp
            <div class="col-lg-3 mb-5 mx-auto d-flex justify-content-start">
                <div>
                    <label for="data_ora_programare" class="mb-0 ps-xxl-2"><small>Dat?? ??i or?? programare</small></label>
                    <vue-datepicker-next
                        data-veche="{{ old('data_ora_programare', ($programare->data_ora_programare ?? '')) }}"
                        nume-camp-db="data_ora_programare"
                        :zile-nelucratoare="{{ App\Models\ZiNelucratoare::select('data')->get()->pluck('data') }}"
                        tip="datetime"
                        :hours="[8,9,10,11,12,13,14,15,16]"
                        :minute-step="10"
                        value-type="YYYY-MM-DD HH:mm"
                        format="DD-MM-YYYY HH:mm"
                        :latime="{ width: '170px' }"
                        style="margin-right: 20px;"
                    ></vue-datepicker-next>
                </div>
            </div>
            <div class="col-lg-3 mb-5 mx-auto d-flex justify-content-start">
                <div>
                    <label for="data_ora_finalizare" class="mb-0 ps-xxl-2"><small>Dat?? ??i or?? finalizare</small></label>
                    <vue-datepicker-next
                        data-veche="{{ old('data_ora_finalizare', ($programare->data_ora_finalizare ?? '')) }}"
                        nume-camp-db="data_ora_finalizare"
                        :zile-nelucratoare="{{ App\Models\ZiNelucratoare::select('data')->get()->pluck('data') }}"
                        tip="datetime"
                        :hours="[8,9,10,11,12,13,14,15,16]"
                        :minute-step="10"
                        value-type="YYYY-MM-DD HH:mm"
                        format="DD-MM-YYYY HH:mm"
                        :latime="{ width: '170px' }"
                        style="margin-right: 20px;"
                    ></vue-datepicker-next>
                </div>
            </div>
            <div class="col-lg-6 mb-5 mx-auto">
                <label for="lucrare" class="form-label mb-0 ps-3">Lucrare</label>
                <textarea class="form-control bg-white {{ $errors->has('lucrare') ? 'is-invalid' : '' }}"
                    name="lucrare" rows="4">{{ old('lucrare', $programare->lucrare) }}</textarea>
            </div>
            <div class="col-lg-4 mb-5 ps-s mx-auto d-flex align-items-center">
                <div>
                    <div class="form-check">
                        <input class="form-check-input" type="hidden" name="geometrie_turism" value="0" />
                        <input class="form-check-input" type="checkbox" value="1" name="geometrie_turism" id="geometrie_turism"
                            {{ old('geometrie_turism', $programare->geometrie_turism) == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="geometrie_turism">Geometrie Turism</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="hidden" name="geometrie_camion" value="0" />
                        <input class="form-check-input" type="checkbox" value="1" name="geometrie_camion" id="geometrie_camion"
                            {{ old('geometrie_camion', $programare->geometrie_camion) == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="geometrie_camion">Geometrie Camion</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="hidden" name="freon" value="0" />
                        <input class="form-check-input" type="checkbox" value="1" name="freon" id="freon"
                            {{ old('freon', $programare->freon) == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="freon">Freon</label>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 mb-5 ps-s mx-auto d-flex align-items-center" style="">
                <div>
                    Piese:
                    <br>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="0" name="piese" id="piese_fara"
                            {{ old('piese', $programare->piese) == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="piese_fara">F??r??</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="1" name="piese" id="piese_comandate"
                            {{ old('piese', $programare->piese) == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="piese_comandate">Comandate</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="2" name="piese" id="piese_venite"
                            {{ old('piese', $programare->piese) == '2' ? 'checked' : '' }}>
                        <label class="form-check-label" for="piese_venite">Venite</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="3" name="piese" id="piese_client"
                            {{ old('piese', $programare->piese) == '3' ? 'checked' : '' }}>
                        <label class="form-check-label" for="piese_client">Client</label>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 mb-5 ps-s mx-auto d-flex">
                <div>
                    Stare ma??in??:
                    <br>
                    <div class="form-check px-4">
                        <input class="form-check-input" type="radio" value="0" name="stare_masina" id="stare_masina_nu_este_la_service"
                            {{ old('stare_masina', $programare->stare_masina) == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="stare_masina_nu_este_la_service">Nu este la service</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="1" name="stare_masina" id="stare_masina_in_asteptare"
                            {{ old('stare_masina', $programare->stare_masina) == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="stare_masina_in_asteptare">??n a??teptare</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="2" name="stare_masina" id="stare_masina_in_lucru"
                            {{ old('stare_masina', $programare->stare_masina) == '2' ? 'checked' : '' }}>
                        <label class="form-check-label" for="stare_masina_in_lucru">??n lucru</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="3" name="stare_masina" id="stare_masina_finalizata"
                            {{ old('stare_masina', $programare->stare_masina) == '3' ? 'checked' : '' }}>
                        <label class="form-check-label" for="stare_masina_finalizata">Finalizat??</label>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 mb-5 ps-s mx-auto d-flex">
                <div>
                    Confirmare:
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="1" name="confirmare" id="confirmare_da"
                            {{ old('confirmare', $programare->confirmare) == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="confirmare_da"><i class="fa-solid fa-thumbs-up text-success fs-4"></i></i></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="0" name="confirmare" id="confirmare_nu"
                            {{ old('confirmare', $programare->confirmare) == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="confirmare_nu"><i class="fa-solid fa-thumbs-down text-danger fs-4"></i></i></label>
                    </div>
                    <div class="form-check px-4">
                        <input class="form-check-input" type="radio" value="" name="confirmare" id="confirmare_nesetat"
                            {{ old('confirmare', $programare->confirmare) == '' ? 'checked' : '' }}>
                        <label class="form-check-label" for="confirmare_nesetat"><i class="fa-solid fa-question text-dark fs-4"></i></label>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-5 mx-auto">
                <label for="observatii" class="form-label mb-0 ps-3">Observa??ii</label>
                <textarea class="form-control bg-white {{ $errors->has('observatii') ? 'is-invalid' : '' }}"
                    name="observatii" rows="4">{{ old('observatii', $programare->observatii) }}</textarea>
            </div>
            <div class="col-lg-3 mb-5 ps-s mx-auto d-flex align-items-center">
                <div>
                    <div class="form-check">
                        <input class="form-check-input" type="hidden" name="sms_revizie_ulei_filtre" value="0" />
                        <input class="form-check-input" type="checkbox" value="1" name="sms_revizie_ulei_filtre" id="sms_revizie_ulei_filtre"
                            {{ old('sms_revizie_ulei_filtre', $programare->sms_revizie_ulei_filtre) == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="sms_revizie_ulei_filtre">Trimitere sms, o singur?? dat??, dup?? 1 an, pentru revizie ulei ??i filtre</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-2 d-flex justify-content-center">
                <button type="submit" class="btn btn-primary text-white me-3 rounded-3">{{ $buttonText }}</button>
                <a class="btn btn-secondary rounded-3" href="{{ Session::get('programare_return_url') }}">Renun????</a>
            </div>
        </div>
    </div>
</div>
