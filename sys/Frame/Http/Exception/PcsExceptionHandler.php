<?php
namespace Pcs\Frame\Http\Exception;

use Pcs\Network\Response\HttpResponse;

class PcsExceptionHandler
{

    public static function handle(\Exception $e, HttpResponse $response)
    {
        echo 'Exception:' . $e->getMessage() . ' ,in ' . $e->getFile() . ' at line ' . $e->getLine() . "\n";
        self::ErrorResponse($response);
    }

    private static function ErrorResponse($response)
    {
        $response->output('system error');
    }
}