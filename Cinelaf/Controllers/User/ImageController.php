<?php
/**
 * Created by alfredo
 * Date: 2020-03-16
 * Time: 23:26
 */

namespace Cinelaf\Controllers\User;


class ImageController
{

    public function get_locandina($locandina = null)
    {

        $locandina = $locandina ?? 'placeholder.jpg';
        return response()->file(storage_path('app/public/locandine/' . $locandina));

        // Storage::get('public/locandine/'.$locandina);

    }

}