<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProgramareController;
use App\Http\Controllers\ProgramareConfirmareController;
use App\Http\Controllers\MesajTrimisSmsController;
use App\Http\Controllers\CronJobTrimitereController;
use App\Http\Controllers\ZiNelucratoareController;
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

    Route::get('programari/afisare-calendar', [ProgramareController::class, 'index'])->name('programari.afisareCalendar');
    Route::resource('/programari', ProgramareController::class,  ['parameters' => ['programari' => 'programare']]);

    Route::resource('mesaje-trimise-sms', MesajTrimisSmsController::class,  ['parameters' => ['mesaje-trimise-sms' => 'mesaj_trimis_sms']]);

    Route::resource('zile-nelucratoare', ZiNelucratoareController::class)->parameters(['zile-nelucratoare' => 'zi_nelucratoare']);

    Route::get('programare-cerere-confirmare-sms/{programare:cheie_unica}', [ProgramareConfirmareController::class, 'cerereConfirmareSms']);


    // Route::get('creare-key-unice', function(){
    //     $programari = App\Models\Programare::all();
    //     foreach ($programari as $programare){
    //         $programare->cheie_unica = uniqid();
    //         $programare->save();
    //     }
    // });
});

