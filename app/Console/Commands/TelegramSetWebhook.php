<?php

namespace App\Console\Commands;

use App\Services\TelegramBotService;
use Illuminate\Console\Command;

class TelegramSetWebhook extends Command
{
    protected $signature = 'telegram:set-webhook {url?}';

    protected $description = 'Set Telegram bot webhook URL';

    public function handle(): int
    {
        $url = $this->argument('url') ?? config('telegram.webhook_url') ?? url('/api/v1/webhooks/telegram');

        $bot = app(TelegramBotService::class);
        $result = $bot->setWebhook($url);

        if ($result['ok'] ?? false) {
            $this->info("Webhook set: {$url}");

            return self::SUCCESS;
        }

        $this->error('Failed: '.($result['description'] ?? 'Unknown error'));

        return self::FAILURE;
    }
}
