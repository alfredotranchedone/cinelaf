<?php
/**
 * Created by alfredo
 * Date: 2020-03-16
 * Time: 14:50
 */

namespace Cinelaf\Controllers\Api;


use App\Http\Controllers\Controller;
use Cinelaf\Traits\ApiResponse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BaseApiController extends Controller
{

    use ApiResponse;

    protected $version = 1;


    /**
     * @param string $type
     * @param Model|Collection|array $data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function api_response(string $type, $data){

        try {
            return $this->success_api_response($type, $data);
        } catch (\Exception $e) {
            Log::error($e);
            return $this->error_api_response('Eccezione. Controlla i Logs', 900);
        }

    }

}