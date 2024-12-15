<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create($this->createAdmin());
    }

    private function createAdmin() {
        return [
            'type' => 'admin',
            'first_name' => 'mohamed',
            'last_name' => 'momen',
            'email' => 'momen@admin.com',
            'password' => bcrypt('password'),
            'email_verified_at' => Carbon::now(),
            'remember_token' => Str::random(10),
        ];
    }
}
