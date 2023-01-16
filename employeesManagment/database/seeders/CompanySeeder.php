<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Nette\Utils\DateTime;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'name' => Str::random(10),
            'location' => Str::random(10),
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),
        ]);
    }
}
