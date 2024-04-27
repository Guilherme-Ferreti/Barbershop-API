<?php

declare(strict_types=1);

namespace App\Api\Admin\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'customerName' => $this->customer_name,
            'scheduledTo'  => format_date($this->scheduled_to, 'Y-m-d H:i'),
            'isPending'    => $this->isPending(),
            'createdAt'    => format_date($this->created_at),
            'updatedAt'    => format_date($this->updated_at),

            'customer' => $this->when($this->customer, fn () => [
                'id'          => $this->customer_id,
                'name'        => $this->customer->name,
                'phoneNumber' => $this->customer->phone_number,
            ], null),

            'barber' => [
                'id'   => $this->barber_id,
                'name' => $this->barber->name,
            ],
        ];
    }
}
