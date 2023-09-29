<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manopera extends Model
{
    use HasFactory;

    protected $table = 'manopere';
    protected $guarded = [];

    public function path()
    {
        return "/manopere/{$this->id}";
    }

    public function mecanic()
    {
        return $this->belongsTo(User::class, 'mecanic_id');
    }

    public function programare()
    {
        return $this->belongsTo(Programare::class, 'programare_id');
    }

    public function recenzii()
    {
        return $this->hasMany(Recenzie::class, 'manopera_id', 'id');
    }
}
