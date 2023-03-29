<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'phone' => '9650269769',
            'password' => bcrypt('123456'),
            'password_text' => '123456',
            'is_admin' => 1,
            'r_code' => 'ABCDEFGH',
        ]);
    }
}
