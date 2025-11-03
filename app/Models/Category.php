<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // ADD THIS - Mass assignment allow karega
    protected $fillable = ['name', 'description'];

    // Relationship with resources
    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}