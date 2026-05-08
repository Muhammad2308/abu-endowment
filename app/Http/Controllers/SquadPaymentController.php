<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SquadPaymentController extends Controller
{
    /**
     * Initiate a Squad payment and return a checkout URL.
     */
    public function initiate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'email' => 'required|email',
            'callback_url' => 'nullable|url',
            'customer_name' => 'nullable|string|max:255',
        ]);

        $squadKey = env('SQUAD_SECRET_KEY', 'sk_04fd9b39368320cfc4ab4c10a963d035e44d37be');
        $callbackUrl = $request->input('callback_url', url('/?payment_status=success'));

        $payload = [
            'amount' => intval($request->input('amount')),
            'email' => $request->input('email'),
            'currency' => 'NGN',
            'initiate_type' => 'inline',
            'transaction_ref' => 'ABU_SQUAD_' . time() . '_' . uniqid(),
            'callback_url' => $callbackUrl,
        ];

        if ($request->filled('customer_name')) {
            $payload['customer_name'] = $request->input('customer_name');
        }

        try {
            $response = Http::withToken($squadKey)
                ->acceptJson()
                ->timeout(30)
                ->post('https://api-d.squadco.com/transaction/initiate', $payload);

            if ($response->failed()) {
                Log::error('Squad payment initiation failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'payload' => $payload,
                ]);

                return response()->json([
                    'message' => 'Unable to initiate payment. Please try again later.',
                    'details' => $response->json(),
                ], 500);
            }

            $data = $response->json();
            $checkoutUrl = data_get($data, 'data.checkout_url');

            if (!$checkoutUrl) {
                Log::error('Squad payment initiation missing checkout URL', [
                    'response' => $data,
                    'payload' => $payload,
                ]);

                return response()->json([
                    'message' => 'Payment provider did not return a checkout URL.',
                    'details' => $data,
                ], 500);
            }

            return response()->json([
                'checkout_url' => $checkoutUrl,
                'transaction_ref' => data_get($data, 'data.transaction_ref'),
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            Log::error('Squad payment initiation exception', [
                'message' => $e->getMessage(),
                'payload' => $payload,
            ]);

            return response()->json([
                'message' => 'Unexpected error while initiating Squad payment.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
