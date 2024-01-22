<?php

declare(strict_types=1);

namespace App\Api\Public\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'phoneNumber' => $this->phone_number,

            'pendingAppointment' => $this->when($this->pendingAppointment, fn () => [
                'id'           => $this->pendingAppointment->id,
                'customerName' => $this->pendingAppointment->customer_name,
                'scheduledTo'  => format_date($this->pendingAppointment->scheduled_to, 'Y-m-d H:i'),
                'isPending'    => $this->pendingAppointment->isPending(),
                'createdAt'    => format_date($this->pendingAppointment->created_at),
                'updatedAt'    => format_date($this->pendingAppointment->updated_at),
            ], null),
        ];
    }
}
