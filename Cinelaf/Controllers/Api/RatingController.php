<?php
/**
 * Created by alfredo
 * Date: 2020-03-26
 * Time: 01:03
 */

namespace Cinelaf\Controllers\Api;


use Cinelaf\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RatingController extends BaseApiController
{


    /**
     * @param Request                      $request
     * @param \Cinelaf\Repositories\Rating $ratingRepo
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function post_vote(Request $request, \Cinelaf\Repositories\Rating $ratingRepo)
    {

        $validator = Validator::make($request->all(),[
            'filmId' => 'required|integer',
            'voto' => 'required|in:0.5,1,1.5,2,2.5,3,3.5,4,4.5,5',
        ]);

        if($validator->fails())
            return $this->error_api_response('Controlla i dati del voto');

        $film_id = $request->filmId;
        $voto = $request->voto;

        try {

            $ratingRepo->save($film_id,$voto);

            return $this->success_api_response('vote',['film' => $film_id, 'voto' => $voto]);

        } catch (\Exception $e){
            Log::error('Si è verificato un errore: ' . $e->getMessage(),[
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return $this->error_api_response('Controlla i dati del voto');
        }

    }

}