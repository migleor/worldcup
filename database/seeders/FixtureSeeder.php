<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixtureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ["grupo"=>"A","local"=>"1", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-21 13:00:00","emparejamiento" =>""],
            ["grupo"=>"A","local"=>"2", "visitante"=>"3", "fase" =>"Grupos","fecha"=>"2024-11-21 16:00:00","emparejamiento" =>""],
            ["grupo"=>"A","local"=>"1", "visitante"=>"3", "fase" =>"Grupos","fecha"=>"2024-11-21 19:00:00","emparejamiento" =>""],
            ["grupo"=>"A","local"=>"2", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-21 22:00:00","emparejamiento" =>""],
            ["grupo"=>"A","local"=>"1", "visitante"=>"2", "fase" =>"Grupos","fecha"=>"2024-11-22 13:00:00","emparejamiento" =>""],
            ["grupo"=>"A","local"=>"3", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-22 16:00:00","emparejamiento" =>""],
            ["grupo"=>"B","local"=>"1", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-22 19:00:00","emparejamiento" =>""],
            ["grupo"=>"B","local"=>"2", "visitante"=>"3", "fase" =>"Grupos","fecha"=>"2024-11-22 22:00:00","emparejamiento" =>""],
            ["grupo"=>"B","local"=>"1", "visitante"=>"3", "fase" =>"Grupos","fecha"=>"2024-11-23 13:00:00","emparejamiento" =>""],
            ["grupo"=>"B","local"=>"2", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-23 16:00:00","emparejamiento" =>""],
            ["grupo"=>"B","local"=>"1", "visitante"=>"2", "fase" =>"Grupos","fecha"=>"2024-11-23 19:00:00","emparejamiento" =>""],
            ["grupo"=>"B","local"=>"3", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-23 22:00:00","emparejamiento" =>""],
            ["grupo"=>"C","local"=>"1", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-24 13:00:00","emparejamiento" =>""],
            ["grupo"=>"C","local"=>"2", "visitante"=>"3", "fase" =>"Grupos","fecha"=>"2024-11-24 16:00:00","emparejamiento" =>""],
            ["grupo"=>"C","local"=>"1", "visitante"=>"3", "fase" =>"Grupos","fecha"=>"2024-11-24 19:00:00","emparejamiento" =>""],
            ["grupo"=>"C","local"=>"2", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-24 22:00:00","emparejamiento" =>""],
            ["grupo"=>"C","local"=>"1", "visitante"=>"2", "fase" =>"Grupos","fecha"=>"2024-11-25 13:00:00","emparejamiento" =>""],
            ["grupo"=>"C","local"=>"3", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-25 16:00:00","emparejamiento" =>""],
            ["grupo"=>"D","local"=>"1", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-25 19:00:00","emparejamiento" =>""],
            ["grupo"=>"D","local"=>"2", "visitante"=>"3", "fase" =>"Grupos","fecha"=>"2024-11-25 22:00:00","emparejamiento" =>""],
            ["grupo"=>"D","local"=>"1", "visitante"=>"3", "fase" =>"Grupos","fecha"=>"2024-11-26 13:00:00","emparejamiento" =>""],
            ["grupo"=>"D","local"=>"2", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-26 16:00:00","emparejamiento" =>""],
            ["grupo"=>"D","local"=>"1", "visitante"=>"2", "fase" =>"Grupos","fecha"=>"2024-11-26 19:00:00","emparejamiento" =>""],
            ["grupo"=>"D","local"=>"3", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-26 22:00:00","emparejamiento" =>""],
            ["grupo"=>"E","local"=>"1", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-27 13:00:00","emparejamiento" =>""],
            ["grupo"=>"E","local"=>"2", "visitante"=>"3", "fase" =>"Grupos","fecha"=>"2024-11-27 16:00:00","emparejamiento" =>""],
            ["grupo"=>"E","local"=>"1", "visitante"=>"3", "fase" =>"Grupos","fecha"=>"2024-11-27 19:00:00","emparejamiento" =>""],
            ["grupo"=>"E","local"=>"2", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-27 22:00:00","emparejamiento" =>""],
            ["grupo"=>"E","local"=>"1", "visitante"=>"2", "fase" =>"Grupos","fecha"=>"2024-11-28 13:00:00","emparejamiento" =>""],
            ["grupo"=>"E","local"=>"3", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-28 16:00:00","emparejamiento" =>""],
            ["grupo"=>"F","local"=>"1", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-28 19:00:00","emparejamiento" =>""],
            ["grupo"=>"F","local"=>"2", "visitante"=>"3", "fase" =>"Grupos","fecha"=>"2024-11-28 22:00:00","emparejamiento" =>""],
            ["grupo"=>"F","local"=>"1", "visitante"=>"3", "fase" =>"Grupos","fecha"=>"2024-11-29 13:00:00","emparejamiento" =>""],
            ["grupo"=>"F","local"=>"2", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-29 16:00:00","emparejamiento" =>""],
            ["grupo"=>"F","local"=>"1", "visitante"=>"2", "fase" =>"Grupos","fecha"=>"2024-11-29 19:00:00","emparejamiento" =>""],
            ["grupo"=>"F","local"=>"3", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-29 22:00:00","emparejamiento" =>""],
            ["grupo"=>"G","local"=>"1", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-30 13:00:00","emparejamiento" =>""],
            ["grupo"=>"G","local"=>"2", "visitante"=>"3", "fase" =>"Grupos","fecha"=>"2024-11-30 16:00:00","emparejamiento" =>""],
            ["grupo"=>"G","local"=>"1", "visitante"=>"3", "fase" =>"Grupos","fecha"=>"2024-11-30 19:00:00","emparejamiento" =>""],
            ["grupo"=>"G","local"=>"2", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-11-30 22:00:00","emparejamiento" =>""],
            ["grupo"=>"G","local"=>"1", "visitante"=>"2", "fase" =>"Grupos","fecha"=>"2024-12-01 18:00:00","emparejamiento" =>""],
            ["grupo"=>"G","local"=>"3", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-12-01 18:00:00","emparejamiento" =>""],
            ["grupo"=>"H","local"=>"1", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-12-01 22:00:00","emparejamiento" =>""],
            ["grupo"=>"H","local"=>"2", "visitante"=>"3", "fase" =>"Grupos","fecha"=>"2024-12-01 22:00:00","emparejamiento" =>""],
            ["grupo"=>"H","local"=>"1", "visitante"=>"3", "fase" =>"Grupos","fecha"=>"2024-12-01 18:00:00","emparejamiento" =>""],
            ["grupo"=>"H","local"=>"2", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-12-01 18:00:00","emparejamiento" =>""],
            ["grupo"=>"H","local"=>"1", "visitante"=>"2", "fase" =>"Grupos","fecha"=>"2024-12-01 22:00:00","emparejamiento" =>""],
            ["grupo"=>"H","local"=>"3", "visitante"=>"4", "fase" =>"Grupos","fecha"=>"2024-12-01 22:00:00","emparejamiento" =>""],
            ["grupo"=>"","local"=>"1A", "visitante"=>"2B", "fase" =>"Octavos","fecha"=>"2024-12-03 18:00:00","emparejamiento" =>"W49"],
            ["grupo"=>"","local"=>"1B", "visitante"=>"2A", "fase" =>"Octavos","fecha"=>"2024-12-03 22:00:00","emparejamiento" =>"W50"],
            ["grupo"=>"","local"=>"1C", "visitante"=>"2D", "fase" =>"Octavos","fecha"=>"2024-12-04 22:00:00","emparejamiento" =>"W51"],
            ["grupo"=>"","local"=>"1D", "visitante"=>"2C", "fase" =>"Octavos","fecha"=>"2024-12-04 18:00:00","emparejamiento" =>"W52"],
            ["grupo"=>"","local"=>"1E", "visitante"=>"2F", "fase" =>"Octavos","fecha"=>"2024-12-05 18:00:00","emparejamiento" =>"W53"],
            ["grupo"=>"","local"=>"1F", "visitante"=>"2E", "fase" =>"Octavos","fecha"=>"2024-12-05 22:00:00","emparejamiento" =>"W54"],
            ["grupo"=>"","local"=>"1G", "visitante"=>"2H", "fase" =>"Octavos","fecha"=>"2024-12-06 18:00:00","emparejamiento" =>"W55"],
            ["grupo"=>"","local"=>"1H", "visitante"=>"2G", "fase" =>"Octavos","fecha"=>"2024-12-06 22:00:00","emparejamiento" =>"W56"],
            ["grupo"=>"","local"=>"W51", "visitante"=>"W52", "fase" =>"Cuartos","fecha"=>"2024-12-09 22:00:00","emparejamiento" =>"W57"],
            ["grupo"=>"","local"=>"W55", "visitante"=>"W56", "fase" =>"Cuartos","fecha"=>"2024-12-09 18:00:00","emparejamiento" =>"W58"],
            ["grupo"=>"","local"=>"W49", "visitante"=>"W50", "fase" =>"Cuartos","fecha"=>"2024-12-10 22:00:00","emparejamiento" =>"W59"],
            ["grupo"=>"","local"=>"W53", "visitante"=>"W54", "fase" =>"Cuartos","fecha"=>"2024-12-10 18:00:00","emparejamiento" =>"W60"],
            ["grupo"=>"","local"=>"W59", "visitante"=>"W60", "fase" =>"Semis","fecha"=>"2024-12-13 22:00:00","emparejamiento" =>"SEMI1"],
            ["grupo"=>"","local"=>"W57", "visitante"=>"W58", "fase" =>"Semis","fecha"=>"2024-12-14 22:00:00","emparejamiento" =>"SEMI2"],
            ["grupo"=>"","local"=>"loss_semi1", "visitante"=>"loss_semi2", "fase" =>"Tercero","fecha"=>"2024-12-17 18:00:00","emparejamiento" =>"TERCERO"],
            ["grupo"=>"","local"=>"SEMI1", "visitante"=>"SEMI2", "fase" =>"Final","fecha"=>"2024-12-18 18:00:00","emparejamiento" =>"CAMPEON"]
        ];

        DB::table('fixtures')->insert($data);
    }
}
