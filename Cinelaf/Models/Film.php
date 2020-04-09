<?php

namespace Cinelaf\Models;

use App\User;
use Cinelaf\Configuration\Configuration;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Film extends Model
{

    use SoftDeletes;

    protected $table = 'films';

    protected static function booted()
    {
        static::addGlobalScope('typeMovie', function (Builder $builder) {
            $builder->where('type', Configuration::TYPE_MOVIE);
        });
    }


    public function rating()
    {
        return $this->hasMany(Rating::class);
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


    public function scopeWithWatchlist()
    {
        return $this->with(['watchlist'=>function($query){
            $query->where('user_id',auth()->id());
        }]);
    }


}
