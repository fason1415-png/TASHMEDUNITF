<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PatronageBotWebhookHandler;
use App\Services\TelegramWebhookHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function telegram(Request $request): JsonResponse
    {
        $update = $request->all();

        if (! isset($update['message']) && ! isset($update['callback_query'])) {
            return response()->json(['status' => 'ignored']);
        }

        app(TelegramWebhookHandler::class)->handle($update);

        return response()->json(['status' => 'ok']);
    }

    public function patronageBot(Request $request): JsonResponse
    {
        $update = $request->all();

        if (! isset($update['message']) && ! isset($update['callback_query'])) {
            return response()->json(['status' => 'ignored']);
        }

        app(PatronageBotWebhookHandler::class)->handle($update);

        return response()->json(['status' => 'ok']);
    }
}
