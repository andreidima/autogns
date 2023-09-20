<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concediu extends Model
{
    use HasFactory;

    protected $table = 'concedii';
    protected $guarded = [];

    public function path()
    {
        return "/concedii/{$this->id}";
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
