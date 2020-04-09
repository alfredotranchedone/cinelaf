<?php
/**
 * Created by alfredo
 * Date: 2020-04-09
 * Time: 15:55
 */

namespace Cinelaf\Models;


use Cinelaf\Configuration\Configuration;
use Illuminate\Database\Eloquent\Builder;

class Series extends Film
{

    protected static function booted()
    {
        static::addGlobalScope('typeSeries', function (Builder $builder) {
            $builder->where('type', Configuration::TYPE_SERIES);
        });
    }

}