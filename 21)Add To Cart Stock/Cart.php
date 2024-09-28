<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['count', 'product_id', 'user_id'];

    public function Products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function Users()
    {
        return $this->belongsTo(User::class);
    }
}
