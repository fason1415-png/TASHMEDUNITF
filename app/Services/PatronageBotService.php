<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PatronageBotService
{
    private string $token;
    private string $apiUrl;

    public function __construct()
    {
        $this->token = config('telegram.patronage_bot_token');
        $this->apiUrl = "https://api.telegram.org/bot{$this->token}";
    }

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

    public function sendWithButtons(int|string $chatId, string $text, array $buttons): array
    {
        return $this->sendMessage($chatId, $text, ['inline_keyboard' => $buttons]);
    }

    public function answerCallbackQuery(string $id, ?string $text = null): array
    {
        $params = ['callback_query_id' => $id];
        if ($text) $params['text'] = $text;
        return $this->request('answerCallbackQuery', $params);
    }

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

    public function setWebhook(string $url): array
    {
        return $this->request('setWebhook', ['url' => $url]);
    }

    public function getMe(): array
    {
        return $this->request('getMe');
    }

    private function request(string $method, array $params = []): array
    {
        return Http::post("{$this->apiUrl}/{$method}", $params)->json() ?? [];
    }
}
