<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgentResource extends JsonResource
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
            'profile_picture' => $this->user->profile_picture,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'username' => $this->user->username,
            'status' => $this->user->status,
            'user_id' => $this->user_id,
            'address' => $this->address,
            'district' => $this->district,
            'upazila' => $this->upazila,
            'nid_front_image' => $this->nid_front_image,
            'nid_back_image' => $this->nid_back_image,
            'account_name' => $this->account_name,
            'account_number' => $this->account_number,
            'bank_name' => $this->bank_name,
            'branch_name' => $this->branch_name,
            'swift_code' => $this->swift_code,
            'routing_number' => $this->routing_number,
            'bkash_number' => $this->bkash_number,
            'rocket_number' => $this->rocket_number,
            'nagad_number' => $this->nagad_number,
           
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
