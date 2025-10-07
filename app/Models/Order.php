<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'subtotal',
        'discount',
        'total',
        'phone',
        'status',
        'payment_method',
        'payment_meta',
        'first_name',
        'last_name',
        'address',
        'province',
        'district',
        'zip',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship กับ order_items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
