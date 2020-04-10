<?php

namespace Tests\Feature\Cinelaf\Repositories;

use App\User;
use Cinelaf\Repositories\Watchlist;
use Cinelaf\Services\WatchlistSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class WatchlistTest extends TestCase
{

    use RefreshDatabase, WithFaker;

    /**
     * @throws
     * @return void
     * @test
     */
    public function watchlist_add_get_remove()
    {

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $watchlistRepo = new Watchlist();
        /* Add */
        $watchlistRepo->add(100);
        $watchlistRepo->add(101);
        $watchlistRepo->add(102);
        $watchlistRepo->add(103);

        $ses = session()->get(config('cinelaf.sessions_key.watchlist.total'));
        $this->assertEquals(4, $ses);

        /* Get */
        $w = $watchlistRepo->get();

        $this->assertNotNull($w);
        $this->assertCount(4, $w);

        /* Remove */
        $watchlistRepo->remove(101);
        $w = $watchlistRepo->get();

        $this->assertCount(3, $w);


        /* Remove Film (eg. same film for 2 users) */
        DB::table('watchlists')
            ->insert([
                'user_id' => 2,
                'film_id' => 102
            ]);
        $watchlistRepo->removeFilmFromWatchlists(102);
        $data = DB::table('watchlists')->where('film_id',102)->get();
        $this->assertCount(0,$data);

    }



    /**
     * @throws
     * @return void
     * @test
     */
    public function watchlist_test_items_in_session()
    {

        $user = factory(User::class)->create();
        $this->actingAs($user);

        $watchlistRepo = new Watchlist();

        /* Add */
        $watchlistRepo->add(100);
        $watchlistRepo->add(101);
        $watchlistRepo->add(102);
        $watchlistRepo->add(103);

        $ses = session()->get(config('cinelaf.sessions_key.watchlist.total'));
        $this->assertEquals(4, $ses);

        $watchlistRepo->remove(100);
        $ses = session()->get(config('cinelaf.sessions_key.watchlist.total'));
        $this->assertEquals(3, $ses);

    }


}
