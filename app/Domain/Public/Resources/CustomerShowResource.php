<?php

declare(strict_types=1);

namespace App\Domain\Public\Resources;

use App\Domain\Common\Resources\ScheduleResource;
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

            'pendingSchedule' => new ScheduleResource($this->pendingSchedule),
        ];
    }
}
