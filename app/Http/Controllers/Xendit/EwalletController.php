<?php

namespace App\Http\Controllers\Xendit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Xendit\Xendit;
use Xendit\EWallets;

class EwalletController extends Controller
{
    public function __construct()
    {
        Xendit::setApiKey(env('XENDIT_SECRET'));
    }
    
    public function createEwalletCharge(Request $r)
    {
        $ewalletChargeParams = [
            'reference_id' => 'test-reference-id',
            'currency' => 'IDR',
            'amount' => 50000,
            'checkout_method' => 'ONE_TIME_PAYMENT',
            'channel_code' => 'ID_SHOPEEPAY',
            'channel_properties' => [
                'success_redirect_url' => 'https://yourwebsite.com/order/123',
            ],
            'metadata' => [
                'meta' => 'data'
            ]
        ];

        $createEWalletCharge = EWallets::createEWalletCharge($ewalletChargeParams);
        return response()->json($createEWalletCharge);
    }

    public function getEwalletChargeStatus($charge_id)
    {
        $getEWalletChargeStatus = EWallets::getEWalletChargeStatus($charge_id);
        return response()->json($getEWalletChargeStatus);
    }
}
