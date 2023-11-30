<?php

declare(strict_types=1);

namespace App\Domain\Authenticated\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'customerName' => $this->customer_name,
            'scheduledTo'  => formatDate($this->scheduled_to),
            'createdAt'    => formatDate($this->created_at),
            'isPending'    => $this->isPending(),
        ];
    }
}
