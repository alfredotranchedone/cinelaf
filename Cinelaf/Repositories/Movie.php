<?php
/**
 * Created by alfredo
 * Date: 2020-03-15
 * Time: 23:13
 */

namespace Cinelaf\Repositories;


use Cinelaf\Configuration\Configuration;
use Cinelaf\Repositories\Common\Common;

class Movie extends Common
{



    /**
     * Series constructor.
     */
    public function __construct()
    {
        $this->type = Configuration::TYPE_MOVIE;
        $this->model = \Cinelaf\Models\Movie::class;
    }




    public function getLatestCreated($limit = 5)
    {

        return $this->model::latest()
            ->with('user')
            ->limit($limit)
            ->get();
    }

}