<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Administrator
        User::create([
            'name' => 'Administrateur',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'administrateur'
        ]);

        // Create Residential Client Agent
        User::create([
            'name' => 'Utilisateur1',
            'username' => 'user1',
            'password' => Hash::make('password'),
            'role' => 'prepose_residentiel'
        ]);

        // Create Business Client Agent
        User::create([
            'name' => 'Utilisateur2',
            'username' => 'user2',
            'password' => Hash::make('password'),
            'role' => 'prepose_affaire'
        ]);
    }
}
