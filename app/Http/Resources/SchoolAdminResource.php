<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolAdminResource extends JsonResource
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
            'agent_user_id' => $this->agent_user_id,
            'agent_name' => $this->agent?$this->agent->name: null,
            'agent_username' => $this->agent?$this->agent->username: null,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'username' => $this->user->username,
            'status' => $this->user->status,
            'user_id' => $this->user_id,
            'english_name' => $this->english_name,
            'bangla_name' => $this->bangla_name,
            'short_address' => $this->short_address,
            'full_address' => $this->full_address,
            'bangla_name_front_size' => $this->bangla_name_front_size,
            'english_name_front_size' => $this->english_name_front_size,
            'eiin' => $this->eiin,
            'full_address_front_size' => $this->full_address_front_size,
            'short_address_front_size' => $this->short_address_front_size,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
