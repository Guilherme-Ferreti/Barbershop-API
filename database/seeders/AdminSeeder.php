<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = Storage::json('administrators.json');

        foreach ($admins as $admin) {
            User::factory()->create([
                'name'     => $admin['name'],
                'password' => $admin['password'],
            ]);
        }
    }
}
