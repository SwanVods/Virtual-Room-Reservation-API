<?php

namespace App\Http\Controllers\Xendit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Xendit\Xendit;
use Xendit\Customers;

class CustomerController extends Controller
{
    public function createCustomer(Request $r)
    {
        Xendit::setApiKey(env('XENDIT_SECRET'));
        $customerParams = [
            'reference_id' => '' . time(),
            'given_names' => $r->name,
            'email' => $r->email,
            'mobile_number' => $r->phone,
            'description' => $r->description,
            'addresses' => [
                [
                    'country' => 'ID',
                    'street_line1' => 'Jl. 123',
                    'street_line2' => 'Jl. 456',
                    'city' => 'Jakarta Selatan',
                    'province' => 'DKI Jakarta',
                    'state' => '-',
                    'postal_code' => '12345'
                ]
            ],
            'metadata' => [
                'meta' => 'data'
            ]
        ];
        
        $customer = Customers::createCustomer($customerParams);

        return response()->json([
            'data' => $customer
        ]);
    }
}
