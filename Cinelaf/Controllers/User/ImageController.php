<?php
/**
 * Created by alfredo
 * Date: 2020-03-16
 * Time: 23:26
 */

namespace Cinelaf\Controllers\User;


use Illuminate\Support\Facades\File;

class ImageController extends BaseController
{


    public function get_locandina($locandina = 'placeholder.jpg')
    {

        $storage_loc = storage_path('app/public/locandine/' . $locandina);
        $storage_plh = storage_path('app/public/locandine/placeholder.jpg');

        if(File::exists($storage_loc) )
            return response()->file($storage_loc);

        if( ! File::exists($storage_plh))
            File::copy(public_path('img/placeholder.jpg'), $storage_plh);

        return response()->file($storage_plh);

    }

}