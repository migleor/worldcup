<?php

namespace App\Http\Controllers;

use App\Http\Requests\NationalTeamCreate;
use App\Models\NationalTeam;
use App\Models\Player;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class NationalTeamController extends Controller
{

    protected $federations = ['AFRICA','ASIA','CONCACAF','CONMEBOL','UEFA','ANFITRION'];
    protected $places = [
        'AFRICA' => 5,
        'ANFITRION' => 1,
        'ASIA' => 5,
        'CONCACAF' => 4,
        'CONMEBOL' => 5,
        'UEFA' => 12
    ];

    public function index(){

        $teams = NationalTeam::all();

        return response()
        ->json([
            "data" => $teams
        ]);
    }

    public function getTeamById(NationalTeam $team){
        return response()
        ->json([
            "data" => $team
        ]);
    }

    public function getPlayersByTeam(NationalTeam $team){

        $data = NationalTeam::with('players')->find($team->id);
        return response()
        ->json([
            "data" => $data
        ]);
    }

    public function importTeamsFromCsv(NationalTeamCreate $request){
        $errors = $this->procescCsvFile($request->file);
        if(empty($errors)){
            //no hubo errores en la data del csv ahora procesamos el .zip
            $errors = $this->processZipFile($request);
            if(empty($errors)){
                //consumimos los servicios para cargar grupos y simular fases y partidos
                //cargar grupos
                Artisan::call('app:sorteo-grupos');
                //setear fixture fase de grupos
                Artisan::call('app:set-matches-groups');
                //simular partidos y fisture fases finales
                Artisan::call('app:simular-fase-grupos');
                $teams = NationalTeam::all();
                $res = [
                    "message" => "Data uploaded succesfully",
                    "teams" => $teams
                ];
                $code = 201;
            }else{
                $this->clearDatabase();
                $res = [
                    "message" => "Error when upload zip file",
                    "errors" => $errors
                ];
            }
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

    private function procescCsvFile($file){
        //cargamos el csv para leerlo
        $sw = false;
        $errors = [];
        $data = [];
        $fileContents = file($file->getPathname());

        foreach($fileContents as $key => $line){
            $fields = explode(";",$line);
            if(count($fields)!=3){
                $errors[] = [
                    "row" => $key,
                    "description" => "Number of columns other than 3"
                ];
                $sw = true;
            }else{
                $name = strtoupper($fields[0]);
                $lang = strtoupper($fields[1]);
                $federation = strtoupper($fields[2]);
                $federation = str_replace(["\r","\n"],"",$federation);
                //validaciones de calidad de datos
                if(strlen($lang)!='3'){
                    $sw = true;
                    $errors[] = [
                        "row" => $key,
                        "description" => "Lang not valid"
                    ];
                }
                if(!in_array($federation, $this->federations)){
                    $sw = true;
                    $errors[] = [
                        "row" => $key,
                        "description" => "Federation not valid"
                    ];
                }
                $data[] = [
                    "name" => $name,
                    "lang" => $lang,
                    "federation" => $federation
                ];

            }
        }
        if(!$sw){
            $unicos = array_unique($data, SORT_REGULAR);
            $errors = $this->validatePlaces($unicos);
        }
        return $errors;
    }

    private function processZipFile($request){
        $this->validateDirectory();
        $errors = [];
        $count = 0;
        if ($request->hasFile('flags')) {
            $zipFile = $request->file('flags');
            $zipPath = $zipFile->store('flags');
            $zip = new ZipArchive;
            if ($zip->open(storage_path('app/' . $zipPath)) === true) {
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $filename = $zip->getNameIndex($i);
                    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    if($extension != 'png'){
                        $errors [] = [
                            "filename" => $filename,
                            "error" => "Only supprt for png files"
                        ];
                    }else{
                        $lang = pathinfo($filename, PATHINFO_FILENAME);
                        $lang = strtoupper($lang);
                        if($this->validateLangTeam($lang)){
                            $imagePath = 'flags/teams/'.$filename;
                            if($zip->extractTo(storage_path('app/flags/teams/'), $filename)){
                                $this->setPathToTeam($lang,$imagePath);
                            }
                            $count++;
                        }else{
                            $errors [] = [
                                "filename" => $filename,
                                "error" => "Lang team not valid"
                            ];
                        }
                    }
                }
            }
        }
        return $errors;

    }

    private function validateDirectory(){
        // Verificar si el directorio de almacenamiento existe
        $directory = 'flags';
        if (Storage::exists($directory)) {
            // El directorio no existe, puedes crearlo aquí
            Storage::deleteDirectory($directory);
            Storage::makeDirectory($directory);
        }
    }

    private function validateLangTeam($lang){

        $langs = NationalTeam::where('lang', $lang)
        ->count();
        return $langs>0;
    }

    private function setPathToTeam($lang,$imagePath){
        $team = NationalTeam::where('lang', $lang)->first();
        $team->flag_image_path = url($imagePath);
        $team->save();
        return true;
    }


    private function validatePlaces($unicos){
        $sw = false;
        $errors = [];
        //validando cupos
        $cupos = array_count_values(array_column($unicos, 'federation'));
        // Comparar el conteo obtenido con los valores esperados
        foreach ($this->places as $federation => $place) {
            $conteo_obtenido = isset($cupos[$federation]) ? $cupos[$federation] : 0;
            if ($conteo_obtenido != $place) {
                $sw = true;
            }
        }
        if($sw){
            $errors = [
                "row" => "N/A",
                "description" => "Invalid distribution of places"
            ];
        }else{
            //se han pasado todas las validaciones podemos crear los registros
            $this->clearDatabase();
            foreach($unicos as $item){
                NationalTeam::create($item);
            }
        }
        return $errors;
    }


    protected function clearDatabase(){

        Player::truncate();
        NationalTeam::truncate();
    }

}

