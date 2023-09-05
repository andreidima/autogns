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
}
