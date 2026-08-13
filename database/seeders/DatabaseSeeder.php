<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Master Admin
        Admin::firstOrCreate(
            ['email' => 'admin@threadax.com'],
            [
                'name'     => 'ThreadAX Admin',
                'password' => 'password', // Updated to match user's known password from context
                'role'     => 'super_admin',
            ]
        );

        // Run all seeders sequentially
        $this->call([
            SettingSeeder::class,
            CategorySeeder::class,
            ProductSeeder::class,
            BannerSeeder::class,
            FaqSeeder::class,
            TestimonialSeeder::class,
            ReviewSeeder::class,
            PageSeeder::class,
            BlogSeeder::class,
        ]);

        $this->command->info('✅ Database perfectly seeded! Store is ready for production/demo.');
        $this->command->info('   Admin Login: admin@threadax.com / password');
    }
}
