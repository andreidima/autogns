<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificare extends Model
{
    use HasFactory;

    protected $table = 'notificari';
    protected $guarded = [];

    public function path()
    {
        return "/notificari/{$this->id}";
    }
}
