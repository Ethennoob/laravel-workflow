<?php

namespace Lingxiang\Workflow;

use Illuminate\Support\Facades\Facade;

class TaskFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'task';
    }
}
