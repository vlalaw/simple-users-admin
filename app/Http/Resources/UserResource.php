<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @param $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'role' => $this->role->name,
            'id' => $this->id,
        ];
    }
}
