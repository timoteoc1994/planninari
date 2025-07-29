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

            // Crear roles si no existen
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Administrador']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Usuario']);


        $user = User::factory()->create([
            'name' => 'Marco',
            'email' => 'marco@gmail.com',
            'password' => bcrypt('Marco123'),
        ]);
         $user->assignRole('Administrador');

        $user = User::factory()->create([
            'name' => 'Timoteo',
            'email' => 'edgartimoteo@gmail.com',
            'password' => bcrypt('amorjanet123'),
        ]);
         $user->assignRole('Administrador');

    }
}
