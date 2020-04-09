<?php
/**
 * Created by alfredo
 * Date: 2020-03-25
 * Time: 16:14
 */

namespace Cinelaf\Services;

use Cinelaf\Repositories\Rating;
use Illuminate\Database\Eloquent\Collection;

class FilmService
{


    /**
     *
     * Per il calcolo della valutazione,
     * ordina per voto e rimuovi il primo e l'ultimo rating.
     *
     * @param Collection $ratings
     *
     * @return float
     */
    public function valutazione(Collection $ratings)
    {

        $r = new Rating();
        return $r->calcolaValutazione($ratings);

    }

}