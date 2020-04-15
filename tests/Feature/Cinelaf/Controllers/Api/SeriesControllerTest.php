<?php

namespace Tests\Feature\Cinelaf\Controllers\Api;

use App\User;
use Cinelaf\Configuration\Configuration;
use Cinelaf\Models\Film;
use Cinelaf\Models\Rating;
use Cinelaf\Models\Regista;
use Cinelaf\Models\Watchlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SeriesControllerTest extends TestCase
{


    use RefreshDatabase, WithFaker;

    protected $user;
    protected $films;

    protected function setUp(): void
    {
        parent::setUp(); //

        $film_type_series = Configuration::TYPE_SERIES;
        $user = factory(User::class)->create();
        $films = factory(Film::class, 10)
            ->create(['user_id' => $user->id, 'type' => $film_type_series])
            ->each(function ($film) use ($user) {
                $film->regista()->save(factory(Regista::class)->make());
                $film->rating()->save(new Rating([
                    'user_id' => $user->id,
                    'voto' => $this->faker->numberBetween(1, 5)
                ]));
            });

        /* Add 1 extra film filterable */
        factory(Film::class)->create([
                'titolo' => '## A Filter ##',
                'user_id' => $user->id,
                'type' => $film_type_series
            ])
            ->each(function ($film) use ($user) {
                $film->regista()->save(factory(Regista::class)->make());
                $film->rating()->save(new Rating([
                    'user_id' => $user->id,
                    'voto' => $this->faker->numberBetween(1, 5)
                ]));
            });


        /* Add 1 extra film NOT RATED */
        factory(Film::class)->create([
                'titolo' => '## B Filter ##',
                'user_id' => $user->id,
                'type' => $film_type_series
            ])
            ->each(function ($film) use ($user) {
                $film->regista()->save(factory(Regista::class)->make());
            });


        /* Add 1 extra film With 0 Valutazione */
        factory(Film::class)->create([
                'titolo' => '## C Filter ##',
                'user_id' => $user->id,
                'type' => $film_type_series,
                'valutazione' => 0
            ])
            ->each(function ($film) use ($user) {
                $film->regista()->save(factory(Regista::class)->make());
            });

        $this->user = $user;
        $this->films = $films;

        $this->actingAs($user);

    }

    public function testGetAll()
    {

        $response = $this->getJson(route('api.series.all'));

        $response->assertStatus(200);
        $response->assertJsonCount(5);
        $response->assertJsonStructure([[
            'id',
            'titolo',
            'anno',
            'valutazione',
            'rank',
            'media',
            'type',
            'regista' => [[
                'nome',
                'cognome'
            ]],
            'user' => [
                'id',
                'name',
                'email'
            ]
        ]]);

    }

    public function testGetDatatableFormattedMovie()
    {

        $response = $this->getJson(route('api.series.dt.all', [
            'search' => ['value' => null],
            'length' => 5,
            'start' => 0,
            'order' => [[
                'column' => 'titolo',
                'dir' => 'ASC'
            ]],
        ]));

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'draw',
            'data' => [[
                'id',
                'titolo',
                'anno',
                'valutazione',
                'rank',
                'media',
                'type',
                'regista' => [[
                    'nome',
                    'cognome'
                ]],
                'created_at',
                'updated_at',
            ]],
            'recordsFiltered',
            'recordsTotal'
        ]);

    }


    public function testGetDatatableFormattedMovieFiltered()
    {

        $response = $this->getJson(route('api.series.dt.all', [
            'search' => ['value' => 'A Filter ##'],
            'length' => 5,
            'start' => 0,
            'order' => [[
                'column' => 'titolo',
                'dir' => 'ASC'
            ]],
        ]));

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonStructure([
            'draw',
            'data' => [[
                'id',
                'titolo',
                'anno',
                'valutazione',
                'rank',
                'media',
                'type',
                'regista' => [[
                    'nome',
                    'cognome'
                ]],
                'created_at',
                'updated_at'
            ]],
            'recordsFiltered',
            'recordsTotal'
        ]);

    }


    public function testGetDatatableFormattedMovieRatedByCurrentUser()
    {

        $response = $this->getJson(route('api.series.dt.myratings', [
            'search' => ['value' => null],
            'length' => 5,
            'start' => 0,
            'order' => [[
                'column' => 'titolo',
                'dir' => 'ASC'
            ]],
        ]));

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
        $response->assertJsonStructure([
            'draw',
            'data' => [[
                'voto',
                'user_id',
                'created_at',
                'updated_at',
                'data_voto',
                'film' => [
                    'id',
                    'titolo',
                    'anno',
                    'locandina',
                    'user_id',
                    'created_at',
                    'updated_at'
                ]
            ]],
            'recordsFiltered',
            'recordsTotal'
        ]);

    }


    public function testGetDatatableFormattedMovieRatedByCurrentUserFiltered()
    {

        $response = $this->getJson(route('api.series.dt.myratings', [
            'search' => ['value' => 'Filter ##'],
            'length' => 5,
            'start' => 0,
            'order' => [[
                'column' => 'titolo',
                'dir' => 'ASC'
            ]],
        ]));

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonStructure([
            'draw',
            'data' => [[
                'voto',
                'user_id',
                'created_at',
                'updated_at',
                'data_voto',
                'film' => [
                    'id',
                    'titolo',
                    'anno',
                    'locandina',
                    'user_id',
                    'created_at',
                    'updated_at'
                ]
            ]],
            'recordsFiltered',
            'recordsTotal'
        ]);

    }


    public function testGetDatatableFormattedMovieNotRatedByCurrentUser()
    {

        $response = $this->getJson(route('api.series.dt.mynotrated', [
            'search' => ['value' => null],
            'length' => 5,
            'start' => 0,
            'order' => [[
                'column' => 'titolo',
                'dir' => 'ASC'
            ]],
        ]));

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
        $response->assertJsonStructure([
            'draw',
            'data' => [[
                'id',
                'titolo',
                'anno',
                'valutazione',
                'created_at',
                'updated_at'
            ]],
            'recordsFiltered',
            'recordsTotal'
        ]);

    }


    public function testGetDatatableFormattedMovieNotRatedByCurrentUserFilteredEmptyResult()
    {

        $response = $this->getJson(route('api.series.dt.mynotrated', [
            'search' => ['value' => "A Filtered ##"],
            'length' => 5,
            'start' => 0,
            'order' => [[
                'column' => 'titolo',
                'dir' => 'ASC'
            ]],
        ]));

        $response->assertStatus(200);
        $response->assertJsonCount(0, 'data');

    }



    public function testGetDatatableFormattedMovieNoQuorumByCurrentUser()
    {

        $response = $this->getJson(route('api.series.dt.noquorum', [
            'search' => ['value' => null],
            'length' => 5,
            'start' => 0,
            'order' => [[
                'column' => 'titolo',
                'dir' => 'ASC'
            ]],
        ]));

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonStructure([
            'draw',
            'data' => [[
                'id',
                'titolo',
                'anno',
                'valutazione',
                'rank',
                'media',
                'type',
                'regista' => [[
                    'nome',
                    'cognome'
                ]],
                'created_at',
                'updated_at'
            ]],
            'recordsFiltered',
            'recordsTotal'
        ]);
    }


    public function testGetDatatableFormattedMovieNoQuorumByCurrentUserFiltered()
    {

        $response = $this->getJson(route('api.series.dt.noquorum', [
            'search' => ['value' => 'C Filter ##'],
            'length' => 5,
            'start' => 0,
            'order' => [[
                'column' => 'titolo',
                'dir' => 'ASC'
            ]],
        ]));

        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
        $response->assertJsonStructure([
            'draw',
            'data' => [[
                'id',
                'titolo',
                'anno',
                'valutazione',
                'rank',
                'media',
                'type',
                'regista' => [[
                    'nome',
                    'cognome'
                ]],
                'created_at',
                'updated_at'
            ]],
            'recordsFiltered',
            'recordsTotal'
        ]);
    }


}
