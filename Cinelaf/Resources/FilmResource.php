<?php

namespace Cinelaf\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FilmResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'titolo' => $this->titolo,
            'anno' => $this->anno,
            'locandina' => $this->locandina
        ];
    }
}
