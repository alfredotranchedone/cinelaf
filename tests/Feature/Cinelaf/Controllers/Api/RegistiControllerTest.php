<?php

namespace Tests\Feature\Cinelaf\Controllers\Api;

use App\User;
use Cinelaf\Models\Film;
use Cinelaf\Models\Rating;
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





    /**
     * An User can Vote.
     *
     * @return void
     * @test
     */
    public function user_vote_a_film()
    {

        $voto = $this->faker()->numberBetween(1,5);
        $film = factory(Film::class)->create();

        $this->assertDatabaseHas('films',['titolo' => $film->titolo, 'anno' => $film->anno]);

        $response = $this
            ->postJson(route('api.rating.vote'),[
                'filmId' => $film->id,
                'voto' => $voto
            ]);

        $rate = Rating::where('film_id', $film->id)
            ->where('user_id', $this->user->id)
            ->first();

        $this->assertNotNull($rate);

        $response->assertStatus(200);

    }

}
