<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TelegramWebhookHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function telegram(Request $request): JsonResponse
    {
        $update = $request->all();

        // Verify the update has valid structure
        if (! isset($update['message']) && ! isset($update['callback_query'])) {
            return response()->json(['status' => 'ignored']);
        }

        app(TelegramWebhookHandler::class)->handle($update);

        return response()->json(['status' => 'ok']);
    }
}

