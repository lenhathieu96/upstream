<?php

namespace App\Services;

use App\Events\ProcurementCreated;
use App\Models\Procurement;
use App\Services\Common\UniqueCodeService;

class ProcurementService
{
    public function store(array $attribute)
    {
        $booking = (new BookingService())->store($attribute);
        if (empty($booking)) {
            return null;
        }

        $vehicle = $booking->vehicle;

        $procurement = Procurement::create([
            'transaction_date' => now(),
            'procurement_code' => UniqueCodeService::generate('PRC'),
            'booking_id' => $booking->id,
            'warehouse_id' => $attribute['warehouse_id'],
            'total_amount' => 0,
            'lat' => $attribute['lat'] ?? null,
            'lng' => $attribute['lng'] ?? null,
            'staff_id' => request()->user('sanctum')->staff->id,
        ]);
        if ($procurement) {
            ProcurementCreated::dispatch($procurement, $attribute);

            return $procurement;
        }

        return null;
    }
}