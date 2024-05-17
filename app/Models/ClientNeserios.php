<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientNeserios extends Model
{
    use HasFactory;

    protected $table = 'clienti_neseriosi';
    protected $guarded = [];

    public function path()
    {
        return "/clienti-neseriosi/{$this->id}";
    }
}
