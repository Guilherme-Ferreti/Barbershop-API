<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Modules\Auth\Models\Barber;

class BarberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barbers = Storage::json('barbers.json');

        foreach ($barbers as $barber) {
            Barber::factory()->create([
                'name'     => $barber['name'],
                'password' => $barber['password'],
                'is_admin' => $barber['is_admin'],
            ]);
        }
    }
}
