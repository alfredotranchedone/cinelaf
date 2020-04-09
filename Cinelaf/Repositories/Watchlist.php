<?php
/**
 * Created by alfredo
 * Date: 2020-03-27
 * Time: 15:13
 */

namespace Cinelaf\Repositories;


use Cinelaf\Logger\Logger;
use Cinelaf\Services\WatchlistSession;
use Illuminate\Support\Facades\Log;

class Watchlist
{

    private $user_id;

    /**
     * Watchlist constructor.
     */
    public function __construct()
    {
        $this->user_id = auth()->id();
    }

    public function get()
    {
        return \Cinelaf\Models\Watchlist::with('film.regista')
            ->where('user_id',$this->user_id)
            ->get();
    }

    /**
     * @param int $film_id
     *
     * @return int
     * @throws \Exception
     */
    public function add(int $film_id)
    {
        try {

            $w = \Cinelaf\Models\Watchlist::firstOrNew(
                ['user_id' => $this->user_id, 'film_id' => $film_id]
            );
            $w->save();

            (new WatchlistSession())->count();

            return $film_id;

        } catch (\Exception $e){
            Logger::error($e, 'Errore add to watchlist');
            throw $e;
        }
    }


    /**
     * @param int $film_id
     *
     * @return int
     * @throws \Exception
     */
    public function remove(int $film_id)
    {
        try {

            \Cinelaf\Models\Watchlist::where('user_id', $this->user_id)
                ->where('film_id',$film_id)
                ->delete();

            (new WatchlistSession())->count();

            return $film_id;

        } catch (\Exception $e){
            Logger::error($e, 'Errore remove from watchlist');
            throw $e;
        }

    }



    /**
     * @param int $film_id
     *
     * @return int
     * @throws \Exception
     */
    public function removeFilmFromWatchlists(int $film_id)
    {
        try {
            \Cinelaf\Models\Watchlist::where('film_id',$film_id)
                ->delete();
            return $film_id;
        } catch (\Exception $e){
            Logger::error($e, 'Errore rimuovi dalle watchlists');
            throw $e;
        }

    }

}