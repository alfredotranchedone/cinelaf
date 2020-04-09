<?php
/**
 * Created by alfredo
 * Date: 2020-03-20
 * Time: 17:18
 */

namespace Cinelaf\Traits;


trait Redirectable
{

    public function errorRedirect($route, $routeParameters=null, $msg = 'Si è verificato un errore')
    {

        return redirect()
            ->route($route, $routeParameters)
            ->with('danger', $msg);

    }

}