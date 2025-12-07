<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Address;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create admin user if it doesn't exist
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('admin2025'),
                'user_type' => 'admin',
                'gender' => 'male',
                'phone' => '0123456789',
            ]);
        }

            $doctors = [
                ['name' => 'Dr. Ahmed Bensouda', 'email' => 'ahmed.bens@example.com', 'specialty_id' => 1],
                ['name' => 'Dr. Salma El Idrissi', 'email' => 'salma.idrissi@example.com', 'specialty_id' => 2],
                ['name' => 'Dr. Youssef Amrani', 'email' => 'youssef.amrani@example.com', 'specialty_id' => 3],
                ['name' => 'Dr. Fatima Zahra Naji', 'email' => 'fatima.naji@example.com', 'specialty_id' => 4],
                ['name' => 'Dr. Hamza Tazi', 'email' => 'hamza.tazi@example.com', 'specialty_id' => 5],
                ['name' => 'Dr. Imane Khadiri', 'email' => 'imane.khadiri@example.com', 'specialty_id' => 6],
                ['name' => 'Dr. Reda El Alaoui', 'email' => 'reda.alaoui@example.com', 'specialty_id' => 7],
                ['name' => 'Dr. Mouna Bakkali', 'email' => 'mouna.bakkali@example.com', 'specialty_id' => 8],
                ['name' => 'Dr. Othmane Berrada', 'email' => 'othmane.berrada@example.com', 'specialty_id' => 9],
                ['name' => 'Dr. Zineb Lahlou', 'email' => 'zineb.lahlou@example.com', 'specialty_id' => 10],
            ];

            foreach ($doctors as $index => $doctorData) {
                $gender = $index % 2 === 0 ? 'male' : 'female';

                $address = Address::create([
                    'rue' => '123 Avenue Mohammed V',
                    'ville' => 'Casablanca',
                ]);

                $user = User::create([
                    'name' => $doctorData['name'],
                    'email' => $doctorData['email'],
                    'password' => Hash::make('password123'),
                    'user_type' => 'doctor',
                    'gender' => $gender,
                    'phone' => '06' . rand(10000000, 99999999),
                    'address_id' => $address->id,
                ]);

                Doctor::create([
                    'user_id' => $user->id,
                    'birth_date' => now()->subYears(rand(30, 60))->format('Y-m-d'),
                    'degree' => 'MD',
                    'specialty_id' => $doctorData['specialty_id'],
                    'status' => 'active',
                ]);
            }

        $patients = [
            ['name' => 'Ali Mansouri', 'email' => 'ali.mansouri@example.com'],
            ['name' => 'Sara El Fassi', 'email' => 'sara.elfassi@example.com'],
            ['name' => 'Omar Kabbaj', 'email' => 'omar.kabbaj@example.com'],
            ['name' => 'Nadia Rahmani', 'email' => 'nadia.rahmani@example.com'],
            ['name' => 'Karim Belkadi', 'email' => 'karim.belkadi@example.com'],
            ['name' => 'Lina Bouziane', 'email' => 'lina.bouziane@example.com'],
            ['name' => 'Tariq El Mehdi', 'email' => 'tariq.elmehdi@example.com'],
            ['name' => 'Hajar Maati', 'email' => 'hajar.maati@example.com'],
            ['name' => 'Samir Ouchen', 'email' => 'samir.ouchen@example.com'],
            ['name' => 'Meriem Toumi', 'email' => 'meriem.toumi@example.com'],
        ];

        foreach ($patients as $index => $patientData) {
            $gender = $index % 2 === 0 ? 'male' : 'female';

            $address = Address::create([
                'rue' => 'Rue ' . ($index + 1) . ' Ibn Sina',
                'ville' => 'Casablanca',
            ]);

            $user = User::create([
                'name' => $patientData['name'],
                'email' => $patientData['email'],
                'password' => Hash::make('password123'),
                'user_type' => 'patient',
                'gender' => $gender,
                'phone' => '06' . rand(10000000, 99999999),
                'address_id' => $address->id,
            ]);

            Patient::create([
                'cin' => 'AG' . str_pad($index + 1, 6, '0', STR_PAD_LEFT), // e.g., AG000001
                'birth_date' => now()->subYears(rand(18, 65))->subDays(rand(0, 365))->format('Y-m-d'),
                'user_id' => $user->id,
            ]);
        }
    }
}
