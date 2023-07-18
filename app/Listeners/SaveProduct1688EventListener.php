<?php

namespace App\Listeners;

use App\Events\SaveProduct1688ToDbEvent;
use App\Jobs\SaveProductToDbElimJob;
use App\Services\Signature;
class SaveProduct1688EventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(SaveProduct1688ToDbEvent $event): void
    {
        $data = $event->result;
        dispatch(new SaveProductToDbElimJob($data));

    }

}
