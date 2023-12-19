<?php

declare(strict_types=1);

namespace App\Domain\Public\Controllers;

use App\Api\Public\Resources\ShowCustomerResource;
use App\Http\Controllers\Controller;
use Domain\Customers\Models\Customer;

class ShowCustomerController extends Controller
{
    public function show(Customer $customer)
    {
        $customer->load('pendingSchedule');

        return new ShowCustomerResource($customer);
    }
}
