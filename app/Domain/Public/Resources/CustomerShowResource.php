<?php

declare(strict_types=1);

namespace App\Domain\Public\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerShowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'phoneNumber' => $this->phone_number,

            'pendingSchedule' => $this->when($this->pendingSchedule, fn () => [
                'id'           => $this->pendingSchedule->id,
                'customerName' => $this->pendingSchedule->customer_name,
                'isPending'    => $this->pendingSchedule->isPending(),
                'scheduledTo'  => formatDate($this->pendingSchedule->scheduled_to),
            ], null),
        ];
    }
}
