<?php

namespace Cinelaf\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RegistiSelectResource extends JsonResource
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
            'text' => "$this->nome $this->cognome"
        ];
    }
}
