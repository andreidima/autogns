<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramareIstoric extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'programari_istoric';
    protected $guarded = [];

    public function path()
    {
        return "/programari-istoric/{$this->id}";
    }
}
