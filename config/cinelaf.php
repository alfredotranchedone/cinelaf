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
            'new' => \Cinelaf\Configuration\Configuration::SESSION_FILM_NEW
        ],
        'watchlist' => [
            'total' => \Cinelaf\Configuration\Configuration::SESSION_WATCHLIST_TOTAL
        ]
    ],

    'quorum' => 5

];