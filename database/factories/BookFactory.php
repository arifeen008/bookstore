<?php
namespace Database\Factories;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(3);
        return [
            'title'       => $title,
            'slug'        => Str::slug($title),
            'author'      => $this->faker->name,
            'price'       => $this->faker->randomFloat(2, 100, 1000),
            'stock'       => $this->faker->numberBetween(5, 50),
            'description' => $this->faker->paragraph,
            'cover'       => 'https://picsum.photos/200?random=' . $this->faker->unique()->numberBetween(1, 1000),
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
        ];
    }
}
