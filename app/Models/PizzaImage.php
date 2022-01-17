<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PizzaImage extends Model
{
    use HasFactory;

    // protected $guarded = ['id'];

    protected $fillable = [
        'pizza_id',
        'image',
        'main'
    ];

    public function images()
    {
        return $this->belongsTo(Pizza::class);
    }
}
