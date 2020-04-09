<?php
/**
 * Created by alfredo
 * Date: 2020-04-09
 * Time: 19:02
 */

namespace Cinelaf\Repositories;


use Cinelaf\Repositories\Common\Common;

/**
 * Class Film is "superset" of Movie class and Series class
 *
 * @package Cinelaf\Repositories
 */
class Film extends Common
{

    /**
     * Series constructor.
     */
    public function __construct()
    {
        $this->type = null;
        $this->model = \Cinelaf\Models\Film::class;
    }
}