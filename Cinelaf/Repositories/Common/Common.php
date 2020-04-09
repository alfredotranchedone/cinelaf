<?php
/**
 * Created by alfredo
 * Date: 2020-04-09
 * Time: 15:06
 */

namespace Cinelaf\Repositories\Common;


use Carbon\Carbon;
use Cinelaf\Configuration\Configuration;
use Cinelaf\Models\Film;
use Cinelaf\Models\Rating;
use Cinelaf\Resources\FilmSelectResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class Common
{

    public $table = 'films';
    protected $type;
    protected $model;

    /**
     * @param string      $format
     * @param int         $limit
     * @param string|null $terms
     * @param bool        $withRegisti
     * @param bool        $withOwner
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|Collection
     */
    public function get(string $format = 'full', int $limit = 50, string $terms = null, bool $withRegisti=false, bool $withOwner=false)
    {

        if(!$format)
            $format='full';

        $data = $this->model::when($terms, function ($q) use ($terms) {
            return $q->whereRaw("titolo LIKE ?", ['%' . $terms . '%']);
        })
            ->when($limit, function ($q) use ($limit) {
                return $q->limit($limit);
            })
            ->when($withRegisti, function ($q) {
                return $q->with(['regista' => function($u){
                    $u->select('registi.id','nome','cognome');
                }]);
            })
            ->when($withOwner, function ($q) {
                return $q->with(['user' => function($u){
                    $u->select('id','name','email');
                }]);
            })
            ->orderBy('titolo');

        $out = $data->get();

        if($format=='select'){
            $out = FilmSelectResource::collection($out);
        }

        return $out;

    }



    public function filter(string $terms = null, int $offset = 0, int $limit = 50, string $orderby='titolo ASC')
    {

        $data = $this->model::whereRaw("CONCAT(titolo,anno) LIKE ?", ['%' . $terms . '%'])
            ->offset($offset)
            ->with('regista')
            ->limit($limit)
            ->orderByRaw($orderby);

        $out = $data->get();

        return $out;

    }




    /**
     * @param string|null $terms
     * @param int         $offset
     * @param int         $limit
     * @param string      $orderby
     *
     * @return \Illuminate\Support\Collection
     */
    public function filterMyRating(string $terms = null, int $offset = 0, int $limit = 50, string $orderby='ratings.updated_at DESC')
    {

        $user_id = auth()->id();
        $data = DB::table('ratings')
            ->selectRaw('ratings.*, 
                ratings.user_id as rating_user_id, 
                ratings.created_at as rating_created_at, 
                ratings.updated_at as rating_updated_at, 
                f.*,
                f.user_id as film_user_id,
                f.created_at as film_created_at, 
                f.updated_at as film_updated_at')
            ->whereRaw("CONCAT(titolo, anno) LIKE ?", ['%' . $terms . '%'])
            ->where('ratings.user_id', $user_id)
            ->whereNull('f.deleted_at')
            ->leftJoin('films as f','f.id','=','ratings.film_id')
            ->offset($offset)
            ->limit($limit)
            ->orderByRaw($orderby);

        if($this->type)
            $data->where('f.type',$this->type);

        $out = $data->get();

        return $out->map(function ($item){
            return $item = [
                'voto' => $item->voto,
                'user_id' => $item->rating_user_id,
                'created_at' => $item->rating_created_at,
                'updated_at' => $item->rating_updated_at,
                'data_voto' => Carbon::createFromFormat('Y-m-d H:i:s', $item->rating_updated_at)->diffForHumans(),
                'film' => [
                    'id' => $item->film_id,
                    'titolo' => $item->titolo,
                    'anno' => $item->anno,
                    'locandina' => $item->locandina,
                    'user_id' => $item->film_user_id,
                    'created_at' => $item->film_created_at,
                    'updated_at' => $item->film_updated_at
                ]
            ];
            /*
            return collect($item)->put('film',[
                'titolo' => $item->titolo,
                'anno' => $item->anno,
                'locandina' => $item->locandina
            ]);
             */
        });

    }



    public function filterMyNotRated(string $terms = null, int $offset = 0, int $limit = 50, string $orderby='titolo ASC')
    {

        /*
        $data = DB::table('films as f')
            ->selectRaw('f.*, r.voto')
            ->whereRaw("CONCAT(titolo, anno) LIKE ?", ['%' . $terms . '%'])
            ->whereNull('r.voto')
            ->whereNull('f.deleted_at')
            ->leftJoin('ratings as r','r.film_id','=','f.id')
            ->offset($offset)
            ->limit($limit)
            ->orderByRaw($orderby);
        */

        /*
        select f.*
        from films f
        where CONCAT(titolo, anno) LIKE '%a.m.%'
          and f.id NOT IN (
            select film_id
            from ratings
            where user_id = 1
          )
          and `f`.`deleted_at` is null
            order by valutazione desc
         */

        $data = DB::table('films as f')
            ->whereRaw("CONCAT(titolo, anno) LIKE ?", ['%' . $terms . '%'])
            ->whereRaw("f.id NOT IN ( SELECT film_id FROM ratings WHERE user_id = ? )", [auth()->id()])
            ->whereNull('f.deleted_at')
            ->offset($offset)
            ->limit($limit)
            ->orderByRaw($orderby);

        if($this->type)
            $data->where('f.type', $this->type);

        $out = $data->get();

        return $out->map(function ($item){
            return $item = [
                'id' => $item->id,
                'titolo' => $item->titolo,
                'anno' => $item->anno,
                'locandina' => $item->locandina,
                'user_id' => $item->user_id,
                'valutazione' => $item->valutazione,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at
            ];

        });

    }



    public function filterNoQuorum(string $terms = null, int $offset = 0, int $limit = 50, string $orderby='titolo ASC')
    {

        $data = $this->model::whereRaw("CONCAT(titolo,anno) LIKE ?", ['%' . $terms . '%'])
            ->offset($offset)
            ->with('regista')
            ->where('valutazione',0)
            ->limit($limit)
            ->orderByRaw($orderby);

        $out = $data->get();

        return $out;

    }



    public function count()
    {
        return $this->model::count();
    }



    /**
     * @return int
     */
    public function countNotRated()
    {

        $data = DB::table('films as f')
            ->selectRaw('f.*, r.voto')
            ->leftJoin('ratings as r','r.film_id','=','f.id')
            ->whereNull('r.voto')
            ->whereNull('f.deleted_at');

        if($this->type)
            $data->where('f.type', $this->type);

        $out = $data->count();

        return $out;

    }



    public function countMyNotRated()
    {

        /*
        $data = DB::table('films as f')
            ->selectRaw('f.*, r.voto')
            ->where('r.user_id','!=',auth()->id())
            ->whereNull('f.deleted_at')
            ->leftJoin('ratings as r','r.film_id','=','f.id');
        */

        $data = DB::table('films as f')
            ->whereRaw("f.id NOT IN ( SELECT film_id FROM ratings WHERE user_id = ? )", [auth()->id()])
            ->whereNull('deleted_at');

        if($this->type)
            $data->where('f.type',$this->type);

        $out = $data->count();

        return $out;

    }


    public function countNoQuorum()
    {

        $data = DB::table('films as f')
            ->where('valutazione',0)
            ->whereNull('deleted_at');

        $out = $data->count();

        if($this->type)
            $data->where('f.type', $this->type);

        return $out;

    }




    public function topRated(int $limit=5)
    {
        return $this->rated('best',$limit);
    }

    /**
     * @param int    $limit
     * @param string $type  'best' | 'worst'
     *
     * @return Collection
     */
    public function rated(string $type, int $limit=5)
    {

        $direction = null;
        switch ($type){
            case 'best':
                $direction = 'DESC';
                break;
            case 'worst':
                $direction = 'ASC';
                break;
        }


        $coll = $this->model::orderBy('valutazione',$direction)
            ->with('user')
            ->where('valutazione','>','0')
            ->limit($limit)
            ->get();

        return $coll;

    }

    public function worstRated(int $limit=5)
    {

        return $this->rated('worst',$limit);

    }

    /**
     *
     * Return all films!!!
     *
     * @param int    $limit
     * @param string $type  'most' | 'least'
     *
     * @return Collection
     */
    public function mostLeastRated(string $type, int $limit=5)
    {

        $direction = null;
        switch ($type){
            case 'most':
                $direction = 'DESC';
                break;
            case 'least':
                $direction = 'ASC';
                break;
        }

        $coll = Rating::selectRaw('count(ratings.id) as totale, 
                                    films.id, 
                                    films.titolo, 
                                    films.anno, 
                                    films.locandina,
                                    films.created_at,
                                    users.name as username'
        )
            ->leftJoin('films','films.id','=','ratings.film_id')
            ->leftJoin('users','users.id','=','films.user_id')
            ->orderBy('totale',$direction)
            ->groupBy('ratings.film_id')
            ->limit($limit)
            ->get();

        return $coll;

    }



    public function myRatingCount()
    {
        $user_id = auth()->id();
        return DB::table('ratings')
            ->distinct('film_id')
            ->where('user_id',$user_id)
            ->count('film_id');
    }

    public function myRating()
    {
        $user_id = auth()->id();
        return Rating::with('film')
            ->where('user_id', $user_id)
            ->get();
    }






    public function getLatestCreated($limit = 5)
    {

        return Film::latest()
            ->with('user')
            ->limit($limit)
            ->get();
    }




    public function save($titolo, $anno, $locandina, $type)
    {

        $film = new Film();
        $film->titolo = $titolo;
        $film->anno = $anno;
        $film->locandina = $locandina;
        $film->user_id = auth()->user()->id;
        $film->type = $type ?? Configuration::TYPE_MOVIE;
        $film->save();

        return $film;

    }



    public function getTrashed()
    {
        return $this->model::onlyTrashed()->orderBy('titolo')->get();
    }




    public function forceDelete(int $film_id)
    {
        return DB::table('films')->where('id',$film_id)->delete();
    }






}