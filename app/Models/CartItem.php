<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
   protected $fillable = ['user_id', 'session_id', 'product_id', 'quantity', 'color', 'size'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
