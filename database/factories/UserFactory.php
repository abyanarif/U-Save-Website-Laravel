<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\University; // FIX: Tambahkan ini untuk mengakses model University

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // FIX: Ambil semua ID universitas yang ada di database
        $universityIds = University::pluck('id')->all();

        return [
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'firebase_uid' => Str::random(28), // Membuat UID acak untuk Firebase
            // FIX: Pilih satu ID universitas secara acak dari yang tersedia
            'university_id' => fake()->randomElement($universityIds), 
            'phone' => fake()->phoneNumber(),
        ];
    }
}
