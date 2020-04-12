<?php

namespace Tests\Feature\Cinelaf\Controllers\User;

use App\User;
use Cinelaf\Models\Film;
use Cinelaf\Models\Rating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RatingControllerTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /**
     * An User can Vote.
     *
     * @return void
     * @test
     */
    public function user_vote_a_film()
    {

        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $film = factory(Film::class)->create();

        $this->assertDatabaseHas('films',['titolo' => $film->titolo, 'anno' => $film->anno]);

        $voto = $this->faker()->numberBetween(1,5);

        $response = $this
            ->actingAs($user)
            ->post(route('film.vota.save',$film->id),[
                'voto' => $voto
            ]);

        $rate = Rating::where('film_id', $film->id)
            ->where('user_id', $user->id)
            ->first();

        $this->assertNotNull($rate);

        $response->assertRedirect(route('film.show', [$film->id]));

        $flash_success = session()->has('success');

        $this->assertTrue($flash_success);

    }


}
