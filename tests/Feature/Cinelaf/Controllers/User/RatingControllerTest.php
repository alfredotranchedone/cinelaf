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

    private $user;

    protected function setUp(): void
    {
        parent::setUp(); //

        $user = factory(User::class)->create();
        $this->actingAs($user);
        $this->user = $user;

    }


    public function testGetVota()
    {
        $film = factory(Film::class)->create();
        $response = $this->get(route('film.vota', $film->id));
        $response->assertViewIs('user.rating.vota');
    }



    /**
     * An User can Vote.
     *
     * @return void
     * @test
     */
    public function user_vote_a_film()
    {

        $film = factory(Film::class)->create();

        $this->assertDatabaseHas('films',['titolo' => $film->titolo, 'anno' => $film->anno]);

        $voto = $this->faker()->numberBetween(1,5);

        $response = $this
            ->post(route('film.vota.save',$film->id),[
                'voto' => $voto
            ]);

        $response->assertSessionMissing('errors');

        $rate = Rating::where('film_id', $film->id)
            ->where('user_id', $this->user->id)
            ->first();

        $this->assertNotNull($rate);

        $response->assertRedirect(route('film.show', [$film->id]));


    }



    public function testDeleteRating()
    {

        $film = factory(Film::class)->create();
        $film->rating()->save(new Rating([
            'user_id' => $this->user->id,
            'voto' => 4.0
        ]));

        $this->assertNotNull($film->rating);

        $response = $this->delete(route('film.vota.delete',$film->id),[
            'ratingId' => encrypt( $film->rating->first()->id )
        ]);

        $response->assertSessionMissing('errors');

        $this->assertDatabaseMissing('ratings', ['id' => $film->rating->first()->id ]);

    }


}
