<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'image' => 'employee1.jpg',
                'name' => 'Andi Saputra',
                'phone' => '081234567890',
                'division_id' => 1,
                'position' => 'Manajer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'employee2.jpg',
                'name' => 'Siti Nurhasanah',
                'phone' => '082345678901',
                'division_id' => 2,
                'position' => 'Pengembang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'employee3.jpg',
                'name' => 'Budi Santoso',
                'phone' => '083456789012',
                'division_id' => 3,
                'position' => 'Junior',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'employee7.jpg',
                'name' => 'Agus an',
                'phone' => '083456789111',
                'division_id' => 3,
                'position' => 'Senior',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'employee4.jpg',
                'name' => 'Rina Wulandari',
                'phone' => '084567890123',
                'division_id' => 4,
                'position' => 'Pemasaran',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'employee5.jpg',
                'name' => 'Eka Pratama',
                'phone' => '085678901234',
                'division_id' => 5,
                'position' => 'Penjualan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'employee6.jpg',
                'name' => 'Dewi Lestari',
                'phone' => '086789012345',
                'division_id' => 6,
                'position' => 'Sumber Daya Manusia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'employee9.jpg',
                'name' => 'Agus B',
                'phone' => '0834511289121',
                'division_id' => 2,
                'position' => 'Konsulting',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => '',
                'name' => 'sam pitak',
                'phone' => '0834511289121',
                'division_id' => 6,
                'position' => 'senior',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => '',
                'name' => 'truno',
                'phone' => '0834511289121',
                'division_id' => 4,
                'position' => 'junior',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($employees as $employee) {
            DB::table('employees')->insert([
                'id' => Str::uuid(),
                'image' => $employee['image'],
                'name' => $employee['name'],
                'phone' => $employee['phone'],
                'division_id' => $employee['division_id'],
                'position' => $employee['position'],
                'created_at' => $employee['created_at'],
                'updated_at' => $employee['updated_at'],
            ]);
    }
}
}
