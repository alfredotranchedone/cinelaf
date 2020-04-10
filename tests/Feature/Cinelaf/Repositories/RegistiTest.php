<?php

namespace Tests\Feature\Cinelaf\Repositories;

use App\User;
use Cinelaf\Repositories\Registi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RegistiTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /**
     * @return void
     * @test
     */
    public function attachRegistaToFilm()
    {

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $film_id = 100;
        $regista = [99];

        $registiRepo = new Registi();

        $registiRepo->attachRegistaToFilm($regista, $film_id);

        $registaInserted = DB::table('films_registi')
            ->where('film_id', $film_id)
            ->where('regista_id', $regista[0])
            ->first();

        $this->assertNotNull($registaInserted);

    }



    /**
     * @return void
     * @test
     */
    public function attachRegistiToFilm()
    {

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $film_id = 100;
        $registi = [99,98,97];

        $registiRepo = new Registi();

        $registiRepo->attachRegistaToFilm($registi, $film_id);

        $registaInserted = DB::table('films_registi')
            ->where('film_id', $film_id)
            ->get();

        $this->assertNotNull($registaInserted);

    }

}
