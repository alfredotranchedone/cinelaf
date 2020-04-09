<?php
/**
 * Created by alfredo
 * Date: 2020-03-15
 * Time: 23:24
 */

namespace Cinelaf\Controllers\User;


use Cinelaf\Models\Film;
use Cinelaf\Models\Movie;
use Cinelaf\Repositories\Registi;
use Cinelaf\Services\FilmService;
use Cinelaf\Services\FilmSession;
use Cinelaf\Services\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FilmController extends BaseController
{




    public function get_index()
    {

        $viewPar = $this->routeVar();
        $viewPar['dataAjaxUrl'] = route('api.film.dt.all');

        return view('user.film.index', $viewPar
        );

    }


    public function get_add(FilmSession $filmSession)
    {
        $filmSession->delete();
        return view('user.film.add');
    }



    public function post_add_step_2(Request $request, FilmSession $filmSession)
    {
        if( ! $request->titolo)
            return redirect()->route('film.add');

        $titolo = ucwords($request->titolo);

        $filmSession
            ->setTitolo($titolo)
            ->save();

        return view('user.film.add_step_2');

    }



    public function post_add_step_3(Request $request, FilmSession $filmSession, Registi $registiRepo)
    {

        if( ! $request->regista)
            return redirect()->route('film.add.step_2');

        $regista = $request->regista;
        $type = $request->type;

        $fs = $filmSession
            ->setRegista($regista)
            ->setType($type)
            ->save();

        $titolo = $fs['titolo'];
        $type = $fs['type'];
        $regista_string = $registiRepo->getNominativoFromId($regista,true);

        return view('user.film.add_step_3', compact(
            'regista_string','titolo','type'
        ));

    }


    public function post_create(Request $request, Upload $uploadService, FilmSession $filmSession, Registi $registiRepo, \Cinelaf\Repositories\Film $filmRepo)
    {

        $this->validate($request,[
            'anno' => 'required',
            'locandina' => 'file|mimes:jpeg,jpg,png|dimensions:max_width=1500,max_height=1500|max:1536',
        ]);

        DB::beginTransaction();

        try {

            // Recupera i dati in sessione
            $currentFilm = $filmSession->get();

            // Carica File
            $fileNameToStore = $uploadService->locandina($request);

            // Salva film
            $film = $filmRepo->save($currentFilm['titolo'], $request->anno, $fileNameToStore, $currentFilm['type']);

            // Salva regista
            $registiRepo->attachRegistaToFilm($currentFilm['regista'], $film->id);

            // Svuota sessione
            $filmSession->reset();

            DB::commit();

            return redirect()
                ->route('home')
                ->with('msg','Movie inserito correttamente!')
                ->with('msgType','success');

        } catch (\Exception $e) {

            DB::rollBack();

            logger()->error('Errore nella creazione del Movie',['msg' => $e->getMessage()]);

            return redirect()
                ->route('film.add')
                ->with('msg','Errore nella creazione del Movie')
                ->with('msgType','danger');

        }


    }


    public function get_show(Film $film, FilmService $filmService)
    {

        $film->load(['user','regista','rating.user']);
        $film->load(['watchlist'=>function($query){
            $query->where('user_id',auth()->id());
        }]);

        $rating = $film->rating;

        $valutazione = $filmService->valutazione($rating);

        return view('user.film.show', compact(
            'film','rating', 'valutazione'
        ));

    }

    public function get_my_ratings()
    {

        $viewPar = $this->routeVar();
        $viewPar['dataAjaxUrl'] = route('api.film.dt.myratings');

        return view('user.film.myratings', $viewPar);

    }

    public function get_my_not_rated()
    {

        $viewPar = $this->routeVar();
        $viewPar['dataAjaxUrl'] = route('api.film.dt.mynotrated');
        return view('user.film.mynotrated', $viewPar);

    }


    public function get_no_quorum()
    {

        $viewPar = $this->routeVar();
        $viewPar['dataAjaxUrl'] = route('api.film.dt.noquorum');
        return view('user.film.noquorum', $viewPar);

    }



    private function routeVar(){

        $routeMyRating = route('film.myratings');
        $routeMyNotRated = route('film.mynotrated');
        $routeNoQuorum = route('film.noquorum');

        return compact('routeMyRating','routeNoQuorum','routeMyNotRated');

    }

    
}