<?php
/**
 * Created by alfredo
 * Date: 2020-04-14
 * Time: 09:34
 */

namespace Cinelaf\Models\DataMapper;


use Illuminate\Contracts\Support\Arrayable;

class UserNotRated implements Arrayable
{

    public $id;
    public $titolo;
    public $anno;
    public $locandina;
    public $user_id;
    public $valutazione;
    public $created_at;
    public $updated_at;



    public function __construct($data)
    {

        $this->id = $data->id;
        $this->titolo = $data->titolo;
        $this->anno = $data->anno;
        $this->locandina = $data->locandina;
        $this->user_id = $data->user_id;
        $this->valutazione = $data->valutazione;
        $this->created_at = $data->created_at;
        $this->updated_at = $data->updated_at;

    }



    public function toArray()
    {
        return [
            'id' => $this->id,
            'titolo' => $this->titolo,
            'anno' => $this->anno,
            'locandina' => $this->locandina,
            'user_id' => $this->user_id,
            'valutazione' => $this->valutazione,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }


}