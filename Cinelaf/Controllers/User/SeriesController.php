<?php
/**
 * Created by alfredo
 * Date: 2020-04-09
 * Time: 14:49
 */

namespace Cinelaf\Controllers\User;


class SeriesController extends BaseController
{



    public function get_index()
    {

        $viewPar = $this->routeVar();
        $viewPar['dataAjaxUrl'] = route('api.series.dt.all');

        return view('user.film.index', $viewPar);
    }


    public function get_my_ratings()
    {

        $viewPar = $this->routeVar();
        $viewPar['dataAjaxUrl'] = route('api.series.dt.myratings');
        return view('user.film.myratings',$viewPar);

    }

    public function get_my_not_rated()
    {

        $viewPar = $this->routeVar();
        $viewPar['dataAjaxUrl'] = route('api.series.dt.mynotrated');

        return view('user.film.mynotrated',$viewPar);

    }


    public function get_no_quorum()
    {

        $viewPar = $this->routeVar();
        $viewPar['dataAjaxUrl'] = route('api.series.dt.noquorum');

        return view('user.film.noquorum',$viewPar);

    }




    private function routeVar(){

        $routeMyRating = route('series.myratings');
        $routeMyNotRated = route('series.mynotrated');
        $routeNoQuorum = route('series.noquorum');

        return compact('routeMyRating','routeNoQuorum','routeMyNotRated');

    }

}