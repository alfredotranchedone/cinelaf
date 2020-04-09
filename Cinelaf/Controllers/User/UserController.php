<?php
/**
 * Created by alfredo
 * Date: 2020-04-02
 * Time: 19:31
 */

namespace Cinelaf\Controllers\User;


use App\User;
use Cinelaf\Repositories\Rating;

class UserController
{

    public function get_ratings(Rating $ratingRepo, User $user)
    {

        $ratings = $ratingRepo->getRatingsByUser($user->id, true);
        $ratingsTotal = $ratingRepo->countRatingsByUser($user->id);

        return view('user.film.ratings', compact(
            'user', 'ratings', 'ratingsTotal'
        ));

    }

}