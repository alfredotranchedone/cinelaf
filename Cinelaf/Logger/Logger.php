<?php
/**
 * Created by alfredo
 * Date: 2020-03-27
 * Time: 15:27
 */

namespace Cinelaf\Logger;


use Illuminate\Support\Facades\Log;

class Logger
{

    public static function error(\Exception $exception, string $tagOrShortMessage = null)
    {
        Log::error("$tagOrShortMessage : ".$exception->getMessage(),[
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ]);
    }

}