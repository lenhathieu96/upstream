<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use function Symfony\Component\Translation\t;

class DistributionDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'category_id' => $this->category_id,
            'category_name' => $this->category_name,
            'quantity' => $this->quantity,
            'price_per_unit' => $this->price_per_unit,
            'sub_total' => $this->sub_total,
            'unit' => $this->unit,
        ];
    }
}
