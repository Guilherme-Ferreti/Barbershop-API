<?php

declare(strict_types=1);

namespace App\Api\Public\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowCustomerResource extends JsonResource
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
                'scheduledTo'  => format_date($this->pendingSchedule->scheduled_to, 'Y-m-d H:i'),
                'isPending'    => $this->pendingSchedule->isPending(),
                'createdAt'    => format_date($this->pendingSchedule->created_at),
            ], null),
        ];
    }
}
