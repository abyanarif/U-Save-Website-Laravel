<?php
// database/seeders/UniversitySeeder.php

use App\Models\University;
use Illuminate\Database\Seeder;

class UniversitySeeder extends Seeder
{
    public function run(): void
    {
        University::create([
            'name' => 'Universitas Airlangga',
            'address' => 'Jl. Airlangga No.4, Surabaya',
            'city_id' => null,
        ]);
    }
}
