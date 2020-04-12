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

        $output='';
        foreach ($cmds as $cmd){
            \Illuminate\Support\Facades\Artisan::call($cmd);
            $output .= '<pre>'. \Illuminate\Support\Facades\Artisan::output() .'</pre>';
        }

        $output .= '<div>Reset Done</div>';

        return view('admin.generic', compact('output'));

    }

}