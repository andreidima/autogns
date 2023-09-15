<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

class Programare extends Model
{
    use HasFactory;

    protected $table = 'programari';
    protected $guarded = [];

    public function path()
    {
        return "/programari/{$this->id}";
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function smsuri()
    {
        return $this->hasMany(MesajTrimisSms::class, 'referinta_id', 'id')->where('categorie', 'programari');
    }

    public function sms_inregistrare()
    {
        return $this->hasMany(MesajTrimisSms::class, 'referinta_id', 'id')->where('categorie', 'programari')->where('subcategorie', 'inregistrare');
    }

    public function sms_confirmare()
    {
        return $this->hasMany(MesajTrimisSms::class, 'referinta_id', 'id')->where('categorie', 'programari')->where('subcategorie', 'confirmare');
    }

    public function sms_finalizare()
    {
        return $this->hasMany(MesajTrimisSms::class, 'referinta_id', 'id')->where('categorie', 'programari')->where('subcategorie', 'finalizare');
    }

    public function sms_inregistrare_trimis()
    {
        return $this->hasMany(MesajTrimisSms::class, 'referinta_id', 'id')->where('categorie', 'programari')->where('subcategorie', 'inregistrare')->where('trimis', 1);
    }

    public function sms_confirmare_trimis()
    {
        return $this->hasMany(MesajTrimisSms::class, 'referinta_id', 'id')->where('categorie', 'programari')->where('subcategorie', 'confirmare')->where('trimis', 1);
    }

    public function sms_finalizare_trimis()
    {
        return $this->hasMany(MesajTrimisSms::class, 'referinta_id', 'id')->where('categorie', 'programari')->where('subcategorie', 'finalizare')->where('trimis', 1);
    }

    public function programare_istoric()
    {
        return $this->hasMany(ProgramareIstoric::class, 'id', 'id');
    }

    public function manopere()
    {
        return $this->hasMany(Manopera::class, 'programare_id', 'id');
    }

    public function pontajAstazi()
    {
        return $this->hasMany(Pontaj::class, 'programare_id', 'id')->whereDate('inceput', Carbon::now());
    }
}
