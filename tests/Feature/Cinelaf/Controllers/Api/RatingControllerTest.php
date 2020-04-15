<?php

namespace Tests\Feature\Cinelaf\Controllers\Api;

use App\User;
use Cinelaf\Models\Film;
use Cinelaf\Models\Rating;
use Cinelaf\Models\Regista;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RatingControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    private $user;

    protected function setUp(): void
    {
        parent::setUp(); //

        $registi = factory(Regista::class)->create();

        /* Add Filterable */
        factory(Regista::class)->create(['nome' => 'Demo', 'cognome' => 'Tester']);

        $user = factory(User::class)->create();
        $this->actingAs($user);
        $this->user = $user;

    }

    public function testGetAllFormatFull()
    {

        $response = $this->getJson(route('api.registi.all'));
        $response->assertStatus(200);
        $response->assertJsonStructure([[
            'id',
            'nome',
            'cognome'
        ]]);

    }


    public function testGetAllFormatSelect()
    {

        $response = $this->getJson(route('api.registi.all',[ 'format' => 'select' ]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [[
                'id',
                'text'
            ]]
        ]);

    }


    public function testGetAllFiltered()
    {

        $response = $this->getJson(route('api.registi.all',['q' => 'Demo Tester']));
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonStructure([[
            'id',
            'nome',
            'cognome'
        ]]);

    }



    public function testPostCreate()
    {

        $response = $this->postJson(route('api.registi.create'),[
            'nome' => 'Newly',
            'cognome' => 'Created'
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('registi',[
            'nome' => 'Newly',
            'cognome' => 'Created'
        ]);

    }


    public function testPostCreateRegistaExists()
    {

        $response = $this->postJson(route('api.registi.create'),[
            'nome' => 'Demo',
            'cognome' => 'Tester'
        ]);
        $response->assertStatus(200);

        $registaExistent = Regista::where([
            'nome' => 'Demo',
            'cognome' => 'Tester'
        ])->count();

        $this->assertEquals(1, $registaExistent);

        $response->assertJsonStructure([
            'error' => [
                'message'
            ]
        ]);

    }


}
