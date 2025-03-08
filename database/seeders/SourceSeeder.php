<?php

namespace Database\Seeders;

use App\Models\Source;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Source::updateOrCreate([
            'name' => 'Guardian',
        ], [
            'activated_at' => now(),
            'last_fetched_at' => null,
        ]);
        Source::updateOrCreate([
            'name' => 'NewsApi',
        ], [
            'activated_at' => now(),
            'last_fetched_at' => null,
        ]);
        Source::updateOrCreate([
            'name' => 'NewYorkTimes',
        ], [
            'activated_at' => now(),
            'last_fetched_at' => null,
        ]);
    }
}
