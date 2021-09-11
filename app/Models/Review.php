<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function pizza()
    {
        return $this->belongsTo(Pizza::class, 'pizza_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
