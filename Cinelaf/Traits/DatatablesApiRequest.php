<?php
/**
 * Created by alfredo
 * Date: 2020-03-19
 * Time: 12:59
 */

namespace Cinelaf\Traits;


use Illuminate\Http\Request;

trait DatatablesApiRequest
{


    /**
     * @param Request $request
     *
     * @return array
     */
    public function datatable_process_request(Request $request)
    {

        $q = $request->query('search')['value'];
        $limit = $request->query('length');
        $offset = $request->query('start');
        $order = $request->query('order');

        return [
            'search' => $q,
            'limit' => $limit,
            'offset' => $offset,
            'order' => $order
        ];

    }

}