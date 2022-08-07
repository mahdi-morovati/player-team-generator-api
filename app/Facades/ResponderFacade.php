<?php


namespace App\Facades;


use App\Services\Responses\ApiResponder;

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
 */
class ResponderFacade extends BaseFacade
{
    const key = ApiResponder::class;
}
