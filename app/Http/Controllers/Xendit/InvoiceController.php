<?php

namespace App\Http\Controllers\Xendit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Xendit\Xendit;
use Xendit\Invoice;

class InvoiceController extends Controller
{
    public function __construct()
    {
        Xendit::setApiKey(env('XENDIT_SECRET'));
    }
    
    public function createInvoice(Request $r)
    {
        $params = [
            'external_id' => 'demo_147580196270',
            'payer_email' => 'sample_email@xendit.co',
            'description' => 'Trip to Bali',
            'amount' => 32000
        ];

        $createInvoice = Invoice::create($params);
        return response()->json(['data'=>$createInvoice]);
    }

    public function getInvoice(Request $r)
    {
        $id = $r->query('id');
        return response()->json(['data' => Invoice::retrieve($id)]);
    }

    public function getAllInvoice(Request $r)
    {
        return response()->json([
            'data' => Invoice::retrieveAll()
        ]);
    }

    public function expireInvoice(Request $r)
    {
        $id = $r->id;
        return response()->json(['success' => true, 'data ' => Invoice::expireInvoice($id)]);
    }

    public function handleCallback(Request $r)
    {
        $params = $r->all();
        # TODO : handle request params from https://developers.xendit.co/api-reference/#invoice-callback

    }
}
