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
        $dataAjaxUrl = route('api.film.dt.all');
        return view('user.film.index',compact(
            'dataAjaxUrl'
        ));
    }

}