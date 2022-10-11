<?php

namespace App\Listeners;

use App\Events\TodoCreatedOrUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserOnTodoCreatedOrUpdated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TodoCreatedOrUpdated  $event
     * @return void
     */
    public function handle(TodoCreatedOrUpdated $event)
    {
        //
    }
}
