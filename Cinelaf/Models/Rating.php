<?php
/**
 * Created by alfredo
 * Date: 2020-03-15
 * Time: 21:37
 */

namespace Cinelaf\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{

    protected $table = 'ratings';

    public function film() {
        return $this->belongsTo(Film::class,'film_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function setVotoAttribute($value){
        $this->attributes['voto'] = str_replace(',', '.', $value);
    }

}