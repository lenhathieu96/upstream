<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Services\Common\UniqueCodeService;

class BookingService
{
    public function store(array $attribute): Booking
    {
        $vehicle = Vehicle::find($attribute['vehicle_id']);

        $booking = Booking::create([
            'vehicle_id' => $vehicle->id,
            'booking_code' => UniqueCodeService::generate('BK'),
            'booking_date' => $attribute['booking_date'] ?? now(),
            'status' => $attribute['booking_status'] ?? 'pending'
        ]);

        if ($booking) {
            $vehicle->update([
                'status' => 'in_process'
            ]);
        }
        return $booking;
    }
}