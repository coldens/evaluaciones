<?php

use App\Models\Merchant;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExampleStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant = Merchant::first();

        Student::create([
            'id'         => Str::uuid()->toString(),
            'name'       => 'Example Student',
            'email'      => 'example@lacafetalab.pe',
            'api_token'  => Str::random(80),
            'merchantId' => $merchant->id,
        ]);
    }
}
