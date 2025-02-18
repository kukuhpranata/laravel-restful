<?php

namespace App\Http\Resources;

use App\Helpers\CryptHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $cryptor = new CryptHelper();
        return [
            'id' => $cryptor->encrypt($this->id),
            'email' => $this->email,
            'name' => $this->name
        ];
    }
}
