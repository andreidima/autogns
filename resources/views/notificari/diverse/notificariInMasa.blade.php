@extends ('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="shadow-lg" style="border-radius: 40px 40px 40px 40px;">
                <div class="border border-secondary p-2 culoare2" style="border-radius: 40px 40px 0px 0px;">
                    <span class="badge text-light fs-5">
                        <i class="fa-solid fa-message me-1"></i>
                        Notificări - modificări în masă
                    </span>
                </div>

                @include ('errors')

                <div class="card-body py-2 border border-secondary"
                    style="border-radius: 0px 0px 40px 40px;"
                >
                    <form  class="needs-validation" novalidate method="POST" action="/notificari/modificari-in-masa">
                        @csrf


                        <div class="row mb-0 p-3 d-flex border-radius: 0px 0px 40px 40px">
                            <div class="col-lg-8 mb-0 mx-auto">
                                <div class="row justify-content-center">
                                    <div class="col-lg-12 mb-4 ps-3">
                                        Funcționalitatea este la fel ca „Search and Replace” din Word.
                                        <br>
                                        Se caută și modifică toate notificările cu data mai mare de ziua curentă.
                                    </div>
                                    <div class="col-lg-12 mb-4">
                                        <label for="text_vechi" class="mb-0 ps-3">Text vechi<span class="text-danger">*</span></label>
                                        <input
                                            type="text"
                                            class="form-control bg-white rounded-3 {{ $errors->has('text_vechi') ? 'is-invalid' : '' }}"
                                            name="text_vechi">
                                        {{-- <small class="ps-3">* Se poate căuta întregul text al notificării, sau doar o parte din text. --}}
                                    </div>
                                    <div class="col-lg-12 mb-4">
                                        <label for="text_nou" class="mb-0 ps-3">Text nou</label>
                                        <input
                                            type="text"
                                            class="form-control bg-white rounded-3 {{ $errors->has('text_nou') ? 'is-invalid' : '' }}"
                                            name="text_nou">
                                        {{-- <small class="ps-3">* Dacă se dorește doar ștergere, acest câmp poate fi lasăt necompletat. --}}
                                    </div>
                                </div>

                                </div>
                                <div class="row mb-4">
                                    <div class="col-lg-12 mb-2 d-flex justify-content-center">
                                        <button type="submit" name="action" value="Cauta" class="btn btn-primary text-white me-3 rounded-3">Caută</button>
                                        <button type="submit" name="action" value="Modifica" class="btn btn-primary text-white me-3 rounded-3">Modifică</button>
                                    </div>
                                </div>
                            </div>
                        </div>




                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
