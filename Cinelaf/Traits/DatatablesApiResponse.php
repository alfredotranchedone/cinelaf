<?php
/**
 * Created by alfredo
 * Date: 2020-03-17
 * Time: 09:40
 */

namespace Cinelaf\Traits;


use Illuminate\Http\Request;

trait DatatablesApiResponse
{


    protected function datatable_success_api_response(Request $request, $recordsTotal, $data=[])
    {

        if(empty($this->version))
            throw new \Exception('$this->version non specificato. Definiscilo nella Classe che usa il Trait: '. __CLASS__);

        $output = [
            'draw' => (int) $request->draw,
            'data' => $data,
            'recordsFiltered' => $recordsTotal,
            'recordsTotal' => $recordsTotal
        ];

        return response()->json($output,200);

    }




}