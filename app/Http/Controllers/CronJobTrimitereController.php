<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Programare;
use Carbon\Carbon;

use App\Traits\TrimiteSmsTrait;

class CronJobTrimitereController extends Controller
{
    use TrimiteSmsTrait;

    public function trimitereAutomataSmsCerereConfirmareProgramare($key = null)
    {
        $config_key = \Config::get('variabile.cron_job_key');
        // dd($key, $config_key);

        if ($key === $config_key){

            $programari = Programare::
                // whereNotNull('data_ora_programare')
                whereDate('data_ora_programare', '=', Carbon::tomorrow()->todatestring())
                ->whereDate('created_at', '<', Carbon::today()->todatestring())
                ->where('stare_masina', 0) // masina nu este deja in service
                ->doesntHave('sms_confirmare') // sms-ul nu a fost deja trimis
                ->whereNull('confirmare') // confirmate deja de administratorii aplicatiei
                ->get();

            foreach ($programari as $programare){
                // echo $programare->id . '<br>';
                $mesaj = 'Accesati ' . url('/status-programare/' . $programare->cheie_unica) . ', pentru a confirma sau anula programarea din ' . Carbon::parse($programare->data_ora_programare)->isoFormat('DD.MM.YYYY') .
                            ', ora ' . Carbon::parse($programare->data_ora_programare)->isoFormat('HH:mm') .
                            '. AutoGNS +40723114595!';
                // Referitor la diacritice, puteti face conversia unui string cu diacritice intr-unul fara diacritice, in mod automatizat cu aceasta functie PHP:
                $mesaj = \Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', \Transliterator::FORWARD)->transliterate($mesaj);
                $this->trimiteSms('programari', 'confirmare', $programare->id, [$programare->telefon], $mesaj);
            }

        } else {
            echo 'Cheia pentru Cron Joburi nu este corectă!';
        }

    }

    public function trimitereSmsRevizieUleiFiltre($key = null)
    {
        $config_key = \Config::get('variabile.cron_job_key');
        // dd($key, $config_key);

        if ($key === $config_key){

            $programari = Programare::
                whereDate('data_ora_programare', '=', Carbon::now()->subYear()->todatestring())
                ->where('sms_revizie_ulei_filtre', 1)
                ->get();

            foreach ($programari as $programare){
                // echo $programare->id . '<br>';
                $mesaj = 'Buna ziua! Pe ' . Carbon::parse($programare->data_ora_programare)->isoFormat('DD.MM.YYYY') .
                        ', masina ' . $programare->nr_auto . ' va implini 1 an de la ultima revizie de ulei si filtre. Va reasteptam la service. Cu stima, AutoGNS  +40723114595!';
                // Referitor la diacritice, puteti face conversia unui string cu diacritice intr-unul fara diacritice, in mod automatizat cu aceasta functie PHP:
                $mesaj = \Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', \Transliterator::FORWARD)->transliterate($mesaj);
                $this->trimiteSms('programari', 'sms_revizie_ulei_filtre', $programare->id, [$programare->telefon], $mesaj);
            }

        } else {
            echo 'Cheia pentru Cron Joburi nu este corectă!';
        }

    }

    public function trimitereSmsCerereRecenzie($key = null)
    {
        $config_key = \Config::get('variabile.cron_job_key');

        if ($key !== $config_key){
            echo 'Cheia pentru Cron Joburi nu este corectă!';
            return;
        }

        $programari = Programare::
            whereDate('data_ora_finalizare', '<', Carbon::today()->todatestring()) // sms-ul sa se trimita cel putin la o zi dupa ce a fost gata lucrarea
            ->whereDate('data_ora_finalizare', '>=', Carbon::today()->subDays(2)->todatestring()) // se trimite sms la programarile din ultimele 7 zile
            ->where('sms_recenzie', 1) // la cei la care nu se doreste de trimis sms, au sms_recenzie = 0
            ->doesntHave('smsCerereRecenzie') // sms-ul nu a fost deja trimis
            // ->where('client', 'Andrei Dima test')
            ->get();

// ($programari->count());

        foreach ($programari as $programare){
            echo $programare->id . '. ' . $programare->client . '<br>';

            $mesaj = 'Cat de multumit ai fost de experienta cu AutoGNS? Te invitam sa ne oferi o recenzie la ' . url('/recenzie' . '/' . $programare->cheie_unica) . '. Multumim!';
                        // 'Multumim! AutoGNS +40723114595!';
            // Referitor la diacritice, puteti face conversia unui string cu diacritice intr-unul fara diacritice, in mod automatizat cu aceasta functie PHP:
            $mesaj = \Transliterator::createFromRules(':: Any-Latin; :: Latin-ASCII; :: NFD; :: [:Nonspacing Mark:] Remove; :: NFC;', \Transliterator::FORWARD)->transliterate($mesaj);
            $this->trimiteSms('programari', 'cerere recenzie', $programare->id, [$programare->telefon], $mesaj);
        }

    }
}
