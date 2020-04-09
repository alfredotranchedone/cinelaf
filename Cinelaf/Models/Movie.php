<?php

namespace Cinelaf\Models;

use App\User;
use Cinelaf\Configuration\Configuration;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Film
{


    protected static function booted()
    {
        static::addGlobalScope('typeMovie', function (Builder $builder) {
            $builder->where('type', Configuration::TYPE_MOVIE);
        });
    }


}
