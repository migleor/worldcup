<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerRequest;
use App\Models\NationalTeam;
use App\Models\Player;
use Illuminate\Http\Request;

class PlayersController extends Controller
{

    protected $postions = ['Portero','Defensa','Centrocampista','Delantero'];

    public function index(){
        $players = Player::with('team')->get();
        return response()
        ->json([
            "players" =>  $players
        ]);
    }

    public function importPlayersFromCsv(PlayerRequest $request){

        $teams = NationalTeam::count();
        if($teams != '32'){
            return response()
            ->json([
                "message" => "Please make sure to upload the national teams",
            ], 422);
        }

        $errors = $this->processCsvFile($request->file);

        if(empty($errors)){
            $players = Player::with('team')->get();
            $res = [
                "message" => "Data uploaded succesfully",
                "players" => $players
            ];
            $code = 201;
        }else{
            $res = [
                "message" => "Error when upload data",
                "errors" => $errors
            ];
            $code = 422;
        }
        return response()
        ->json($res, $code);
    }

    private function processCsvFile($file){
        $errors = [];
        //cargamos el csv para leerlo
        $sw = false;
        $errors = [];
        $data = [];
        $fileContents = file($file->getPathname());

        foreach($fileContents as $key => $line){
            $fields = explode(";",$line);
            if(count($fields)!=5){
                $errors[] = [
                    "row" => $key,
                    "description" => "Number of columns other than 5"
                ];
                $sw = true;
            }else{

                $errPerLine = [];
                $errPerLine = $this->validateDataPlayers($fields);

                if(empty($errPerLine)){
                    //la linea cumple con la calidad de datos
                    $name = strtoupper($fields[0]);
                    $lang = strtoupper($fields[1]);
                    $age  = strtoupper($fields[2]);
                    $pos  = $fields[3];
                    $num  = strtoupper($fields[4]);
                    $num = str_replace(["\r","\n"],"",$num);
                    $pos = ucfirst($pos);
                    $national_team_id = $this->getNationalTeamId($lang);

                    $data[] = [
                        "name" => $name,
                        "national_team_id" => $national_team_id,
                        "edad" => $age,
                        "dorsal" => $num,
                        "posicion" => $pos,
                        "profile_image" => "https://picsum.photos/200/300.jpg"
                    ];
                }else{
                    $sw = true;
                    $errors[] = [
                        "row" => $key,
                        "description" => $errPerLine
                    ];
                }
            }
        }
        if(!$sw){
            $unicos = array_unique($data, SORT_REGULAR);
            //creamos los jugadores
            $this->clearDatabase();
            foreach ($unicos as $player) {
                Player::create($player);
            }
        }
        return $errors;
    }

    private function getNationalTeamId($lang){
        $team = NationalTeam::where('lang', $lang)->first();
        return $team->id;
    }

    private function validateDataPlayers($fields){
        $errors = [];
        $name = strtoupper($fields[0]);
        $lang = strtoupper($fields[1]);
        $age  = strtoupper($fields[2]);
        $age  = $age * 1;
        $pos  = ucfirst($fields[3]);
        $num  = strtoupper($fields[4]);
        $num = str_replace(["\r","\n"],"",$num);
        $num = $num * 1;
        if(empty($name)){
            $errors[] = [
                "field" => "name",
                "description" => "Name invalid"
            ];
        }

        if(!$this->validateLang($lang)){
            $errors[] = [
                "field" => "Nationality",
                "description" => "Nationality invalid"
            ];
        }
        if(!is_numeric($age) && $age>14){
            $errors[] = [
                "field" => "Age",
                "description" => "Age invalid"
            ];
        }
        if(!in_array($pos, $this->postions)){
            $errors[] = [
                "field" => "Position",
                "description" => "Position invalid"
            ];
        }

        if(!is_numeric($num) && ($num<=0 || $num>=23)){
            $errors[] = [
                "field" => "Number",
                "description" => "Number invalid"
            ];
        }
        return $errors;
    }

    private function validateLang($lang){
        $team = NationalTeam::where('lang', $lang)->count();
        return $team>0 ;
    }

    protected function clearDatabase(){
        $instancias = Player::all();

        // Eliminar en cascada cada instancia del modelo
        foreach ($instancias as $instancia) {
            $instancia->delete();
        }
    }

}
