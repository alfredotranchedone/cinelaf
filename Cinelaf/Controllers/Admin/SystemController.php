<?php
/**
 * Created by alfredo
 * Date: 2020-04-12
 * Time: 22:11
 */

namespace Cinelaf\Controllers\Admin;


class SystemController extends BaseController
{

    public function get_reset()
    {

        $cmds = ['route:clear','cache:clear','config:clear','config:cache'];

        foreach ($cmds as $cmd){
            \Illuminate\Support\Facades\Artisan::call($cmd);
            dump( \Illuminate\Support\Facades\Artisan::output() );
        }

        echo( 'Reset Done' );

    }

}