<?php
/**
 * Created by alfredo
 * Date: 2020-03-15
 * Time: 21:37
 */

namespace Cinelaf\Models;


use App\User;
use Cinelaf\Film;
use Illuminate\Database\Eloquent\Model;

class Watchlist extends Model
{

    protected $table = 'watchlists';
    protected $fillable = ['film_id','user_id'];

    public function film() {
        return $this->belongsTo(Film::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }


}