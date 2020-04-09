<?php
/**
 * Created by alfredo
 * Date: 2020-04-09
 * Time: 19:00
 */

namespace Cinelaf\Models;


use App\User;
use Cinelaf\Configuration\Configuration;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 *
 * Class Film is the parent of Movie class and Series class
 *
 * NB Relationships: foreignKey has to be explicitly declared (because of the inheriting classes)
 *
 * @package Cinelaf\Models
 */
class Film extends Model
{

    use SoftDeletes;

    protected $table = 'films';



    public function rating()
    {
        return $this->hasMany(Rating::class, 'film_id');
    }


    public function regista()
    {
        return $this->belongsToMany(Regista::class,'films_registi','film_id','regista_id');
    }

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }

    public function watchlist() {
        return $this->hasOne(Watchlist::class,'user_id');
    }

    public function seriesMeta() {
        return $this->hasMany(SeriesMeta::class,'film_id');
    }


    public function scopeWithWatchlist()
    {
        return $this->with(['watchlist'=>function($query){
            $query->where('user_id',auth()->id());
        }]);
    }

    public function isMovie()
    {
        return $this->type === Configuration::TYPE_MOVIE;
    }

    public function isSeries()
    {
        return $this->type === Configuration::TYPE_SERIES;
    }

}