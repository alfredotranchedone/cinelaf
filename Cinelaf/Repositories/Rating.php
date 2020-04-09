<?php
/**
 * Created by alfredo
 * Date: 2020-03-19
 * Time: 22:12
 */

namespace Cinelaf\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Rating
{

    public function save(int $film_id, $voto)
    {

        \Cinelaf\Models\Rating::unguard();

        $rating = \Cinelaf\Models\Rating::updateOrCreate(
            ['film_id' => $film_id, 'user_id' => auth()->id()],
            ['voto' => $voto]
        );

        \Cinelaf\Models\Rating::reguard();

        /* Aggiorna Valutazione */
        $this->updateValutazione($film_id);

        return $rating;

    }


    public function removeAll(int $film_id)
    {
        \Cinelaf\Models\Rating::where('film_id', $film_id)->delete();
    }


    public function removeRating(int $rating_id, int $film_id)
    {

        \Cinelaf\Models\Rating::where('id', $rating_id)->delete();

        /* Aggiorna Valutazione */
        $this->updateValutazione($film_id);

        return $film_id;

    }


    /**
     *
     * Aggiorna la valutazione del singolo film.
     * Ad ogni aggiornamento ricalcola il rank di tutti i film, se valutazione > 0
     *
     * @param int $film_id
     *
     * @return bool
     */
    public function updateValutazione(int $film_id)
    {

        $ratings = $this->getRatingsByFilm($film_id);
        $valutazione = $this->calcolaValutazione($ratings);
        $media = $this->calcolaMedia($ratings);

        /* Aggiorna Rank */
        if($valutazione > 0)
            $this->updateRank();

        DB::table('films')
            ->where('id', $film_id)
            ->update([
                'valutazione' => $valutazione,
                'media' => $media
            ]);

        return true;

    }


    /**
     *
     * Aggiorna tutte le valutazioni dei films
     * Ad ogni aggiornamento ricalcola il rank di tutti i film
     *
     * @param null $limit
     * @param null $offset
     *
     * @return int
     */
    public function updateBatchValutazione($limit = null, $offset = null)
    {

        $films = \Cinelaf\Models\Film::with('rating')
            ->when($limit, function ($q) use ($limit) {
                $q->limit($limit);
            })
            ->when($offset, function ($q) use ($offset) {
                $q->offset($offset);
            })
            ->get(['id']);

        $films->each(function ($film) {
            $valutazione = $this->calcolaValutazione($film->rating);
            $media = $this->calcolaMedia($film->rating);
            DB::table('films')
                ->where('id', $film->id)
                ->update([
                    'valutazione' => $valutazione,
                    'media' => $media
                ]);
        });

        $count = $films->count();

        unset($film);

        /* Aggiorna Rank */
        $this->updateRank();

        return $count;

    }


    /**
     *
     * Per il calcolo della valutazione,
     * ordina per voto e rimuovi il primo e l'ultimo rating.
     *
     * @param Collection $ratings
     *
     * @return float
     */
    public function calcolaValutazione(Collection $ratings)
    {

        if ($ratings && $ratings->count() >= config('cinelaf.quorum')) {

            $ratingSorted = $ratings->sortByDesc('voto');
            $ratingSorted->pop();
            $ratingSorted->shift();

            return round($ratingSorted->avg('voto'), 2);

        } else {

            return 0;

        }

    }



    /**
     *
     * Calcola media
     *
     * @param Collection $ratings
     *
     * @return float
     */
    public function calcolaMedia(Collection $ratings)
    {

        if ($ratings) {

            return round($ratings->avg('voto'), 2);

        } else {

            return 0;

        }

    }





    /**
     *
     * Aggiorna il rank
     *
     * @return bool
     */
    public function updateRank()
    {

        $sql = 'UPDATE films SET `rank`= @r:= (@r+1) WHERE valutazione > 0 ORDER BY valutazione DESC, titolo asc';
        DB::statement(DB::raw('SET @r=0'));
        DB::select(DB::raw($sql));
        return true;

    }


    public function getRatingsByFilm(int $film_id)
    {

        return DB::table('ratings')
            ->where('film_id',$film_id)
            ->get();

    }


    public function getRatingsByUser(int $user_id, bool $pagination=false, int $paginationSize=50)
    {
        $query = \Cinelaf\Models\Rating::where('user_id',$user_id)
            ->with(['film' => function($q){
                $q->orderBy('titolo','DESC');
            }])
            ->orderBy('voto','DESC')
            ->latest('updated_at');

        if($pagination)
            return $query->paginate($paginationSize);

        return $query->get();

    }


    public function countRatingsByUser(int $user_id)
    {
        return DB::table('ratings')->where('user_id',$user_id)->count();
    }


}