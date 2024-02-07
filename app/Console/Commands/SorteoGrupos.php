<?php

namespace App\Console\Commands;

use App\Models\Group;
use Illuminate\Console\Command;
use App\Models\NationalTeam;

class SorteoGrupos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sorteo-grupos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $teams = NationalTeam::inRandomOrder()->get();

        if(count($teams) != '32'){
            echo "Please make sure to upload the national teams!!!";
            exit;
        }
        Group::truncate();

        $groups = ['A' => [], 'B' => [], 'C' => [], 'D' => [], 'E' => [], 'F' => [], 'G' => [], 'H' => []];

        $hostTeam = $teams->where('federation', 'ANFITRION')->first();
        $groups['A'][] = $hostTeam;

        $nonHostTeams = $teams->where('federation', '!=', 'ANFITRION');


        // Distribuir los equipos no anfitriones aleatoriamente en los grupos
        foreach ($nonHostTeams as $team) {
            $group = $this->getAvailableGroup($groups);
            $groups[$group][] = $team;
        }


        // Mostrar resultados del sorteo
        foreach ($groups as $grupo => $equiposGrupo) {
            $ubication = 1;
            foreach ($equiposGrupo as $team) {
                Group::create([
                    "grupo"=>$grupo,
                    "national_team_id" => $team->id,
                    "ubicacion" => $ubication
                ]);
                $ubication++;
            }
        }
    }

    private function getAvailableGroup($groups)
    {
        // Obtener todos los grupos que tienen menos de 4 integrantes
        $availableGroups = array_filter($groups, function ($group) {
            return count($group) < 4;
        });

        // Si hay grupos disponibles, seleccionar uno aleatoriamente
        if (!empty($availableGroups)) {
            $groupKeys = array_keys($availableGroups);
            $randomGroupKey = array_rand($groupKeys);
            return $groupKeys[$randomGroupKey];
        }

        // Si todos los grupos están llenos, devolver null
        return null;
    }

}
