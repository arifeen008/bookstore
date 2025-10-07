<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        // รายการประเภทหนังสือจริง
        $categories = [
            'นิยาย',
            'วรรณกรรม',
            'สารคดี',
            'ประวัติศาสตร์',
            'จิตวิทยา',
            'พัฒนาตนเอง',
            'การเงิน',
            'ธุรกิจ',
            'เทคโนโลยี',
            'วิทยาศาสตร์',
            'ปรัชญา',
            'ศิลปะ',
            'การ์ตูน / มังงะ',
            'นิยายแปล',
            'การศึกษา',
            'เด็กและเยาวชน',
            'ท่องเที่ยว',
            'ทำอาหาร',
            'สุขภาพ',
            'ศาสนา',
        ];

        // ดึงชื่อแบบสุ่มจากรายการจริง
        $name = $this->faker->randomElement($categories);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
