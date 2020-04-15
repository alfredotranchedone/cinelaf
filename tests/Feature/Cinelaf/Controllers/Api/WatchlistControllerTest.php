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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WatchlistControllerTest extends TestCase
{


    use RefreshDatabase, WithFaker;

    protected $user;
    protected $films;

    protected function setUp(): void
    {
        parent::setUp(); //

        $user = factory(User::class)->create();
        $films = factory(Film::class, 10)
            ->create(['user_id' => $user->id])
            ->each(function ($film) use ($user) {
                $film->regista()->save(factory(Regista::class)->make());
                $film->rating()->save(new Rating([
                    'user_id' => $user->id,
                    'voto' => $this->faker->numberBetween(1, 5)
                ]));

                DB::table('watchlists')->insert(['film_id' => $film->id, 'user_id' => $user->id]);

            });

        $this->user = $user;
        $this->films = $films;

        $this->actingAs($user);

    }



    public function testGetCurrentUserList()
    {

        $response = $this->getJson(route('api.watchlist.get'));

        $response->assertStatus(200);
        $response->assertJsonCount(10,'data');


    }

    public function testPostAdd()
    {

        $film = factory(Film::class)->create();
        $response = $this->postJson(route('api.watchlist.add',[
            'filmId' => $film->id
        ]));

        $response->assertStatus(200);

        $this->assertDatabaseHas('watchlists',[
            'film_id' => $film->id,
            'user_id'=> $this->user->id
        ]);

    }



    public function testPostRemove()
    {

        $film = factory(Film::class)->create();

        DB::table('watchlists')->insert(['film_id' => $film->id, 'user_id' => $this->user->id]);
        $this->assertDatabaseHas('watchlists',[
            'film_id' => $film->id,
            'user_id'=> $this->user->id
        ]);

        $response = $this->postJson(route('api.watchlist.remove',[
            'filmId'=> $film->id
        ]));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('watchlists',[
            'film_id' => $film->id,
            'user_id'=> $this->user->id
        ]);

    }


}
