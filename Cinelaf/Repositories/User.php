<?php
/**
 * Created by alfredo
 * Date: 2020-03-28
 * Time: 11:40
 */

namespace Cinelaf\Repositories;


use Illuminate\Support\Facades\DB;

class User
{

    public function myAverage()
    {

        $q = DB::table('ratings')
            ->selectRaw('ROUND(AVG(voto),2) as media')
            ->where('user_id','=',auth()->id())
            ->first();

        return $q->media ?? 0;

    }


}