<?php

use App\Models\Merchant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Merchant::create([
            'id'         => Str::uuid()->toString(),
            'name'       => 'Zegel Virtual',
            'baseURL'    => 'https://app.zegelvirtual.com',
            'serviceURL' => 'https://courseapp.educatzilla.tech',
        ]);
    }
}
