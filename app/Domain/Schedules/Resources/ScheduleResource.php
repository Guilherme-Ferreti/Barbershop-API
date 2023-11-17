<?php

declare(strict_types=1);

namespace App\Domain\Schedules\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'customerName' => $this->customer_name,
            'scheduledTo'  => $this->scheduled_to,
            'createdAt'    => $this->created_at,
            'updatedAt'    => $this->updated_at,

            'customer' => $this->whenLoaded('customer', [
                'id'   => $this->customer_id,
                'name' => $this->customer->name,
            ]),
        ];
    }
}
