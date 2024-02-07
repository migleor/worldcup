<?php

namespace App\Http\Controllers;

use App\Models\Estadistica;
use App\Models\Fixture;
use App\Models\NationalTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstadisticaController extends Controller
{
    public function getFixture(){
        $fixtures = Fixture::get();
        $matches = [];
        foreach($fixtures as $fixture){
            $localTeam = NationalTeam::find($fixture->local_team_id);
            $visitorTeam = NationalTeam::find($fixture->visitante_team_id);
            if($fixture->goles_visitante>$fixture->goles_local){
                $result = "Win Visitor Team ".$visitorTeam->name ."(".$visitorTeam->lang.")";
            }elseif($fixture->goles_visitante==$fixture->goles_local){
                 $result = "Draw";
            }else{
                $result = "Win Local Team ".$localTeam->name ."(".$localTeam->lang.")";
            }
            $matches[] = [
                "id_match" => $fixture->id,
                "match_date" => $fixture->fecha,
                "result" => $result,
                "teams"=>[
                    "local" => [
                        "team" => $localTeam->name ."(".$localTeam->lang.")",
                        "statistics" => [
                            "goals" => $fixture->goles_local,
                            "yellow_cards" => $fixture->amarillas_local,
                            "red_cards" => $fixture->rojas_local
                        ]
                    ],
                    "visitor" => [
                        "team" => $visitorTeam->name ."(".$visitorTeam->lang.")",
                        "statistics" => [
                            "goals" =>$fixture->goles_visitante,
                            "yellow_cards" => $fixture->amarillas_visitante,
                            "red_cards" => $fixture->rojas_visitante
                        ]
                    ]
                ]
            ];
        }
        return response()
        ->json([
            "fixture" => $matches
        ]);
    }
    public function getFixtureById(Fixture $fixture){
        $localTeam = NationalTeam::find($fixture->local_team_id);
        $visitorTeam = NationalTeam::find($fixture->visitante_team_id);
        if($fixture->goles_visitante>$fixture->goles_local){
            $result = "Win Visitor Team ".$visitorTeam->name ."(".$visitorTeam->lang.")";
        }elseif($fixture->goles_visitante==$fixture->goles_local){
             $result = "Draw";
        }else{
            $result = "Win Local Team ".$localTeam->name ."(".$localTeam->lang.")";
        }
        $matches[] = [
            "id_match" => $fixture->id,
            "match_date" => $fixture->fecha,
            "result" => $result,
            "teams"=>[
                "local" => [
                    "team" => $localTeam->name ."(".$localTeam->lang.")",
                    "statistics" => [
                        "goals" => $fixture->goles_local,
                        "yellow_cards" => $fixture->amarillas_local,
                        "red_cards" => $fixture->rojas_local
                    ]
                ],
                "visitor" => [
                    "team" => $visitorTeam->name ."(".$visitorTeam->lang.")",
                    "statistics" => [
                        "goals" =>$fixture->goles_visitante,
                        "yellow_cards" => $fixture->amarillas_visitante,
                        "red_cards" => $fixture->rojas_visitante
                    ]
                ]
            ]
        ];
        return response()
        ->json([
            "match" => $matches
        ]);
    }

    public function getstatisticsTeam(NationalTeam $team){

        $rows = Estadistica::where('national_team_id', $team->id)
        ->get();

        $statistics = [];
        foreach ($rows as $row) {
            $statistics[$row->fase] = [
                "goals" => $row->goles,
                "yellow_cards" => $row->amarillas,
                "red_cards" => $row->rojas,
                "wins" => $row->ganados,
                "loses" => $row->perdidos,
                "draws" => $row->empatados,
                "group or key" => $row->grupo
            ];
        }

        $response = [
            "team" => [
                "name" => $team->name,
                "lang" => $team->lang,
                "federation" => $team->federation,
                "flag" => $team->flag_image_path,
            ],
            "statistics" => $statistics
        ];

        return response()
        ->json([
            "statistics" => $response
        ]);

    }

    public function getPositions(){
        $champion = Estadistica::where('fase', 'Final')
        ->where('ganados', 1)
        ->first();
        $dataChampion = $this->getDataTeam($champion->national_team_id);
        $second = Estadistica::where('fase', 'Final')
        ->where('perdidos', 1)
        ->first();

        $dataSecond = $this->getDataTeam($second->national_team_id);
        $third = Estadistica::where('fase', 'Tercero')
        ->where('ganados', 1)
        ->first();

        $dataThird = $this->getDataTeam($third->national_team_id);
        $fourth = Estadistica::where('fase', 'Tercero')
        ->where('ganados', 0)
        ->first();
        $dataFourth = $this->getDataTeam($fourth->national_team_id);

        $response = [
            "positions" => [
                "champion" => [
                    "id" => $dataChampion->id,
                    "name" => $dataChampion->name,
                    "lang" => $dataChampion->lang,
                    "flag" => $dataChampion->flag_image_path
                ],
                "second" => [
                    "id" => $dataSecond->id,
                    "name" => $dataSecond->name,
                    "lang" => $dataSecond->lang,
                    "flag" => $dataSecond->flag_image_path
                ],
                "third" => [
                    "id" => $dataThird->id,
                    "name" => $dataThird->name,
                    "lang" => $dataThird->lang,
                    "flag" => $dataThird->flag_image_path
                ],
                "fourth" => [
                    "id" => $dataFourth->id,
                    "name" => $dataFourth->name,
                    "lang" => $dataFourth->lang,
                    "flag" => $dataFourth->flag_image_path
                ]
            ]
        ];
        return response()
        ->json([
            "positions" => $response
        ]);


    }

    private function getDataTeam($team){
        return NationalTeam::find($team);

    }
}
