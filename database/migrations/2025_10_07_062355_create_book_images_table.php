<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book_images', function (Blueprint $table) {
            $table->id();

            // คอลัมน์สำหรับเชื่อมโยงกับตาราง books (Foreign Key)
            $table->foreignId('book_id')
                ->constrained()        // บังคับ Foreign Key Constraint
                ->onDelete('cascade'); // เมื่อลบหนังสือ รูปภาพที่เกี่ยวข้องจะถูกลบตามไปด้วย

            // คอลัมน์สำหรับเก็บพาธของรูปภาพที่จัดเก็บใน Storage
            $table->string('path', 255);

            // คอลัมน์สำหรับจัดเรียงลำดับรูปภาพ (ไม่บังคับ แต่มีประโยชน์)
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_images');
    }
};
