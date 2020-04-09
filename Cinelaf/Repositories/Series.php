<?php
/**
 * Created by alfredo
 * Date: 2020-04-09
 * Time: 15:04
 */

namespace Cinelaf\Repositories;


use Cinelaf\Configuration\Configuration;
use Cinelaf\Repositories\Common\Common;

class Series extends Common
{


    /**
     * Series constructor.
     */
    public function __construct()
    {
        $this->type = Configuration::TYPE_SERIES;
        $this->model = \Cinelaf\Models\Series::class;
    }



    public function getLatestCreated($limit = 5)
    {

        return $this->model::latest()
            ->with('user')
            ->limit($limit)
            ->get();
    }


}