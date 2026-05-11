<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminUser::create([
            'nama' => 'Super Admin',
            'email' => 'admin@ajltrans.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'akses_modul' => ['all'],
        ]);
    }
}
