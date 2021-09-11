<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PizzaImage extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function pizzaimages()
    {
        return $this->belongsTo(Pizza::class);
    }
}
