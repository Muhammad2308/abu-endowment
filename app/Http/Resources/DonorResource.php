<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'full_name' => $this->full_name,
            'surname' => $this->surname,
            'other_name' => $this->other_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'reg_number' => $this->reg_number,
            'registration_number' => $this->reg_number, // For compatibility
            'entry_year' => $this->entry_year,
            'graduation_year' => $this->graduation_year,
            'address' => $this->address,
            'state' => $this->state,
            'lga' => $this->lga,
            'nationality' => $this->nationality,
            'donor_type' => $this->donor_type,
            'ranking' => $this->ranking,
            'faculty_name' => $this->whenLoaded('faculty', function() {
                return $this->faculty->current_name;
            }),
            'department_name' => $this->whenLoaded('department', function() {
                return $this->department->current_name;
            }),
            'faculty' => $this->whenLoaded('faculty', function() {
                return [
                    'id' => $this->faculty->id,
                    'name' => $this->faculty->current_name,
                    'code' => $this->faculty->code ?? null
                ];
            }),
            'department' => $this->whenLoaded('department', function() {
                return [
                    'id' => $this->department->id,
                    'name' => $this->department->current_name,
                    'code' => $this->department->code ?? null
                ];
            })
        ];
    }
} 