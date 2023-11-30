<?php

declare(strict_types=1);

namespace App\Domain\Public\Resources;

use App\Domain\Common\Models\Schedule;
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

            'schedules' => $this->schedules->map(fn (Schedule $schedule) => [
                'id'           => $schedule->id,
                'customerName' => $schedule->customer_name,
                'scheduledTo'  => formatDate($schedule->scheduled_to),
            ]),
        ];
    }
}
