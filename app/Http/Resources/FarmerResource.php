<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FarmerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'cooperative_id' => $this->cooperative_id,
            'enrollment_place' => $this->enrollment_place,
            'phone_number' => $this->phone_number,
            'identity_proof' => $this->identity_proof,
            'country' => $this->country,
            'province' => $this->province,
            'district' => $this->district,
            'commune' => $this->commune,
            'village' => $this->village,
            'lng' => $this->lng,
            'lat' => $this->lat,
            'proof_no' => $this->proof_no,
            'gender' => $this->gender,
            'dob' => $this->dob,
            'farmer_code' => $this->farmer_code,
            'is_online' => $this->is_online,
            'srp_certification' => $this->srp_certification,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
