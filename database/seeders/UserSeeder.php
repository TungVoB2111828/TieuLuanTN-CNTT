<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kiểm tra nếu chưa có tài khoản guest thì mới tạo
        if (!User::where('email', 'guest@example.com')->exists()) {
            User::create([
                'name'     => 'Guest',
                'email'    => 'guest@example.com',
                'phone'    => '0000000000',
                'address'  => 'Không xác định',
                'password' => Hash::make('guest1234'),
            ]);
        }
    }
}
