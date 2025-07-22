<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientsTableSeeder extends Seeder
{
    public function run()
    {
        // Create residential clients
        Client::create([
            'first_name' => 'Jean',
            'last_name' => 'Dupont',
            'type' => 'residentiel',
            'email' => 'jean.dupont@email.com'
        ]);

        Client::create([
            'first_name' => 'Marie',
            'last_name' => 'Tremblay',
            'type' => 'residentiel',
            'email' => 'marie.tremblay@email.com'
        ]);

        // Create business clients
        Client::create([
            'first_name' => 'Robert',
            'last_name' => 'Martin',
            'type' => 'affaire',
            'email' => 'robert.martin@company.com'
        ]);

        Client::create([
            'first_name' => 'Sophie',
            'last_name' => 'Bergeron',
            'type' => 'affaire',
            'email' => 'sophie.bergeron@business.com'
        ]);
    }
}
