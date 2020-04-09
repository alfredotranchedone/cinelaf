<?php
/**
 * Created by alfredo
 * Date: 2020-03-16
 * Time: 21:41
 */

return [

    'version' => env('VERSION'),

    'sessions_key' => [
        'film' => [
            'new' => 'film:new'
        ],
        'watchlist' => [
            'total' => 'watchlist:total'
        ]
    ],

    'quorum' => 5

];