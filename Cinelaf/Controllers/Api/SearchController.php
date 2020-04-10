<?php
/**
 * Created by alfredo
 * Date: 2020-04-10
 * Time: 01:46
 */

namespace Cinelaf\Controllers\Api;


use Cinelaf\Models\Film;
use Cinelaf\Traits\ApiResponse;
use Illuminate\Http\Request;

class SearchController extends BaseApiController
{

    use ApiResponse;



    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function post_search(Request $request)
    {

        $data = Film::search($request->q)->get()->load('regista:registi.id,nome,cognome','user:id,name');

        return $this->success_api_response('search',$data);

    }

}