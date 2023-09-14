<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pontaj extends Model
{
    use HasFactory;

    protected $table = 'pontaje';
    protected $guarded = [];

    public function path()
    {
        return "/pontaje/{$this->id}";
    }

    public function programare()
    {
        return $this->belongsTo(Programare::class, 'programare_id');
    }

    public function mecanic()
    {
        return $this->belongsTo(User::class, 'mecanic_id');
    }
}
