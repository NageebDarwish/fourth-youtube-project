<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['category', 'title', 'description', 'About', 'price', 'discount', 'stock'];

    public function Category()
    {
        return $this->belongsTo(Category::class);
    }


    public function Images()
    {
        return $this->hasMany(ProductImage::class);
    }


    public function Cart()
    {
        return $this->hasMany(Cart::class);
    }
}
