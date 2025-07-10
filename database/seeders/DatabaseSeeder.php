<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->createUsers();
        $this->createCategories();
    }

    private function createUsers() {

        User::factory()->create([
            'name' => 'Greta Brognoli',
            'email' => 'greta.brognoli@gmail.com',
            'password' => Hash::make('greta'),
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Davide Bianchi',
            'email' => 'davide.bianchi@libero.it',
            'password' => Hash::make('greta')
        ]);

        User::factory()->create([
            'name' => 'Alessandra Rossi',
            'email' => 'alessandra.rossi@gmail.com',
            'password' => Hash::make('greta')
        ]);
    }

    private function createCategories(){
        Category::factory()->create([
            'name' => 'left',
            'image' => 'image/left.jpg'
        ]);

        Category::factory()->create([
            'name' => 'turning',
            'image' => 'image/right.jpg'
        ]);

        Category::factory()->create([
            'name' => 'stopping',
            'image' => 'image/stop.jpg'
        ]);

        Category::factory()->create([
            'name' => 'notevent'
        ]);
    }
}
