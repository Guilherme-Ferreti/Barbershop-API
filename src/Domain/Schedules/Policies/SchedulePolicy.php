<?php

declare(strict_types=1);

namespace App\Domain\Common\Policies;

use App\Domain\Common\Models\Customer;
use App\Domain\Common\Models\Schedule;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\User as Authenticable;

class SchedulePolicy
{
    public function before(Authenticable $user): ?bool
    {
        if ($user instanceof User) {
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
