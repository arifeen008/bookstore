<?php
namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'address';
    protected $fillable = [
        'user_id',
        'fullname',
        'phone',
        'address_line',
        'district',
        'province',
        'zipcode',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
