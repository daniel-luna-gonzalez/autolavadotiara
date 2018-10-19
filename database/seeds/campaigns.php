<?php

use Illuminate\Database\Seeder;

class campaigns extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('campaign')->insert([
            'id' => 1,
            'name' =>"Todosxuno",
            'description' => "Campaña Todosxuno",
            'start_date' => "2017-08-10",
            'end_date' => "2017-09-17 23:59:00",
            "expected_amount" => 1000000
        ]);
    }
}
