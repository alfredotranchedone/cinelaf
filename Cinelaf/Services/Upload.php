<?php
/**
 * Created by alfredo
 * Date: 2020-03-16
 * Time: 21:29
 */

namespace Cinelaf\Services;


use Cinelaf\Models\Film;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class Upload
{


    /**
     * @param Request $request
     *
     * @return string
     * @throws \Exception
     */
    public function locandina(Request $request)
    {

        $keyLocandina = 'locandina';
        $path = 'public/locandine';
        $fileNameToStore = null;
        try {

            if ($request->hasFile($keyLocandina)) {


                $file = $request->file($keyLocandina);
                $fileNameToStore = $filename = auth()->id() . '-' . md5('loc-' . time()) . '.' . $file->getClientOriginalExtension();

                $file->storeAs($path, $fileNameToStore);

            }

        } catch (\Exception $e) {
            logger()->error('Errore caricamento locandina');
            throw $e;
        }

        return $fileNameToStore;

    }


    /**
     * @param Request $request
     * @param Film   $film
     *
     * @return mixed|string
     * @throws \Exception
     */
    public function updateLocandina(Request $request, Film $film)
    {

        $locandina = $film->locandina;

        try {

            /* Se caricata una nuova locandina, elimina la precedente */
            if($request->hasFile('locandina')){

                // Elimina attuale
                $this->removeLocandina($film);

                // Carica File
                return $this->locandina($request);

            }

        } catch (\Exception $e) {
            logger()->error('Errore aggiornamento locandina');
            throw $e;
        }

        return $locandina;

    }


    /**
     * @param Film $film
     *
     * @return bool
     * @throws \Exception
     */
    public function removeLocandina(Film $film)
    {

        try {

            $filename = $film->locandina ?? 'nonexistent';
            $path = storage_path('app/public/locandine/'. $filename);

            if( file_exists($path))
                File::delete($path);

            return true;

        } catch (\Exception $e) {
            logger()->error('Errore eliminazione locandina');
            throw $e;
        }

    }


}