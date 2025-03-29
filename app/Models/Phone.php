<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    protected $fillable = [
        'name',
        'price',
        'category_name',
        'image',
    ];
    use HasFactory;
}
