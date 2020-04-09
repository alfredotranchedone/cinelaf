<?php
/**
 * Created by alfredo
 * Date: 2020-04-09
 * Time: 23:40
 */

namespace Cinelaf\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeriesMeta extends Model
{

    use SoftDeletes;

    protected $table = "seriesmeta";

}