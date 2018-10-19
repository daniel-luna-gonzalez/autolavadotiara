<?php

use Illuminate\Database\Seeder;

class insert_admin extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'name' =>"Admin",
            'email' => "info@hazlorealidad.org",
            'password' => app('hash')->make("admin"),
            'remember_token'    => str_random(10),
        ]);
    }
}
