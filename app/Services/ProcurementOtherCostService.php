<?php

namespace App\Services;

use App\Models\Procurement;
use App\Models\ProcurementOtherCost;

class ProcurementOtherCostService
{
    public function store(Procurement $procurement, array $attribute)
    {
        $subTotal = $attribute['quantity'] * $attribute['rate'];
        $cost = ProcurementOtherCost::create([
            'procurement_id' => $procurement->id,
            'item' => $attribute['item'],
            'quantity' => $attribute['quantity'],
            'rate' => $attribute['rate'],
            'sub_total' => $subTotal,
        ]);
        $procurement->total_amount += $subTotal;
        $procurement->save();
        return $cost;
    }

    public function addProcurementOtherCosts(Procurement $procurement, array $attribute)
    {
        foreach ($attribute['other_costs'] as $item) {
            $this->store($procurement, $item);
        }
    }
}