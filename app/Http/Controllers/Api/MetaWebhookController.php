<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MetaWebhookController extends Controller
{
    /**
     * Handle the incoming Meta Webhook requests.
     */
    public function handle(Request $request)
    {
        // 1. Handle Meta's GET Verification Request
        if ($request->isMethod('get')) {
            // NOTICE: Using underscores because of PHP's query conversion
            $mode = $request->query('hub_mode');
            $token = $request->query('hub_verify_token');
            $challenge = $request->query('hub_challenge');

            $expectedToken = env('META_VERIFY_TOKEN');

            if ($mode === 'subscribe' && $token === $expectedToken) {
                // Must return HTTP 200 and raw challenge string explicitly
                return response($challenge, 200)->header('Content-Type', 'text/plain');
            }

            return response('Forbidden: Token mismatch', 403);
        }

        // 2. Handle Meta's POST Event Data Payload (Actual Webhook data)
        if ($request->isMethod('post')) {
            $payload = $request->all();
            
            // Helpful for debugging: Logs the incoming data payload to your storage logs
            Log::info('Meta Webhook Event Received:', $payload);
            
            // TODO: Add your custom business logic here to process $payload
            
            return response('EVENT_RECEIVED', 200);
        }

        return response('Method Not Allowed', 405);
    }
}
