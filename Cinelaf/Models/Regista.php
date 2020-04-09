<?php
/**
 * Created by alfredo
 * Date: 2020-03-15
 * Time: 21:33
 */

namespace Cinelaf\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regista extends Model
{

    use SoftDeletes;

    protected $table = 'registi';

    public function films()
    {
        return $this->belongsToMany(Film::class,'films_registi','regista_id','film_id');
    }


    public function setNomeAttribute($value)
    {
        $this->attributes['nome'] = ucwords($value);
    }

    public function setCognomeAttribute($value)
    {
        $this->attributes['cognome'] = ucwords($value);
    }

}