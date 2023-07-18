<?php

namespace App\Listeners;

use App\Events\SaveProduct1688ToDbEvent;
use App\Jobs\SaveProductToDbElimJob;
use App\Services\Signature;
use App\Services\Test;

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
        $test = new Test();
        $kq = $test->test($data);
        dd($kq);
//        dispatch(new SaveProductToDbElimJob($data));

    }

}
