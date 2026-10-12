<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Braintree_Gateway;

class PaypalController extends Controller
{
    public function processPayment(Request $request)
    {
        $order = new OrderController;

        $gateway = new Braintree_Gateway([
            'environment' => config('drikana.payments.braintree.environment'),
            'merchantId' => config('drikana.payments.braintree.merchant_id'),
            'publicKey' => config('drikana.payments.braintree.public_key'),
            'privateKey' => config('drikana.payments.braintree.private_key')
        ]);

        $response = $gateway->transaction()->sale([
            'amount' => $request->grand_total,
            'paymentMethodNonce' => $request->nonce,
            'options' => [
                'submitForSettlement' => true
            ]
        ]);

        if ($response->success) {
            return $order->processOrder($request);
        } elseif ($response->transaction) {
            return response()->json([
                'success' => false,
                'message' => 'The order was not completed because there was an error processing the transaction'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'The order was not completed becuase the paymeent is invalid'
            ]);
        }

    }
}
