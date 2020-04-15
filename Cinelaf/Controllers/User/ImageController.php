<?php
/**
 * Created by alfredo
 * Date: 2020-03-16
 * Time: 23:26
 */

namespace Cinelaf\Controllers\User;


use Illuminate\Support\Facades\File;

class ImageController
{

    public function get_locandina($locandina = null)
    {

        $locandina = $locandina ?? 'placeholder.jpg';

        if(File::exists(storage_path('app/public/locandine/' . $locandina)) )
            return response()->file(storage_path('app/public/locandine/' . $locandina));

        return response()->file(storage_path('app/public/locandine/placeholder.jpg'));

    }

}