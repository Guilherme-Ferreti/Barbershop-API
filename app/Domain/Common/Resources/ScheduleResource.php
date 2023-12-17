<?php

declare(strict_types=1);

namespace App\Domain\Common\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'customerName' => $this->customer_name,
            'scheduledTo'  => formatDate($this->scheduled_to, 'Y-m-d H:i'),
            'isPending'    => $this->isPending(),
            'createdAt'    => formatDate($this->created_at),
            'updatedAt'    => formatDate($this->updated_at),

            $this->mergeWhen($request->routeIs('public.schedules.store', 'admin.schedules.store'), fn () => [
                'customer' => $this->when($this->customer, fn () => [
                    'id'          => $this->customer_id,
                    'name'        => $this->customer->name,
                    'phoneNumber' => $this->customer->phone_number,
                ], null),
            ]),
        ];
    }
}
