<?php

namespace App\Observers;

use App\Models\Router;
use PhpParser\Node\Stmt\TryCatch;

class RouterObserver
{

    public function creating (Router $router)
    {
        try {
            $identity = collect(array_values($router->getRouterOS(1)->query('/system/identity/print')->read())[0]);
            if($identity->has('name')){
                $router->identity = $identity->get('name');
            }

        }catch (\Exception $e){

        }
    }

    public function updating(Router $router)
    {
        try {
            $identity = collect(array_values($router->getRouterOS(1)->query('/system/identity/print')->read())[0]);
            if($identity->has('name')){
                $router->identity = $identity->get('name');
            }

        }catch (\Exception $e){

        }
    }

    public function deleted(Router $router)
    {
        //
    }

    public function restored(Router $router)
    {
        //
    }

    public function forceDeleted(Router $router)
    {
        //
    }
}
