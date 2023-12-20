<?php

declare(strict_types=1);

namespace App\Api\Admin\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarberResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}
