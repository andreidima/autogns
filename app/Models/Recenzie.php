<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recenzie extends Model
{
    use HasFactory;

    protected $table = 'recenzii';
    protected $guarded = [];

    public function path()
    {
        return "/recenzii/{$this->id}";
    }

    public function manopera()
    {
        return $this->belongsTo(Manopera::class, 'manopera_id');
    }
}
