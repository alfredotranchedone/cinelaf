<?php
/**
 * Created by alfredo
 * Date: 2020-03-15
 * Time: 22:19
 */

namespace Cinelaf\Controllers\Admin;


use App\User;
use Cinelaf\Repositories\Movie;
use Cinelaf\Repositories\Registi;

class DashboardController extends BaseController
{

    public function get_index(Movie $filmRepo, Registi $registiRepo)
    {

        $filmTotale = $filmRepo->count();
        $filmNonVotatiTotale = $filmRepo->countNotRated();
        $registiTotale = $registiRepo->count();
        $usersTotale = User::count();
        $trashed = $filmRepo->getTrashed();

        return view('admin.dashboard',compact(
            'filmTotale', 'filmNonVotatiTotale',
            'registiTotale', 'usersTotale','trashed'
        ));

    }

}