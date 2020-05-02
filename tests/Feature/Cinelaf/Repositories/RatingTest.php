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
     * Remove a Vote.
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


    public function test_remove_all_votes_by_user_()
    {

        $total_film_to_create = 10;
        $total_users_extra_to_create = 4;

        $user = factory(User::class)->create();
        $this->actingAs($user);
        $films = factory(Film::class,$total_film_to_create)->create();

        $this->assertEquals(10, $films->count());

        $ratingRepo = new \Cinelaf\Repositories\Rating();

        /* Assegna un voto (5) per ogni film con l'utente corrente */
        $films->each(function ($film) use ($ratingRepo) {
            $ratingRepo->save($film->id, 5);
        });

        /* Assegna altri 5 voti al secondo film per calcolarne il rank (2) */
        $this->add_extra_rating_to_film(5, 2, 2);


        /**
         * Scenario 1: primo film, voti totali 5, eliminando un voto la valutazione scende a 0
         */

        $first_film = Film::find(1);

        /* Controlla valutazione e media iniziali */
        $this->assertEquals("0.00", $first_film->valutazione);
        $this->assertEquals("5.00", $first_film->media);

        /*
         *  Al primo film aggiungi un numero di voti pari a $total_users_extra_to_create
         *  per far scattare il calcolo della valutazione e del rank)
         */
        $this->add_extra_rating_to_film($total_users_extra_to_create, $first_film->id);
        /* * */

        $this->assertEquals(
            ($total_users_extra_to_create + $total_film_to_create + 5), // 4 = 4 voti extra del secondo film
            Rating::count()
        );

        /* Forza l'update del Rank */
        $ratingRepo->updateRank();


        /* Controlla che il rank del primo film sia 1 */
        $first_film_updated = Film::find(1);
        $this->assertEquals(1, $first_film_updated->rank);

        /* Controlla valutazione e media aggiornati */
        $this->assertEquals("3.00", $first_film_updated->valutazione);
        $this->assertEquals("3.40", $first_film_updated->media);


        /*
         * Elimina i voti dell'utente iniziale
         * */
        $this->actingAs($user);
        $ratingRepo->removeRatingsByUserIdAndUpdateFilms($user->id);


        /* Forza nuovamente l'update del Rank */
        $ratingRepo->updateRank();

        $first_film_updated = Film::find(1); // Ricarica il primo film

        /* Controlla valutazione e media dopo l'eliminazione */
        $this->assertEquals("0.00", $first_film_updated->valutazione);
        $this->assertEquals("3.00", $first_film_updated->media);

        /* TODO da aggiornare dopo il fix del ricalcolo del rank quando una valutazione scende a 0 */
        /* Controlla rank dei film 1 e 2 */
        // $this->assertEquals(2,$first_film_updated->rank);
        // $this->assertEquals(1,Film::find(2)->rank);

        /**
         * Scenario 2: primo film, voti totali 6, eliminando un voto la valutazione NON scende a 0
         */
        $this->add_extra_rating_to_film(2,1);

        /* Forza nuovamente l'update del Rank */
        $ratingRepo->updateRank();

        $first_film_updated = Film::find(1); // Ricarica il primo film

        /* Controlla che i voti del film 1 siano 6 */
        $this->assertEquals(6, Rating::where('film_id',1)->get()->count());

        /* Controlla valutazione e media dopo i nuovi voti */
        $this->assertEquals("3.00", $first_film_updated->valutazione);
        $this->assertEquals("3.00", $first_film_updated->media);

        /* Controlla rank dei film 1 e 2 */
        $this->assertEquals(1,$first_film_updated->rank);
        $this->assertEquals(2,Film::find(2)->rank);

    }


    private function add_extra_rating_to_film($number_of_extra_users, $film_id, $force_vote = 3)
    {
        $ratingRepo = new \Cinelaf\Repositories\Rating();
        $users_extra = factory(User::class,$number_of_extra_users)->create();
        $users_extra->each(function ($user_extra) use ($ratingRepo, $film_id, $force_vote) {
            $this->actingAs($user_extra);
            $ratingRepo->save($film_id, $force_vote);
        });
    }


}
