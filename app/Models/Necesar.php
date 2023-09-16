<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Necesar extends Model
{
    use HasFactory;

    protected $table = 'necesare';
    protected $guarded = [];

    public function path()
    {
        return "/necesare/{$this->id}";
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
