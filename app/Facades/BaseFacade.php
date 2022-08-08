<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

abstract class BaseFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return static::key;
    }

}
