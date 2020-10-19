<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MerchantSeeder::class);
        $this->call(ExampleStudentSeeder::class);
        $this->call(ExampleQuizSeeder::class);
    }
}
