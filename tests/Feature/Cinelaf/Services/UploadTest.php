<?php

namespace Tests\Feature\Cinelaf\Services;

use App\User;
use Cinelaf\Models\Film;
use Cinelaf\Services\Upload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadTest extends TestCase
{

    use WithFaker;


    protected function setUp(): void
    {
        parent::setUp(); //
    }


    /**
     * Method locandina() richiede Illuminate\Http\Request: testalo direttamente nei controller
     */
    /*
    public function testUploadLocandina(){}
    */


    public function testDeleteLocandina()
    {

        $filename = 'prova_file';
        Storage::put('public/locandine/'. $filename,'null');
        $film = factory(Film::class)->create(['locandina' => $filename ]);

        $this->assertFileExists(storage_path('app/public/locandine/'.$filename));

        $uploadService = new Upload();
        $uploadService->removeLocandina($film);

        $this->assertFileNotExists(storage_path('app/public/locandine/'.$filename));

    }

}
