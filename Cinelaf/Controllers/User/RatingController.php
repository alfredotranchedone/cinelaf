<?php
/**
 * Created by alfredo
 * Date: 2020-03-15
 * Time: 23:24
 */

namespace Cinelaf\Controllers\User;


use Cinelaf\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class RatingController extends BaseController
{


    public function get_vota(Movie $film)
    {

        $film->load(['rating']);

        $myRating = $film->rating->firstWhere('user_id', auth()->id());

        return view('user.rating.vota', compact(
            'film','myRating'
        ));

    }


    public function post_vota(Request $request, Movie $film, \Cinelaf\Repositories\Rating $ratingRepo)
    {


        $this->validate($request, [
            'voto' => 'required|max:5'
        ]);


        DB::beginTransaction();

        try {

            /* Salva Voto */
            $ratingRepo->save($film->id, $request->voto);

            DB::commit();

            return redirect()
                ->route('film.show', [$film->id])
                ->with('msg','Valutazione salvata!')
                ->with('msgType','success');

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error($e->getMessage(),['file'=> $e->getFile(), 'line' => $e->getLine()]);

            return redirect()
                ->route('film.show', [$film->id])
                ->with('msg','Errore durante la valutazione!')
                ->with('msgType','danger');

        }

    }





    public function delete(Request $request, Movie $film, \Cinelaf\Repositories\Rating $ratingRepo)
    {

        $this->validate($request, [
            'ratingId' => 'required'
        ]);

        DB::beginTransaction();

        try {

            /* Salva Voto */
            $ratingRepo->removeRating(decrypt($request->ratingId), $film->id);

            DB::commit();

            return redirect()
                ->route('film.show', [$film->id])
                ->with('success','Valutazione Rimossa!');

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error($e->getMessage(),['file'=> $e->getFile(), 'line' => $e->getLine()]);

            return redirect()
                ->route('film.show', [$film->id])
                ->with('danger','Errore durante la rimozione della valutazione!');

        }

    }





}