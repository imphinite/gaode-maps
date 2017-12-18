<?php

namespace GaodeMaps;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\Html\HtmlBuilder
 */
class GaodeMapsFacade extends Facade 
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() 
    {
         return 'imphinite-gaode-maps'; 
    }
}