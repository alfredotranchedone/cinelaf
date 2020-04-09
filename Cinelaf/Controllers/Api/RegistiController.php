<?php
/**
 * Created by alfredo
 * Date: 2020-03-16
 * Time: 00:43
 */

namespace Cinelaf\Controllers\Api;

use Cinelaf\Models\Regista;
use Cinelaf\Repositories\Registi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class RegistiController extends BaseApiController
{


    public function get_all(Request $request, Registi $registiRepo)
    {
        $q = $request->q;
        $format = $request->query('format');
        return $registiRepo->get($format,15,$q,false);
    }


    public function post_create(Request $request, Registi $registiRepo)
    {

        $validator = Validator::make($request->all(),[
            'nome' => 'required|min:2|max:150',
            'cognome' => 'required|min:2|max:150',
        ]);

        if($validator->fails())
            return $this->error_api_response('I campi sono obbligatori');


        DB::beginTransaction();

        try {

            /* Check esistenza */
            if($registiRepo->exists($request->nome, $request->cognome))
                return $this->error_api_response('Regista già presente');

            /* Salva */
            $newRegista = $registiRepo->save($request->nome, $request->cognome);

            DB::commit();

            return $this->success_api_response('regista/new', $newRegista);


        } catch (\Exception $e) {

            DB::rollBack();

            logger()->error('Errore nella creazione del Regista',['msg' => $e->getMessage()]);

            return $this->error_api_response('Errore nella creazione del Regista');

        }

    }

}