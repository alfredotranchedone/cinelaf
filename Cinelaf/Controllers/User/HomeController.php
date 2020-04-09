<?php

namespace Cinelaf\Controllers\User;

use Cinelaf\Repositories\Film;
use Cinelaf\Repositories\Movie;
use Cinelaf\Repositories\Rating;
use Cinelaf\Services\FilmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param $filmRepo
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Film $filmRepo)
    {
        $latestFilm = $filmRepo->getLatestCreated();
        $totalFilm = $filmRepo->count();
        $myRatingCount = $filmRepo->myRatingCount();

        $bestRated = $filmRepo->topRated(10);
        $worstRated = $filmRepo->worstRated(5);

        return view('home',compact(
            'latestFilm',
            'totalFilm',
            'bestRated',
            'worstRated',
            'myRatingCount'
        ));
    }
}
