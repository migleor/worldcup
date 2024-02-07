<?php

namespace App\Console\Commands;

use App\Models\Fixture;
use App\Models\Estadistica;
use App\Models\NationalTeam;
use Illuminate\Console\Command;

use Illuminate\Support\Testing\Fakes\Fake;

class SimularFaseGrupos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:simular-fase-grupos';

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

        $this->simularFase('Grupos');
        $this->setearFixture('Grupos','Octavos');
        $this->simularFase('Octavos');
        $this->setearFixture('Octavos','Cuartos');
        $this->simularFase('Cuartos');
        $this->setearFixture('Cuartos','Semis');
        $this->simularFase('Semis');
        $this->setearFixture('Semis', 'Tercero');
        $this->simularFase('Tercero');
        $this->simularFase('Final');
        $this->actualizarEstadisticas();
    }

    private function actualizarEstadisticas(){
        //obtenemos participantes tercero
        $estadisticas = [];
        $tercero = Fixture::where('fase','Tercero')->first();
        $grupo = "W".$tercero->id;
        $this->getEstadisticasLocal($tercero->local_team_id, 'Tercero', $estadisticas, $grupo);
        $this->getEstadisticasVisitor($tercero->visitante_team_id, 'Tercero', $estadisticas, $grupo);
        $consolidado = $this->sumaEstadisticas($estadisticas);
        $this->guardaConsolidado($consolidado,'Tercero',$grupo);
        //obtenemos final
        $estadisticas = [];
        $final = Fixture::where('fase','Final')->first();
        $grupo = "W".$final->id;
        $this->getEstadisticasLocal($final->local_team_id, 'Final', $estadisticas, $grupo);
        $this->getEstadisticasVisitor($final->visitante_team_id, 'Final', $estadisticas, $grupo);
        $consolidado = $this->sumaEstadisticas($estadisticas);
        $this->guardaConsolidado($consolidado,'Final',$grupo);
        $estadisticas = [];
    }

    private function simularFase($fase){
        $matches = Fixture::where('fase',$fase)
        ->orderBy('id')
        ->get();
        $count = 0;
        foreach($matches as $match){
            $count++;
            $this->simularPartido($match, $fase);
        }
    }

    private function simularPartido($match, $fase){
        //goles
        $golesLocal = fake()->numberBetween(0,4);
        $golesVisitante = fake()->numberBetween(0,4);
        //amarillas
        $amarillas_local = fake()->numberBetween(0,3);
        $amarillas_visitante = fake()->numberBetween(0,3);
        //rojas
        $rojas_local = fake()->numberBetween(0,1);
        $rojas_visitante = fake()->numberBetween(0,1);
        if($fase!=='Grupos' && ($golesLocal==$golesVisitante)){
            //no pueden haber empates en fases de eliminación directa;
            //penales
            $ramndom = mt_rand(0,1);
            if($ramndom===0){
                $golesLocal = $golesLocal + 1;
            }else{
                $golesVisitante = $golesVisitante + 1;
            }
        }
        if($golesLocal==$golesVisitante){
            $puntosLocal = 1;
            $puntosVisitante = 1;
        }elseif($golesLocal>$golesVisitante){
            $puntosLocal = 3;
            $puntosVisitante = 0;
        }else{
            $puntosLocal = 0;
            $puntosVisitante = 3;
        }
        $match->goles_local = $golesLocal;
        $match->goles_visitante = $golesVisitante;
        $match->amarillas_local = $amarillas_local;
        $match->amarillas_visitante = $amarillas_visitante;
        $match->rojas_local = $rojas_local;
        $match->rojas_visitante = $rojas_visitante;
        $match->puntos_local = $puntosLocal;
        $match->puntos_visitante = $puntosVisitante;
        $match->save();
    }


    private function setearFixture($fasePrevia, $faseActual){
        //obtenemos los ganadores de la fase previa
        switch($fasePrevia){
            case 'Octavos':
                $listGroups = ["W49","W50","W51","W52","W53","W54","W55","W56"];
                $this->loadFixtureEliminatorias($fasePrevia, $faseActual, $listGroups);
            break;
            case 'Cuartos':
                $listGroups = ["W57","W58","W59","W60"];
                $this->loadFixtureEliminatorias($fasePrevia, $faseActual, $listGroups);
            break;
            case 'Semis':
                $listGroups = ["W61","W62"];
                $this->loadFixtureFinales($fasePrevia, $listGroups);
            break;
            default:
                $this->loadFixtureGrupos($fasePrevia, $faseActual);
            break;
        }
    }

    private function loadFixtureFinales($fasePrevia, $groups){
        foreach($groups as $grupo){
            $estadisticas = [];
            $fix = Fixture::where('fase', $fasePrevia)
            ->where('grupo', $grupo)
            ->first();
            $local = $fix->local_team_id;
            $visitor = $fix->visitante_team_id;
            $this->getEstadisticasLocal($local, $fasePrevia, $estadisticas, $grupo);
            $this->getEstadisticasVisitor($visitor, $fasePrevia, $estadisticas, $grupo);
            $consolidado = $this->sumaEstadisticas($estadisticas);
            $this->guardaConsolidado($consolidado,$fasePrevia,$grupo);
            $winLlave = $this->getGanadorLLave($fasePrevia, $grupo);
            $loseLlave = $this->getGanadorLLave($fasePrevia, $grupo, 'Perdedor');
            $llaves[$grupo] = [
                "ganador" => $winLlave,
                "perdedor" => $loseLlave
            ];
        }
        //seteamos los partidos finales
        $pos = "local";
        foreach($llaves as $llave => $item){
            $ganador = $item["ganador"];
            $perdedor = $item["perdedor"];
            $fix  = Fixture::where('fase', 'Tercero')->first();
            $fix2 = Fixture::where('fase', 'Final')->first();
            if($pos=="local"){
                //perdedor a tercero local
                $fix->local_team_id = $perdedor;
                $fix->grupo = "W".$fix->id;
                $fix->save();
                //ganador a final local
                $fix2->local_team_id = $ganador;
                $fix2->grupo = "W".$fix2->id;
                $fix2->save();
                $pos = "Visitante";
            }else{
                //perdedor a tercero visitante
                $fix->visitante_team_id = $perdedor;
                $fix->grupo = "W".$fix->id;
                $fix->save();
                //ganador a final visitante
                $fix2->visitante_team_id = $ganador;
                $fix2->grupo = "W".$fix2->id;
                $fix2->save();
                $pos = "local";
            }
        }
        return true;
    }

    private function loadFixtureEliminatorias($fasePrevia, $faseActual, $groups){
        foreach($groups as $grupo){
            $estadisticas = [];
            $fix = Fixture::where('fase', $fasePrevia)
            ->where('grupo', $grupo)
            ->first();
            $local = $fix->local_team_id;
            $visitor = $fix->visitante_team_id;
            $this->getEstadisticasLocal($local, $fasePrevia, $estadisticas, $grupo);
            $this->getEstadisticasVisitor($visitor, $fasePrevia, $estadisticas, $grupo);
            $consolidado = $this->sumaEstadisticas($estadisticas);
            $this->guardaConsolidado($consolidado,$fasePrevia,$grupo);
            //obtenemos el id del ganador de la llave y seteamos los datos del perdedor
            $winLlave = $this->getGanadorLLave($fasePrevia, $grupo);
            $llaves[$grupo] = $winLlave;
        }
        //ya tenemos los ganadores de cada llave ahora los emparejamos en la siguiente fase
        foreach($llaves as $llave => $id){
            $this->asignaLLave($faseActual,$llave,$id);
        }
        return true;
    }

    private function getGanadorLlave($fase, $llave, $retorno=null){
        $fix = Fixture::where('fase', $fase)
        ->where('grupo', $llave)
        ->first();
        $local = $fix->local_team_id;
        $visitor = $fix->visitante_team_id;
        $goles_local = $fix->goles_local;
        $goles_visitor = $fix->goles_visitante;
        if($goles_local>$goles_visitor){
            //ganador el local
            $valLocal = "Win - ".$llave;
            $valVisitor = "Lose - ".$llave;
            $ganador = $local;
            $perdedor = $visitor;
        }else{
            $valLocal = "Lose - ".$llave;
            $valVisitor = "Win - ".$llave;
            $ganador = $visitor;
            $perdedor = $local;
        }
        if($fase=='Octavos'){
            $campo = 'llave_octavos';
        }elseif($fase=='Cuartos'){
            $campo = 'llave_cuartos';
        }else{
            $campo = 'llave_semi';
        }
        $this->actualizaEquipo($local,$valLocal,$campo);
        $this->actualizaEquipo($visitor,$valVisitor,$campo);
        if($retorno=="Perdedor"){
            return $perdedor;
        }else{
            return $ganador;
        }

    }

    private function actualizaEquipo($id, $valor, $campo){
        $nationalTeam = NationalTeam::find($id);
        $nationalTeam->$campo = $valor;
        $nationalTeam->save();
    }

    private function loadFixtureGrupos($fasePrevia, $faseActual){
        $listGroups = ["A","B","C","D","E","F","G","H"];
        //obtenemos los ganadores de cada grupo
        foreach($listGroups as $grupo){
            //recorremos los datos de cada equipo
            $estadisticas = [];
            //obtenemos los id de los locales del grupo
            $locals = $this->getIds('local_team_id', $fasePrevia, $grupo);
            foreach($locals as $local){
                $this->getEstadisticasLocal($local->local_team_id, $fasePrevia, $estadisticas, $grupo);
            }
            //obtenemos los id de los visitantes del grupo
            $visitors = $this->getIds('visitante_team_id', $fasePrevia, $grupo);
            foreach($visitors as $visitor){
                $this->getEstadisticasVisitor($visitor->visitante_team_id, $fasePrevia, $estadisticas, $grupo);
            }
            $consolidado = $this->sumaEstadisticas($estadisticas);
            $this->guardaConsolidado($consolidado,$fasePrevia,$grupo);

            $clasifies  = $this->getClasificados($fasePrevia, $grupo);
            $pos = 1;
            foreach ($clasifies as $clasifie) {
                $valor = $pos.$grupo;
                $this->actualizaEquipo($clasifie->national_team_id, $valor, 'pos_grupos');
                $this->asignaLLave($faseActual,$valor,$clasifie->national_team_id);
                $pos++;
            }
        }
        return true;
    }

    private function asignaLlave($fase, $valor, $id){
        $fix = Fixture::where('fase', $fase)
        ->where('local', $valor)
        ->first();
        if(!is_null($fix)){
            $fix->local_team_id = $id;
            $fix->grupo = "W".$fix->id;
            $fix->save();
        }
        $fix2 = Fixture::where('fase', $fase)
        ->where('visitante', $valor)
        ->first();
        if(!is_null($fix2)){
            $fix2->visitante_team_id = $id;
            $fix2->save();
        }
        return true;
    }

    private function getIds($campo, $fase, $grupo=null){
        if(!empty($grupo)){
            $ids = Fixture::where('fase', $fase)
            ->where('grupo', $grupo)
            ->select($campo)
            ->groupBy($campo)
            ->orderBy($campo)
            ->get();
        }else{
            $ids = Fixture::where('fase', $fase)
            ->select($campo)
            ->groupBy($campo)
            ->orderBy($campo)
            ->get();
        }
        return $ids;

    }

    private function getEstadisticasLocal($teamId, $fase, &$estadisticas, $grupo=null){
        if(!empty($grupo)){
            $rows = Fixture::where('fase', $fase)
            ->where('grupo', $grupo)
            ->where('local_team_id', $teamId)
            ->select('goles_local', 'amarillas_local','rojas_local','puntos_local')
            ->get();
        }else{
            $rows = Fixture::where('fase', $fase)
            ->where('local_team_id', $teamId)
            ->select('goles_local', 'amarillas_local','rojas_local','puntos_local')
            ->get();
        }
        $goles = 0;
        $amarillas = 0;
        $rojas = 0;
        $puntos = 0;
        $perdidos = 0;
        $ganados = 0;
        $empatados = 0;
        foreach($rows as $row){
            $goles = $goles + $row->goles_local;
            $amarillas = $amarillas + $row->amarillas_local;
            $rojas = $rojas + $row->rojas_local;
            $puntos = $puntos + $row->puntos_local;
            if($row->puntos_local == 0){
                $perdidos++;
            }elseif($row->puntos_local == 1){
                $empatados++;
            }else{
                $ganados++;
            }
        }
        $estadisticas[$teamId]['local'] = [
            "goles"=> $goles,
            "amarillas"=> $amarillas,
            "rojas"=> $rojas,
            "puntos"=> $puntos,
            "perdidos"=> $perdidos,
            "ganados"=> $ganados,
            "empatados"=> $empatados
        ];
        return $estadisticas;
    }

    private function getEstadisticasVisitor($teamId, $fase, &$estadisticas, $grupo=null){
        if(!empty($grupo)){
            $rows = Fixture::where('fase', $fase)
            ->where('grupo', $grupo)
            ->where('visitante_team_id', $teamId)
            ->select('goles_visitante', 'amarillas_visitante','rojas_visitante','puntos_visitante')
            ->get();
        }else{
            $rows = Fixture::where('fase', $fase)
            ->where('visitante_team_id', $teamId)
            ->select('goles_visitante', 'amarillas_visitante','rojas_visitante','puntos_visitante')
            ->get();
        }
        $goles = 0;
        $amarillas = 0;
        $rojas = 0;
        $puntos = 0;
        $perdidos = 0;
        $ganados = 0;
        $empatados = 0;
        foreach($rows as $row){
            $goles = $goles + $row->goles_visitante;
            $amarillas = $amarillas + $row->amarillas_visitante;
            $rojas = $rojas + $row->rojas_visitante;
            $puntos = $puntos + $row->puntos_visitante;
            if($row->puntos_visitante == 0){
                $perdidos++;
            }elseif($row->puntos_visitante == 1){
                $empatados++;
            }else{
                $ganados++;
            }
        }
        $estadisticas[$teamId]["visitante"] = [
            "goles"=> $goles,
            "amarillas"=> $amarillas,
            "rojas"=> $rojas,
            "puntos"=> $puntos,
            "perdidos"=> $perdidos,
            "ganados"=> $ganados,
            "empatados"=> $empatados
        ];
        return $estadisticas;
    }

    private function sumaEstadisticas($estadisticas){
        $data = [];
        foreach ($estadisticas as $key => $value) {
            $goles = 0;
            $amarillas = 0;
            $rojas = 0;
            $puntos = 0;
            $perdidos = 0;
            $ganados = 0;
            $empatados = 0;
            foreach ($estadisticas[$key] as $item) {
                $goles = $goles + $item["goles"];
                $amarillas = $amarillas + $item["amarillas"];
                $rojas = $rojas + $item["rojas"];
                $puntos = $puntos + $item["puntos"];
                $perdidos = $perdidos + $item["perdidos"];
                $ganados = $ganados + $item["ganados"];
                $empatados =$empatados + $item["empatados"];
            }
            $data[$key] = [
                "goles"=> $goles,
                "amarillas"=> $amarillas,
                "rojas"=> $rojas,
                "puntos"=> $puntos,
                "perdidos"=> $perdidos,
                "ganados"=> $ganados,
                "empatados"=> $empatados
            ];
        }
        return $data;
    }

    private function guardaConsolidado($consolidado, $fase, $grupo=null){
        foreach ($consolidado as $key => $value) {
            $goles = 0;
            $amarillas = 0;
            $rojas = 0;
            $puntos = 0;
            $perdidos = 0;
            $ganados = 0;
            $empatados = 0;
            $national_team_id = $key;
            Estadistica::where('fase',  $fase)
            ->where('national_team_id', $national_team_id)
            ->delete();
            $goles = $consolidado[$key]["goles"];
            $amarillas = $consolidado[$key]["amarillas"];
            $rojas = $consolidado[$key]["rojas"];
            $puntos = $consolidado[$key]["puntos"];
            $ganados = $consolidado[$key]["ganados"];
            $perdidos = $consolidado[$key]["perdidos"];
            $empatados = $consolidado[$key]["empatados"];
            $data = [
                'national_team_id' => $national_team_id,
                'goles' => $goles,
                'amarillas' => $amarillas,
                'rojas' => $rojas,
                'puntos' => $puntos,
                'ganados' => $ganados,
                'perdidos' => $perdidos,
                'empatados' => $empatados,
                'grupo' => $grupo,
                'fase' => $fase
            ];
            Estadistica::create($data);
        }
        return true;
    }

    private function getClasificados($fase, $grupo){
        return Estadistica::where('fase', $fase)
        ->where('grupo', $grupo)
        ->orderBy('puntos', 'desc')
        ->orderBy('perdidos', 'asc')
        ->orderBy('goles', 'desc')
        ->orderBy('rojas', 'asc')
        ->orderBy('amarillas', 'asc')
        ->get();
    }
}
