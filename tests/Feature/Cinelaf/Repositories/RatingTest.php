<?php

namespace Tests\Feature\Cinelaf\Repositories;

use App\User;
use Cinelaf\Models\Film;
use Cinelaf\Models\Rating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RatingTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /**
     * An User can Vote.
     *
     * @return void
     * @test
     */
    public function remove_vote()
    {

        $user = factory(User::class)->create();
        $film = factory(Film::class)->create();
        $ratingRepo = new \Cinelaf\Repositories\Rating();

        $this->actingAs($user);
        $this->assertDatabaseHas('films',['titolo' => $film->titolo, 'anno' => $film->anno]);

        $voto = 4;

        $rate = new Rating();
        $rate->film_id = $film->id;
        $rate->user_id = $user->id;
        $rate->voto = $voto;
        $rate->save();

        $rateInserted = Rating::where('film_id', $film->id)
            ->where('user_id', $user->id)
            ->first();

        $this->assertNotNull($rateInserted);

        $ratingRepo->removeRating($rate->id,$film->id);

        $rateRemoved = Rating::where('id', $rate->id)->first();

        $this->assertNull($rateRemoved);

    }


}
