<?php

namespace Cinelaf;

use App\User;
use Cinelaf\Models\Rating;
use Cinelaf\Models\Regista;
use Cinelaf\Models\Watchlist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Film extends Model
{

    use SoftDeletes;

    protected $table = 'films';


    public function rating()
    {
        return $this->hasMany(Rating::class);
    }


    public function regista()
    {
        return $this->belongsToMany(Regista::class,'films_registi','film_id','regista_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function watchlist() {
        return $this->hasOne(Watchlist::class);
    }


    public function scopeWithWatchlist($q)
    {
        return $this->with(['watchlist'=>function($query){
            $query->where('user_id',auth()->id());
        }]);
    }


}
