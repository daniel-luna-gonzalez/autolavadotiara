<?php

use Illuminate\Database\Seeder;

class PackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $package = \Illuminate\Support\Facades\DB::table("package")->where('code','paquete1')->where('vehicleType','coche')->first();

        if(is_null($package)){
            DB::table('package')->insert([
                'id'                => 1,
                'name'              => 'Paquete 1',
                'code'              => 'paquete1',
                'price'             => 240,
                'description'       => '1 Lavada Semanal',
                'vehicleType'       => "coche"
            ]);
        }

        $package = \Illuminate\Support\Facades\DB::table("package")->where('code','paquete1')->where('vehicleType','camioneta')->first();

        if(is_null($package)){
            DB::table('package')->insert([
                'id'                => 2,
                'name'              => 'Paquete 1',
                'code'              => 'paquete1',
                'price'             => 300,
                'description'       => '1 Lavada Semanal',
                'vehicleType'       => "camioneta"
            ]);
        }

        $package = \Illuminate\Support\Facades\DB::table("package")->where('code','paquete2')->where('vehicleType','coche')->first();

        if(is_null($package)){
            DB::table('package')->insert([
                'id'                => 3,
                'name'              => 'Paquete 2',
                'code'              => 'paquete2',
                'price'             => 480,
                'description'       => '2 Lavadas Semanales',
                'vehicleType'       => "coche"
            ]);
        }

        $package = \Illuminate\Support\Facades\DB::table("package")->where('code','paquete2')->where('vehicleType','camioneta')->first();

        if(is_null($package)){
            DB::table('package')->insert([
                'id'                => 4,
                'name'              => 'Paquete 2',
                'code'              => 'paquete2',
                'price'             => 600,
                'description'       => '2 Lavadas Semanales',
                'vehicleType'       => "camioneta"
            ]);
        }

        $package = \Illuminate\Support\Facades\DB::table("package")->where('code','paquete3')->where('vehicleType','coche')->first();

        if(is_null($package)){
            DB::table('package')->insert([
                'id'                => 5,
                'name'              => 'Paquete 3',
                'code'              => 'paquete3',
                'price'             => 720,
                'description'       => '3 Lavadas Semanales',
                'vehicleType'       => "coche"
            ]);
        }

        $package = \Illuminate\Support\Facades\DB::table("package")->where('code','paquete3')->where('vehicleType','camioneta')->first();

        if(is_null($package)){
            DB::table('package')->insert([
                'id'                => 6,
                'name'              => 'Paquete 3',
                'code'              => 'paquete3',
                'price'             => 900,
                'description'       => '3 Lavadas Semanales',
                'vehicleType'       => "camioneta"
            ]);
        }

        $package = \Illuminate\Support\Facades\DB::table("package")->where('code','paquete4')->where('vehicleType','coche')->first();

        if(is_null($package)){
            DB::table('package')->insert([
                'id'                => 7,
                'name'              => 'Paquete 4',
                'code'              => 'paquete4',
                'price'             => 630,
                'description'       => '2 Lavadas Semanales|Baño de cera 1 vez a la quincena',
                'vehicleType'       => "coche"
            ]);
        }

        $package = \Illuminate\Support\Facades\DB::table("package")->where('code','paquete4')->where('vehicleType','camioneta')->first();

        if(is_null($package)){
            DB::table('package')->insert([
                'id'                => 8,
                'name'              => 'Paquete 4',
                'code'              => 'paquete4',
                'price'             => 800,
                'description'       => '2 Lavadas Semanales|Baño de cera 1 vez a la quincena',
                'vehicleType'       => "camioneta"
            ]);
        }

        $package = \Illuminate\Support\Facades\DB::table("package")->where('code','paquete5')->where('vehicleType','coche')->first();

        if(is_null($package)){
            DB::table('package')->insert([
                'id'                => 9,
                'name'              => 'Paquete 5',
                'code'              => 'paquete5',
                'price'             => 780,
                'description'       => '2 Lavadas Semanales|Baño de cera 1 vez a la semana',
                'vehicleType'       => "coche"
            ]);
        }

        $package = \Illuminate\Support\Facades\DB::table("package")->where('code','paquete5')->where('vehicleType','camioneta')->first();

        if(is_null($package)){
            DB::table('package')->insert([
                'id'                => 10,
                'name'              => 'Paquete 5',
                'code'              => 'paquete5',
                'price'             => 1000,
                'description'       => '2 Lavadas Semanales|Baño de cera 1 vez a la quincena',
                'vehicleType'       => "camioneta"
            ]);
        }

        $package = \Illuminate\Support\Facades\DB::table("package")->where('code','paquete6')->where('vehicleType','coche')->first();

        if(is_null($package)){
            DB::table('package')->insert([
                'id'                => 11,
                'name'              => 'Paquete 6',
                'code'              => 'paquete6',
                'price'             => 980,
                'description'       => '2 Lavadas Semanales|Ecerado 1 vez a la quincena',
                'vehicleType'       => "coche"
            ]);
        }

        $package = \Illuminate\Support\Facades\DB::table("package")->where('code','paquete6')->where('vehicleType','camioneta')->first();

        if(is_null($package)){
            DB::table('package')->insert([
                'id'                => 12,
                'name'              => 'Paquete 6',
                'code'              => 'paquete6',
                'price'             => 1200,
                'description'       => '2 Lavadas Semanales|Ecerado 1 vez a la quincena',
                'vehicleType'       => "camioneta"
            ]);
        }

        $package = \Illuminate\Support\Facades\DB::table("package")->where('code','paquete7')->where('vehicleType','coche')->first();

        if(is_null($package)){
            DB::table('package')->insert([
                'id'                => 13,
                'name'              => 'Paquete 7',
                'code'              => 'paquete7',
                'price'             => 1480,
                'description'       => '2 Lavadas Semanales|Encerado 1 vez a la semana',
                'vehicleType'       => "coche"
            ]);
        }

        $package = \Illuminate\Support\Facades\DB::table("package")->where('code','paquete7')->where('vehicleType','camioneta')->first();

        if(is_null($package)){
            DB::table('package')->insert([
                'id'                => 14,
                'name'              => 'Paquete 7',
                'code'              => 'paquete7',
                'price'             => 900,
                'description'       => '2 Lavadas Semanales|Encerado 1 vez a la semana',
                'vehicleType'       => "camioneta"
            ]);
        }
    }
}
