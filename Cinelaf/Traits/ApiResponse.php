<?php
/**
 * Created by alfredo
 * Date: 2020-03-16
 * Time: 14:49
 */

namespace Cinelaf\Traits;



trait ApiResponse
{


    protected function success_api_response($type=null, $data=[], $meta=null)
    {

        if(empty($this->version))
            throw new \Exception('$this->version non specificato. Definiscilo nella Classe che usa il Trait: '. __CLASS__);

        $output = [
            'type' => $type,
            'data' => $data,
        ];

        if($meta)
            $output['meta'] = $meta;

        $output['version'] = $this->version;

        $output['timestamp'] = date('c');

        return response()->json($output,200);

    }



    protected function error_api_response($message,$code=0,$data=[])
    {

        $output = [
            'error' => [
                'code' => $code,
                'message' => $message
            ],
        ];

        if($data)
            $output['error']['data'] = $data;

        return response()->json($output);

    }


}
