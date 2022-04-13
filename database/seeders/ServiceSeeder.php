<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::create([
            'name' => 'Levantamento de requisitos',
            'duration' => '30'
        ]);
        Service::create([
            'name' => 'Sessão de programação',
            'duration' => '60'
        ]);
        Service::create([
            'name' => 'Sessão de testes',
            'duration' => '45'
        ]);
    }
}
