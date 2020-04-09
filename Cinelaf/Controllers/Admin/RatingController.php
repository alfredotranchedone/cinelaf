<?php
/**
 * Created by alfredo
 * Date: 2020-03-25
 * Time: 20:36
 */

namespace Cinelaf\Controllers\Admin;


use Cinelaf\Repositories\Rating;
use Cinelaf\Traits\Redirectable;
use Illuminate\Support\Facades\Log;

class RatingController extends BaseController
{

    use Redirectable;

    public function get_update_batch(Rating $ratingRepo)
    {

        try {

            $count = $ratingRepo->updateBatchValutazione();

            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Valutazioni aggiornate correttamente: ' . $count);

        } catch (\Exception $e){

            Log::error('Errore aggiornamento valutazioni: ' . $e->getMessage(),[
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return $this->errorRedirect('admin.dashboard');

        }

    }

}