<?php

use Illuminate\Database\Seeder;

class InsertAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = \Illuminate\Support\Facades\DB::table("users")->where("email", env('USER_ADMIN'))->first();

        if(is_null($user))
            \Illuminate\Support\Facades\DB::table('users')->insert([
                'id' => 1,
                'name' =>"Admin",
                'email' => env('USER_ADMIN'),
                'password' => app('hash')->make("admin"),
                'remember_token'    => str_random(10),
            ]);
    }
}
