<?php

namespace Tests\Feature;

use App\Http\Controllers\NationalTeamController;
use App\Http\Requests\NationalTeamCreate;
use App\Models\NationalTeam;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NationalTeamControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_index_returns_all_teams()
    {
        NationalTeam::factory()->count(3)->create();
        $response = $this->get('api/v1/teams');
        $response->assertStatus(200);
    }

    public function test_find_team_by_id_pass()
    {
        $team = NationalTeam::factory()->create();
        $response = $this->get('api/v1/team/'.$team->id);
        $response->assertStatus(200);

    }

    public function test_import_teams_from_csv_and_zip()
    {
        $csvFilePath = storage_path('tests/assets/teams.csv');
        $csvFile = new UploadedFile($csvFilePath, 'teams.csv', 'text/csv', null, true);

        $zipFilePath = storage_path('tests/assets/flags.zip');
        $zipFile = new UploadedFile($zipFilePath, 'flags.zip', 'application/zip', null, true);

        $response = $this->postJson('api/v1/import-teams', [
            'file' => $csvFile,
            'flags' => $zipFile
        ]);

        $response->assertStatus(201);
        $this->assertEquals("Data uploaded succesfully", $response->json('message'));
        $this->assertDatabaseCount('national_teams', 32);
    }



}
