<?php

namespace Tests\Feature\Cinelaf\Controllers\User;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistiControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    private $user;

    protected function setUp(): void
    {
        parent::setUp(); //

        $user = factory(User::class)->create();
        $this->actingAs($user);
        $this->user = $user;

    }

    public function testPostCreate()
    {

        $nome = $this->faker->firstName;
        $cognome = $this->faker->lastName;
        $response = $this->post(route('registi.create'),[
            'nome' => $nome,
            'cognome' => $cognome
        ]);
        $response->assertSessionMissing('error');
        $this->assertDatabaseHas('registi',['nome'=>$nome,'cognome'=>$cognome]);

        /* Dont save if exists */
        $response = $this->post(route('registi.create'),[
            'nome' => $nome,
            'cognome' => $cognome
        ]);
        $response->assertSessionHas('warning');

    }


}
