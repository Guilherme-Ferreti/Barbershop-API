<?php

declare(strict_types=1);

namespace Domain\Schedules\Policies;

use Domain\Barbers\Models\Barber;
use Domain\Customers\Models\Customer;
use Domain\Schedules\Models\Schedule;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\User as Authenticable;

class SchedulePolicy
{
    public function before(Authenticable $user): ?bool
    {
        if ($user instanceof Barber && $user->is_admin) {
            return true;
        }

        return null;
    }

    public function destroy(Customer $customer, Schedule $schedule): Response
    {
        return $schedule->customer_id === $customer->id
            ? Response::allow()
            : Response::denyAsNotFound(__('Schedule not found.'));
    }
}
