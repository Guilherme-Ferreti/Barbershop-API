<?php

declare(strict_types=1);

namespace App\Api\Public\Controllers;

use App\Api\Public\Resources\CustomerResource;
use Modules\Auth\Models\Customer;
use Support\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function show(Customer $customer)
    {
        $customer->load('pendingAppointment');

        return new CustomerResource($customer);
    }
}
