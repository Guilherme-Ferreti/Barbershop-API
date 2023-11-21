<?php

declare(strict_types=1);

namespace App\Domain\Customers\Controllers;

use App\Domain\Customers\Models\Customer;
use App\Domain\Customers\Resources\CustomerResource;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }
}
