<?php

// File: app/Models/Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['category', 'slug'];

    public $timestamps = false;

    // Relationships
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    // Route key binding
    public function getRouteKeyName()
    {
        return 'slug';
    }
}