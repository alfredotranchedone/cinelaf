<?php
/**
 * Created by alfredo
 * Date: 2020-03-15
 * Time: 23:24
 */

namespace Cinelaf\Controllers\User;


use Cinelaf\Repositories\Registi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class RegistiController extends BaseController
{



    public function post_create(Request $request, Registi $registiRepo)
    {


        $this->validate($request,[
            'nome' => 'required|min:2|max:150',
            'cognome' => 'required|min:2|max:150',
        ]);

        DB::beginTransaction();

        try {

            /* Check esistenza */
            if($registiRepo->exists($request->nome, $request->cognome)){
                return redirect()
                    ->back()
                    ->with('warning','Attenzione! Regista già presente.');
            }

            /* Salva */
            $registiRepo->save($request->nome, $request->cognome);

            DB::commit();

            return redirect()
                ->back()
                ->with('success','Regista creato!.');


        } catch (\Exception $e) {

            DB::rollBack();

            logger()->error('Errore nella creazione del Regista',['msg' => $e->getMessage()]);

            return redirect()
                ->back()
                ->with('danger','Errore nella creazione del Movie');

        }




    }
    
}