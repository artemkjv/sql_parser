<?php

namespace App\Http\Resources\Dump;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DumpCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $collection = $this->collection->map(function ($item) {
            return DumpResource::make($item)->resolve();
        })->toArray();
        $data = $this->resource->toArray();
        $data['data'] = $collection;
        return $data;
    }
}
