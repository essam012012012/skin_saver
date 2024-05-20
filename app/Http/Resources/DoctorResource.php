<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'doctor_name' => $this -> name,
            'doctor_email' => $this -> email,
            'doctor_phone' => $this -> phone_number,
            'doctor_clinic' => $this -> clinic_address,
            'doctor_schdule' => $this -> schdule
        ];
    }
}
