<?php
/**
 * Created by alfredo
 * Date: 2020-03-16
 * Time: 23:26
 */

namespace Cinelaf\Controllers\User;


use Cinelaf\Models\Film;
use Cinelaf\Models\Movie;
use Cinelaf\Logger\Logger;
use Cinelaf\Repositories\Watchlist;
use Cinelaf\Traits\Redirectable;
use Illuminate\Http\Request;

class WatchlistController extends BaseController
{

    use Redirectable;

    public function get_index(Watchlist $watchlistRepo)
    {

        $list = $watchlistRepo->get();
        return view('user.watchlist.index', compact(
            'list')
        );

    }


    public function get_add(Watchlist $watchlistRepo, Film $film)
    {

        try {

            $watchlistRepo->add($film->id);

            return redirect()
                ->route('film.show',[$film->id])
                ->with('success','Movie Aggiunto alla Watchlist');

        } catch (\Exception $e) {
            Logger::error($e);
            return $this->errorRedirect('film.show',[$film->id]);
        }

    }



    /**
     * @param Request   $request
     * @param Watchlist $watchlistRepo
     *
     * @return WatchlistController|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function post_remove(Request $request, Watchlist $watchlistRepo){

        $this->validate($request,[
            'filmId' => 'required|integer',
        ]);

        $film_id = $request->filmId;

        try {

            $watchlistRepo->remove($film_id);

            return redirect()
                ->to(url()->previous())
                ->with('success','Movie rimosso dalla Watchlist');

        } catch (\Exception $e) {
            Logger::error($e);

            return redirect()
                ->to(url()->previous())
                ->with('danger', 'Si è verificato un errore');

        }
    }

}