<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'slug', 'author', 'description', 'price', 'stock', 'cover', 'category_id'];

    protected static function booted()
    {
        static::creating(function ($book) {
            if (! $book->slug) {
                $book->slug = Str::slug($book->title . '-' . Str::random(5));
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function images()
    {
        // สมมติว่า Model สำหรับรูปภาพเพิ่มเติมชื่อ BookImage
        return $this->hasMany(BookImage::class); 
    }
}
