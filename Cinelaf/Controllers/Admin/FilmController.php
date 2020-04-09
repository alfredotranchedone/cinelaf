<?php
/**
 * Created by alfredo
 * Date: 2020-03-15
 * Time: 22:08
 */

namespace Cinelaf\Controllers\Admin;


use Cinelaf\Models\Film;
use Cinelaf\Repositories\Rating;
use Cinelaf\Repositories\Registi;
use Cinelaf\Repositories\Watchlist;
use Cinelaf\Services\Upload;
use Cinelaf\Traits\Redirectable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FilmController extends BaseController
{

    use Redirectable;


    public function get_edit(Film $film)
    {

        return view('admin.film.edit', compact(
            'film'
        ));

    }

    public function put_update(Request $request, Film $film)
    {

        $this->validate($request,[
            'titolo' => [
                'required',
                'max:150',
                Rule::unique('films','titolo')->whereNull('deleted_at')->ignore($film->id)
            ],
            'anno' => 'required|date_format:Y',
            'registi.*' => 'required|exists:registi,id',
            'locandina' => 'nullable|file|mimes:jpeg,jpg,png|dimensions:max_width=1500,max_height=1500|max:1536',
        ]);

        DB::beginTransaction();

        try {

            /* Aggiorna locandina */
            $locandina = (new Upload())->updateLocandina($request, $film);
            $film->locandina = $locandina;

            /* Aggiorna e salva */
            $film->titolo = $request->titolo;
            $film->anno = $request->anno;
            $film->save();

            /* Aggiorna Registi */
            $registiRepo = new Registi();
            $registiRepo
                ->cleanRegistiFromFilm($film->id)
                ->attachRegistaToFilm($request->regista,$film->id);

            /* Aggiorna Valutazione */
            $ratingRepo = new Rating();
            $ratingRepo->updateValutazione($film->id);

            DB::commit();

            return redirect()
                ->route('film.show',[$film])
                ->with('success','Movie modificato!');

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->errorCallback($e);

        }

    }


    public function delete(Registi $registiRepo, Rating $ratingRepo, Upload $uploadService, Watchlist $watchlistRepo, Film $film)
    {
        try {

            // Ratings
            $ratingRepo->removeAll($film->id);

            // Registi
            $registiRepo->cleanRegistiFromFilm($film->id);

            // Locandina
            $uploadService->removeLocandina($film);

            // Watchlist
            $watchlistRepo->removeFilmFromWatchlists($film->id);

            // Movie
            $film->delete();

            return redirect()
                ->route('admin.dashboard')
                ->with('success','Movie eliminato!');

        } catch (\Exception $e) {

            return $this->errorCallback($e);


        }
    }


    public function forceDelete(Request $request, \Cinelaf\Repositories\Film $filmRepo)
    {

        try {

            $filmRepo->forceDelete($request->id);

            return redirect()
                ->route('admin.dashboard')
                ->with('success','Movie rimosso definitivamente dall\'archivio!');

        } catch (\Exception $e) {

            return $this->errorCallback($e);

        }

    }


    private function errorCallback(\Exception $error){

        logger()->error($error->getMessage());
        return $this->errorRedirect('admin.dashboard');

    }


}