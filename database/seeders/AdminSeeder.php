<?php

declare(strict_types=1);

namespace Database\Seeders;

use Domain\Barbers\Models\Barber;
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
            Barber::factory()->create([
                'name'     => $admin['name'],
                'password' => $admin['password'],
                'is_admin' => true,
            ]);
        }
    }
}
