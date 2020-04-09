<?php
/**
 * Created by alfredo
 * Date: 2020-03-15
 * Time: 23:13
 */

namespace Cinelaf\Repositories;


use Cinelaf\Configuration\Configuration;
use Cinelaf\Models\Rating;
use Cinelaf\Repositories\Common\Common;
use Cinelaf\Resources\FilmSelectResource;
use Illuminate\Support\Facades\DB;

class Film extends Common
{

    public $table = 'films';
    private $type = Configuration::TYPE_MOVIE;

    public function getLatestCreated($limit = 5)
    {
        return \Cinelaf\Models\Film::latest()
            ->with('user')
            ->limit($limit)
            ->get();
    }


    public function getTrashed()
    {
        return \Cinelaf\Models\Film::onlyTrashed()->orderBy('titolo')->get();
    }

    public function count()
    {
        return \Cinelaf\Models\Film::count();
    }



    public function countNotRated()
    {
        return $this->countNotRatedByType($this->type);
    }

    /**
     * @param string      $format   full|select
     * @param int|null    $limit
     * @param string|null $terms
     * @param bool        $withRegisti
     * @param bool        $withOwner
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(string $format = 'full', int $limit = 50, string $terms = null, bool $withRegisti=false, bool $withOwner=false)
    {

        if(!$format)
            $format='full';

        $data = \Cinelaf\Models\Film::when($terms, function ($q) use ($terms) {
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

        $data = \Cinelaf\Models\Film::whereRaw("CONCAT(titolo,anno) LIKE ?", ['%' . $terms . '%'])
            ->offset($offset)
            ->with('regista')
            ->limit($limit)
            ->orderByRaw($orderby);

        $out = $data->get();

        return $out;

    }


    public function filterNoQuorum(string $terms = null, int $offset = 0, int $limit = 50, string $orderby='titolo ASC')
    {

        $data = \Cinelaf\Models\Film::whereRaw("CONCAT(titolo,anno) LIKE ?", ['%' . $terms . '%'])
            ->offset($offset)
            ->with('regista')
            ->where('valutazione',0)
            ->limit($limit)
            ->orderByRaw($orderby);

        $out = $data->get();

        return $out;

    }



    public function filterMyRating(string $terms = null, int $offset = 0, int $limit = 50, string $orderby='ratings.updated_at DESC')
    {
        return $this->filterMyRatingByType( $this->type,$terms, $offset, $limit, $orderby);
    }



    public function filterMyNotRated(string $terms = null, int $offset = 0, int $limit = 50, string $orderby='titolo ASC')
    {
        return $this->filterMyNotRatedByType($this->type, $terms,$offset,$limit,$orderby);
    }



    public function countMyNotRated()
    {

        return $this->countNotRatedByType($this->type);

    }


    public function countNoQuorum()
    {

        return $this->countNoQuorumByType($this->type);

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

        /*
        $coll = Rating::with('film.user')
            ->groupBy('ratings.film_id')
            ->orderBy('ratings.voto',$direction)
            ->limit($limit)
            ->get();
        */

        $coll = \Cinelaf\Models\Film::orderBy('valutazione',$direction)
            ->with('user')
            ->where('valutazione','>','0')
            ->limit($limit)
            ->get();

        return $coll;

    }


    public function save($titolo, $anno, $locandina)
    {

        $film = new \Cinelaf\Models\Film();
        $film->titolo = $titolo;
        $film->anno = $anno;
        $film->locandina = $locandina;
        $film->user_id = auth()->user()->id;
        $film->save();

        return $film;

    }



    public function myRatingCount()
    {
        return DB::table('ratings')
            ->distinct('film_id')
            ->where('user_id',auth()->id())
            ->count('film_id');
    }

    public function myRating()
    {
        return Rating::with('film')->where('user_id',auth()->id())->get();
    }
    



}