<?php
/**
 * Created by alfredo
 * Date: 2020-03-28
 * Time: 11:21
 */

namespace Cinelaf\Services;


use Cinelaf\Repositories\Watchlist;

class WatchlistSession
{

    private $watchlistRepo;

    /**
     * WatchlistSession constructor.
     */
    public function __construct()
    {
        $this->watchlistRepo = new Watchlist();
    }

    public function count()
    {
        session([
            config('cinelaf.sessions_key.watchlist.total') => count($this->watchlistRepo->get())
        ]);
    }

}