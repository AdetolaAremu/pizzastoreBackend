<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function pizza()
    {
        return $this->hasMany(Pizza::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
