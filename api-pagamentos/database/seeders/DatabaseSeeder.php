<?php

namespace Database\Seeders;

use App\Domain\User\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Domain\Product\Models\Product;
use App\Infraestructure\Gateway\Models\Gateway;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
    
        User::create([
            'name' => 'Matheus Admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('password'),
            'role' => 'ADMIN',
        ]);

        Gateway::create([
            'name' => 'Gateway 1',
            'priority' => 1,
            'is_active' => true,
        ]);

        Gateway::create([
            'name' => 'Gateway 2',
            'priority' => 2,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Teclado',
            'amount' => 5000, // R$ 50,00
        ]);
        
        Product::create([
            'name' => 'Mouse',
            'amount' => 2500, // R$ 25,00
        ]);
    }
}
