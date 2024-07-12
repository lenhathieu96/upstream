<?php

namespace App\Listeners;

use App\Events\ProcurementCreated;
use App\Services\ProcurementDetailService;
use App\Services\ProcurementOtherCostService;
use Illuminate\Events\Dispatcher;

class ProcurementEventListener
{
    public function onProcurementCreated(ProcurementCreated $event)
    {
        $procurement = $event->procurement;
        $attribute = $event->attribute;

        (new ProcurementDetailService())->createProcurementDetail($procurement, $attribute);
        if (!empty($attribute['other_costs'])) {
            (new ProcurementOtherCostService())->addProcurementOtherCosts($procurement, $attribute);
        }
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            [
                ProcurementCreated::class,
            ],
            'App\Listeners\ProcurementEventListener@onProcurementCreated'
        );
    }
}