<?php
/**
 * Created by alfredo
 * Date: 2020-03-16
 * Time: 00:43
 */

namespace Cinelaf\Controllers\Api;

use Cinelaf\Repositories\Film as FilmRepo;
use Cinelaf\Repositories\Movie as MovieRepo;
use Cinelaf\Traits\ApiResponse;
use Cinelaf\Traits\DatatablesApiRequest;
use Cinelaf\Traits\DatatablesApiResponse;
use Illuminate\Http\Request;

class FilmController extends BaseApiController
{

    use ApiResponse, DatatablesApiResponse, DatatablesApiRequest;

    protected $version = 1;

    /**
     * @param Request   $request
     * @param FilmRepo $filmRepo
     *
     * @return \Illuminate\Support\Collection
     */
    public function get_all(Request $request, FilmRepo $filmRepo)
    {

        $q = $request->q;
        $format = $request->query('format') ?? 'full';

        return $filmRepo->get($format,5,$q,true,true);

    }


    /**
     * @param Request   $request
     * @param MovieRepo $movieRepo
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function get_dt_all(Request $request, MovieRepo $movieRepo)
    {

        $dt_request = $this->datatable_process_request($request);
        $q = $dt_request['search'];
        $limit = $dt_request['limit'];
        $offset = $dt_request['offset'];
        $order = $dt_request['order'];

        $orderColumn = $order[0]['column'];
        $orderDir = $order[0]['dir'];

        $column='titolo';
        switch ($orderColumn){
            case "1":
                $column='titolo';
                break;
            case "2":
                $column='anno';
                break;
            case "3":
                $column='valutazione';
                break;

        }
        $orderBy = "$column $orderDir";

        $data = $movieRepo->filter($q,$offset,$limit,$orderBy);
        $totale = $movieRepo->count();

        return $this->datatable_success_api_response($request, $totale, $data);

    }


    /**
     * @param Request   $request
     * @param MovieRepo $movieRepo
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function get_dt_myratings(Request $request, MovieRepo $movieRepo)
    {

        $dt_request = $this->datatable_process_request($request);
        $q = $dt_request['search'];
        $limit = $dt_request['limit'];
        $offset = $dt_request['offset'];
        $order = $dt_request['order'];

        $orderColumn = $order[0]['column'];
        $orderDir = $order[0]['dir'];

        $column='titolo';
        switch ($orderColumn){
            case "1":
                $column='titolo';
                break;
            case "2":
                $column='voto';
                break;
            case "3":
                $column='ratings.updated_at';
                break;

        }
        $orderBy = "$column $orderDir";

        $data = $movieRepo->filterMyRating($q,$offset,$limit,$orderBy);
        $totale = $movieRepo->myRatingCount();

        return $this->datatable_success_api_response($request, $totale, $data->toArray());

    }



    /**
     * @param Request   $request
     * @param MovieRepo $movieRepo
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function get_dt_mynotrated(Request $request, MovieRepo $movieRepo)
    {

        $dt_request = $this->datatable_process_request($request);
        $q = $dt_request['search'];
        $limit = $dt_request['limit'];
        $offset = $dt_request['offset'];
        $order = $dt_request['order'];

        $orderColumn = $order[0]['column'];
        $orderDir = $order[0]['dir'];

        $column='titolo';
        switch ($orderColumn){
            case "1":
                $column='titolo';
                break;
            case "2":
                $column='anno';
                break;
            case "3":
                $column='valutazione';
                break;

        }
        $orderBy = "$column $orderDir";

        $data = $movieRepo->filterMyNotRated($q,$offset,$limit,$orderBy);
        $totale = $movieRepo->countMyNotRated();

        return $this->datatable_success_api_response($request, $totale, $data);

    }





    /**
     * @param Request   $request
     * @param MovieRepo $movieRepo
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function get_dt_noquorum(Request $request, MovieRepo $movieRepo)
    {

        $dt_request = $this->datatable_process_request($request);
        $q = $dt_request['search'];
        $limit = $dt_request['limit'];
        $offset = $dt_request['offset'];
        $order = $dt_request['order'];

        $orderColumn = $order[0]['column'];
        $orderDir = $order[0]['dir'];

        $column='titolo';
        switch ($orderColumn){
            case "1":
                $column='titolo';
                break;
            case "2":
                $column='anno';
                break;
            case "3":
                $column='media';
                break;

        }
        $orderBy = "$column $orderDir";

        $data = $movieRepo->filterNoQuorum($q,$offset,$limit,$orderBy);
        $totale = $movieRepo->countNoQuorum();

        return $this->datatable_success_api_response($request, $totale, $data);

    }


}