<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramBotService
{
    private string $token;

    private string $apiUrl;

    public function __construct()
    {
        $this->token = config('telegram.bot_token');
        $this->apiUrl = "https://api.telegram.org/bot{$this->token}";
    }

    /**
     * Send a text message.
     */
    public function sendMessage(int|string $chatId, string $text, ?array $replyMarkup = null): array
    {
        $params = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'Markdown',
        ];

        if ($replyMarkup) {
            $params['reply_markup'] = json_encode($replyMarkup);
        }

        return $this->request('sendMessage', $params);
    }

    /**
     * Send message with inline keyboard buttons.
     */
    public function sendMessageWithInlineKeyboard(int|string $chatId, string $text, array $buttons): array
    {
        return $this->sendMessage($chatId, $text, [
            'inline_keyboard' => $buttons,
        ]);
    }

    /**
     * Answer callback query (acknowledge button press).
     */
    public function answerCallbackQuery(string $callbackQueryId, ?string $text = null): array
    {
        $params = [
            'callback_query_id' => $callbackQueryId,
        ];

        if ($text) {
            $params['text'] = $text;
        }

        return $this->request('answerCallbackQuery', $params);
    }

    /**
     * Edit message text (for updating after button press).
     */
    public function editMessageText(int|string $chatId, int $messageId, string $text, ?array $replyMarkup = null): array
    {
        $params = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $text,
            'parse_mode' => 'Markdown',
        ];

        if ($replyMarkup) {
            $params['reply_markup'] = json_encode($replyMarkup);
        }

        return $this->request('editMessageText', $params);
    }

    /**
     * Set webhook URL.
     */
    public function setWebhook(string $url): array
    {
        return $this->request('setWebhook', ['url' => $url]);
    }

    /**
     * Delete webhook.
     */
    public function deleteWebhook(): array
    {
        return $this->request('deleteWebhook');
    }

    /**
     * Get bot info.
     */
    public function getMe(): array
    {
        return $this->request('getMe');
    }

    /**
     * Make API request.
     */
    private function request(string $method, array $params = []): array
    {
        $response = Http::post("{$this->apiUrl}/{$method}", $params);

        return $response->json();
    }
}
