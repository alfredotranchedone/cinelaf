<?php

namespace Tests\Feature\Cinelaf\Controllers\User;

use App\User;
use Cinelaf\Repositories\Registi;
use Cinelaf\Services\FilmSession;
use Cinelaf\Services\Upload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FilmControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /**
     * @return void
     * @test
     */
    public function add_film()
    {



    }


}
