<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookImage extends Model
{
    use HasFactory;

    // กำหนดชื่อตารางที่ใช้
    protected $table = 'book_images';

    // กำหนดฟิลด์ที่อนุญาตให้ Mass Assignment ได้
    protected $fillable = [
        'book_id',
        'path',
        'sort_order',
    ];

    /**
     * ความสัมพันธ์: รูปภาพเพิ่มเติม BelongTo หนังสือเล่มเดียว
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
