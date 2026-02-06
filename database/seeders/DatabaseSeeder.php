<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Serter Serbest',
            'email' => 'serter.serbest@gmail.com',
            'password' => bcrypt('serter.serbest'),
        ]);

        $teams = [
            ['name' => 'Manchester City', 'power' => 90],
            ['name' => 'Arsenal', 'power' => 85],
            ['name' => 'Liverpool', 'power' => 88],
            ['name' => 'Aston Villa', 'power' => 78],
        ];

        foreach ($teams as $team) {
            \App\Models\Team::create($team);
        }
    }
}
