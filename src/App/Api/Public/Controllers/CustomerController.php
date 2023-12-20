<?php

declare(strict_types=1);

namespace App\Api\Public\Controllers;

use App\Api\Public\Resources\CustomerResource;
use Domain\Customers\Models\Customer;
use Support\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function show(Customer $customer)
    {
        $customer->load('pendingSchedule');

        return new CustomerResource($customer);
    }
}
