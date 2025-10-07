<?php
namespace Database\Seeders;

use App\Models\Address;
use App\Models\Book;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // สร้าง admin
        User::create([
            'name'              => 'Admin User',
            'email'             => 'admin@example.com',
            'password'          => Hash::make('password'),
            'phone'             => '0999999999',
            'birthdate'         => '1990-01-01',
            'email_verified_at' => now(),
            'role'              => 'admin',
            'remember_token'    => Str::random(10),
        ]);

        // User
        User::create([
            'name'              => 'Demo User',
            'email'             => 'user@example.com',
            'password'          => Hash::make('password'),
            'phone'             => '0999999999',
            'birthdate'         => '1990-01-01',
            'email_verified_at' => now(),
            'role'              => 'user',
            'remember_token'    => Str::random(10),
        ]);

        // สร้าง user ปกติ
        User::factory(10)->create();

        Address::factory(30)->create();
        // สร้างประเภทหนังสือ
        Category::factory(5)->create();

        // สร้างหนังสือ
        Book::factory(50)->create();

        // สร้างคูปอง
        Coupon::factory(10)->create();
    }
}
