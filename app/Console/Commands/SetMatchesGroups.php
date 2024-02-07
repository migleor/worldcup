<?php

namespace App\Console\Commands;
use App\Models\Group;
use App\Models\Fixture;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetMatchesGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:set-matches-groups';

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
        $groups = Group::all();
        if($groups->count()==0){
            Artisan::call('app:sorteo-grupos');
        }

        $listGroups = [
            "A",
            "B",
            "C",
            "D",
            "E",
            "F",
            "G",
            "H",
        ];
        foreach($listGroups as $item){
            //recorremos las unicaciones del grupo
            for ($i=1; $i <=4  ; $i++) {
                $grupo = Group::where('grupo', $item)
                ->where('ubicacion', $i)
                ->orderBy('id')->first();
                $this->setPositionTeams('local', $grupo);
                $this->setPositionTeams('visitante', $grupo);
            }
        }

    }
    private function setPositionTeams($type, $grupo){
        $fixture = Fixture::where('grupo', $grupo->grupo)
        ->where($type,$grupo->ubicacion)->get();
        foreach ($fixture as $match) {
            if($type=='local'){
                $match->local_team_id = $grupo->national_team_id;
            }else{
                $match->visitante_team_id = $grupo->national_team_id;
            }
            $match->save();
        }
    }
}
