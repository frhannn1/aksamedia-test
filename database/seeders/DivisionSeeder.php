<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $divisions = [
            'Mobile Apps',
            'QA',
            'Full Stack',
            'Backend',
            'Frontend',
            'UI/UX Designer',
        ];

        $counter = 1;
        foreach ($divisions as $division) {
            DB::table('divisions')->insert([
                'id' => $counter++,
                'name' => $division,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
