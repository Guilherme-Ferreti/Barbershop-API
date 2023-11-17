<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Resources\CustomerResource;

class CustomerController extends Controller
{
    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }
}
