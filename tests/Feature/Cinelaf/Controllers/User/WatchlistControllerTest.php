<?php

namespace Tests\Feature\Cinelaf\Controllers\User;

use App\User;
use Cinelaf\Models\Film;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WatchlistControllerTest extends TestCase
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

    public function testGetIndex()
    {

        $response = $this->get(route('watchlist.index'));

        $response->assertStatus(200);

    }

    public function testGetAdd()
    {

        $film = factory(Film::class)->create();
        $response = $this->get(route('watchlist.add', $film->id));

        $this->assertDatabaseHas('watchlists', [
            'user_id' => $this->user->id,
            'film_id' => $film->id
        ]);

    }

    public function testRemove()
    {

        $film = factory(Film::class)->create();
        $response = $this->post(route('watchlist.remove',[
            'filmId' => $film->id
        ]));

        $response->assertSessionMissing('errors');

        $this->assertDatabaseMissing('watchlists', [
            'user_id' => $this->user->id,
            'film_id' => $film->id
        ]);

    }


}
