<?php

declare(strict_types=1);

namespace App\Domain\Public\Controllers;

use App\Domain\Common\Models\Customer;
use App\Domain\Public\Resources\CustomerShowResource;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function show(Customer $customer)
    {
        $customer->load('pendingSchedule');

        return new CustomerShowResource($customer);
    }
}
