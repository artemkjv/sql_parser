<?php

namespace App\Http\Resources\Dump;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DumpResource extends JsonResource
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
            'filename' => $this->filename,
            'created_at' => $this->created_at,
        ];
    }
}
