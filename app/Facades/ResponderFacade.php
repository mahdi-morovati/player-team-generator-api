<?php


namespace App\Facades;


use App\Services\Responses\ApiResponder;
use Illuminate\Http\Response;

/**
 *
 * @method success(string $message)
 * @method created(string $message)
 * @method updated(string $message)
 * @method badRequest(string $message)
 * @method destroyed(string $message)
 * @method notFound(string $message)
 * @method internalError()
 * @method unauthorizedError()
 * @method errorWithValue(string $message, $value, int $statusCode = Response::HTTP_BAD_REQUEST)
 */
class ResponderFacade extends BaseFacade
{
    const key = ApiResponder::class;
}
