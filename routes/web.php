<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProgramareController;
use App\Http\Controllers\ProgramareConfirmareController;
use App\Http\Controllers\MesajTrimisSmsController;
use App\Http\Controllers\CronJobTrimitereController;
use App\Http\Controllers\ZiNelucratoareController;
use App\Http\Controllers\MecanicProgramareController;
use App\Http\Controllers\MecanicBonusController;
use App\Http\Controllers\ManoperaController;
use App\Http\Controllers\PontajController;
use App\Http\Controllers\NecesarController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['register' => false, 'password.request' => false, 'reset' => false]);

Route::redirect('/', '/acasa');

Route::get('status-programare/{programare:cheie_unica}', [ProgramareConfirmareController::class, 'statusProgramare']);

// Trimitere Cron joburi din Cpanel
Route::any('/cron-jobs/trimitere-automata-sms-cerere-confirmare-programare/{key}', [CronJobTrimitereController::class, 'trimitereAutomataSmsCerereConfirmareProgramare']);
Route::any('/cron-jobs/trimitere-sms-revizie-ulei-filtre/{key}', [CronJobTrimitereController::class, 'trimitereSmsRevizieUleiFiltre']);

Route::group(['middleware' => 'auth'], function () {
    Route::view('/acasa', 'acasa');

    Route::resource('pontaje', PontajController::class)->parameters(['pontaje' => 'pontaj']);

    Route::resource('necesare', NecesarController::class)->parameters(['necesare' => 'necesar']);
});

Route::group(['middleware' => 'role:admin'], function () {
    Route::get('programari/afisare-calendar', [ProgramareController::class, 'index'])->name('programari.afisareCalendar');

    Route::get('/programari/{programare}/fisa-pdf', [ProgramareController::class, 'exportFisaPdf']);
    Route::resource('/programari', ProgramareController::class,  ['parameters' => ['programari' => 'programare']]);

    Route::resource('mesaje-trimise-sms', MesajTrimisSmsController::class,  ['parameters' => ['mesaje-trimise-sms' => 'mesaj_trimis_sms']]);

    Route::resource('zile-nelucratoare', ZiNelucratoareController::class)->parameters(['zile-nelucratoare' => 'zi_nelucratoare']);

    Route::get('programare-cerere-confirmare-sms/{programare:cheie_unica}', [ProgramareConfirmareController::class, 'cerereConfirmareSms']);

    Route::get('/manopere/export', [ManoperaController::class, 'export']);
});


Route::group(['middleware' => 'role:mecanic'], function () {
    // Route::resource('/mecanici/programari-mecanici', MecanicProgramareController::class,  ['parameters' => ['programari_mecanici' => 'programare']]);
    Route::get('/mecanici/programari-mecanici', [MecanicProgramareController::class, 'index']);
    Route::get('/mecanici/programari-mecanici/modificare-programare/{programare}', [MecanicProgramareController::class, 'modificareProgramare']);
    Route::post('/mecanici/programari-mecanici/modificare-programare/{programare}', [MecanicProgramareController::class, 'postModificareProgramare']);
    Route::get('/mecanici/programari-mecanici/modificare-manopera/{manopera}', [MecanicProgramareController::class, 'modificareManopera']);
    Route::post('/mecanici/programari-mecanici/modificare-manopera/{manopera}', [MecanicProgramareController::class, 'postModificareManopera']);

    Route::get('/mecanici/baza-de-date-programari', [MecanicProgramareController::class, 'bazaDeDateIndex']);
    Route::get('/mecanici/baza-de-date-programari/{programare}', [MecanicProgramareController::class, 'bazaDeDateShow']);

    Route::get('/mecanici/bonusuri-mecanici', [MecanicBonusController::class, 'index']);

    Route::get('/mecanici/pontaje-mecanici/citireQr/{programare}', [PontajController::class, 'citireQr']);
    Route::post('/mecanici/pontaje-mecanici/incepe-termina-pontaj/{programare}', [PontajController::class, 'postIncepeTerminaPontaj']);
    Route::get('/mecanici/pontaje-mecanici/status', [PontajController::class, 'status']);
});

// Route::group(['middleware' => 'role:admin,mecanic'], function () {
//     Route::resource('/pontaje', PontajController::class,  ['parameters' => ['pontaje' => 'pontaj']]);
// });
