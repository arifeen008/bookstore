<?php
namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    public function run(): void
    {
        // loop user ทุกคนแล้วสร้างที่อยู่ให้
        User::all()->each(function ($user) {
            $addresses = Address::factory(rand(1, 3))->create([
                'user_id' => $user->id,
            ]);

            // สุ่มที่อยู่ 1 อันให้เป็นค่า default
            $addresses->random()->update(['is_default' => true]);
        });
    }
}
