<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding...');
        $this->command->newLine();

        // Seed in order to maintain relationships
        $this->call([
            UserSeeder::class,
            ServiceSeeder::class,
            UserLocationSeeder::class,
            OrderSeeder::class,
            CartSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->info('📝 Test Credentials:');
        $this->command->info('   Admin: admin@ghaseel.com / password123');
        $this->command->info('   User: ahmed@example.com / password123');
    }
}
