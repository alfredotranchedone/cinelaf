<?php
/**
 * Created by alfredo
 * Date: 2020-03-15
 * Time: 23:13
 */

namespace Cinelaf\Repositories;


use Cinelaf\Models\Regista;
use Cinelaf\Resources\RegistiSelectResource;
use Illuminate\Support\Facades\DB;

class Registi
{

    public $table = 'registi';


    /**
     * @param string      $format   full|select
     * @param int|null    $limit
     * @param string|null $terms
     * @param bool        $withFilm
     *
     * @return \Illuminate\Support\Collection
     */
    public function get(string $format = 'full', int $limit = 50, string $terms = null, bool $withFilm=false)
    {

        $data = Regista::when($terms, function ($q) use ($terms) {
                return $q->whereRaw("CONCAT(nome,cognome) LIKE ?", ['%' . $terms . '%']);
            });
            $data->when($limit, function ($q) use ($limit) {
                return $q->limit($limit);
            })
            ->when($withFilm, function ($q) {
                return $q->with(['films' => function($u){
                    $u->select('titolo','anno','locandina');
                }]);
            })
            ->whereNull('deleted_at')
            ->orderBy('nome')
            ->orderBy('cognome');

        $out = $data->get();


        if($format=='select'){
            $out = RegistiSelectResource::collection($out);
        }

        return $out;

    }



    public function count()
    {
        return Regista::count();
    }



    public function exists($nome, $cognome)
    {
        $check = Regista::where('nome', $nome)->where('cognome',$cognome)->count();
        return $check > 0 ? true : false;

    }



    public function save($nome, $cognome){

        $r = new Regista();
        $r->nome = $nome;
        $r->cognome = $cognome;
        $r->save();

        return $r;

    }


    public function getNominativoFromId(array $ids, bool $stringify){

        $registi = Regista::whereIn('id',$ids)->get(['nome','cognome']);

        $out = $registi->map(function ($item){
            return "$item->nome $item->cognome";
        });

        if($stringify){
            $out = implode(', ', $out->toArray());
        }

        return $out;

    }


    public function attachRegistaToFilm(array $ids, int $film_id)
    {

        $data = [];
        foreach ($ids as $id) {
            $data[] = [
                'film_id' => $film_id,
                'regista_id' => $id
            ];
        }

        DB::table('films_registi')->insert($data);

        return $this;

    }


    public function cleanRegistiFromFilm(int $film_id)
    {

        DB::table('films_registi')->where('film_id',$film_id)->delete();

        return $this;

    }


}