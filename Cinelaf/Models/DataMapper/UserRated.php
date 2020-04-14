<?php
/**
 * Created by alfredo
 * Date: 2020-04-14
 * Time: 09:34
 */

namespace Cinelaf\Models\DataMapper;


use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class UserRated implements Arrayable
{

    public $voto;
    public $user_id;
    public $data_voto;
    public $created_at;
    public $updated_at;
    public $film = [
        'id',
        'titolo',
        'anno',
        'locandina',
        'user_id',
        'created_at',
        'updated_at',
    ];

    public function __construct($data)
    {

        $this->voto = $data->voto;
        $this->user_id = $data->rating_user_id;
        $this->created_at = $data->rating_created_at;
        $this->updated_at = $data->rating_updated_at;
        $this->data_voto = Carbon::createFromFormat('Y-m-d H:i:s', $data->rating_updated_at)->diffForHumans();
        $this->film = (object) [
            'id' => $data->film_id,
            'titolo' => $data->titolo,
            'anno' => $data->anno,
            'locandina' => $data->locandina,
            'user_id' => $data->film_user_id,
            'created_at' => $data->film_created_at,
            'updated_at' => $data->film_updated_at
        ];

    }

    public function toArray()
    {
        return [
            'voto' => $this->voto,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'data_voto' => $this->data_voto,
            'film' => $this->film
        ];
    }


}