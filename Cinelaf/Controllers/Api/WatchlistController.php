<?php
/**
 * Created by alfredo
 * Date: 2020-03-26
 * Time: 01:03
 */

namespace Cinelaf\Controllers\Api;


use Cinelaf\Logger\Logger;
use Cinelaf\Models\Rating;
use Cinelaf\Repositories\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WatchlistController extends BaseApiController
{


    public function get_my_list(Watchlist $watchlistRepo)
    {

        try {

            $data = $watchlistRepo->get();
            $data = $data->map(function ($item){
                return $item = [
                    'film_id' => $item->film_id
                ];
            });

            return $this->success_api_response('watchlist/get', $data);

        } catch (\Exception $e){
            Logger::error($e);
            return $this->error_api_response('Impossibile caricare i dati');
        }

    }


    public function post_add(Request $request, Watchlist $watchlistRepo)
    {

        $validator = Validator::make($request->all(),[
            'filmId' => 'required|integer',
        ]);

        if($validator->fails())
            return $this->error_api_response('Dati mancanti');

        $film_id = $request->filmId;

        try {

            $watchlistRepo->add($film_id);
            $response = [
                'filmId' => $film_id,
            ];

            return $this->success_api_response('watchlist/add',$response);

        } catch (\Exception $e){
            Logger::error($e);
            return $this->error_api_response('Impossibile aggiungere alla Watchlist');
        }

    }


    public function post_remove(Request $request, Watchlist $watchlistRepo)
    {
        $validator = Validator::make($request->all(),[
            'filmId' => 'required|integer',
        ]);

        if($validator->fails())
            return $this->error_api_response('Dati mancanti');

        $film_id = $request->filmId;

        try {

            $watchlistRepo->remove($film_id);
            $response = [
                'filmId' => $film_id,
            ];


            return $this->success_api_response('watchlist/remove',$response);

        } catch (\Exception $e){
            Logger::error($e);
            return $this->error_api_response('Impossibile rimuovere dalla Watchlist');
        }
    }

}