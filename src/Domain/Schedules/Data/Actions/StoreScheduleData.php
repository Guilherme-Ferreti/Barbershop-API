<?php

declare(strict_types=1);

namespace Domain\Schedules\Data\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

readonly class StoreScheduleData
{
    public function __construct(
        public ?string $customer_phone_number,
        public string $customer_name,
        public Carbon $scheduled_to,
    ) {
    }

    public static function fromRequest(Request $request): static
    {
        return new static(...[
            'customer_phone_number' => $request->input('customerPhoneNumber'),
            'customer_name'         => $request->input('customerName'),
            'scheduled_to'          => $request->date('scheduledTo', 'Y-m-d H:i'),
        ]);
    }
}
