<?php

declare(strict_types=1);

namespace App\Domain\Public\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'customerName' => $this->customer_name,
            'scheduledTo'  => formatDate($this->scheduled_to),
            'isPending'    => $this->isPending(),
            'createdAt'    => formatDate($this->created_at),
            'updatedAt'    => formatDate($this->updated_at),

            'customer' => $this->whenLoaded('customer', [
                'id'   => $this->customer_id,
                'name' => $this->customer->name,
            ]),
        ];
    }
}
