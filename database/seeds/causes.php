<?php

use Illuminate\Database\Seeder;

class causes extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('causes')->insert([
            'id' => 1,
            'name' =>"Mohar",
            'description' => "Apoyo para parejas"
        ]);
        
        DB::table('causes')->insert([
            'id' => 2,
            'name' =>"Bikir jolim",
            'description' => "Apoyo médico"
        ]);
        
        DB::table('causes')->insert([
            'id' => 3,
            'name' =>"Matan baseter",
            'description' => "Apoyos económicos y en especie para necesidades básicas"
        ]);
        
        DB::table('causes')->insert([
            'id' => 4,
            'name' =>"Becas",
            'description' => "Para alumnos del CHMS"
        ]);
        
        DB::table('causes')->insert([
            'id' => 5,
            'name' =>"Impulso educativo",
            'description' => "Financiamiento para carrera universitaria"
        ]);
        
    }

}
