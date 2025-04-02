<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\User_role;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagerResource extends JsonResource
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
            'user_id' => $this->user_id,
            'profile_picture' => $this->user->profile_picture,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'username' => $this->user->username,
            'status' => $this->user->status,
            'role_tye' => $this->role_type,   
        ];
    }
}
