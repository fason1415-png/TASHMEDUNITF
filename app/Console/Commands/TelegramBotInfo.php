<?php

namespace App\Console\Commands;

use App\Services\TelegramBotService;
use Illuminate\Console\Command;

class TelegramBotInfo extends Command
{
    protected $signature = 'telegram:info';

    protected $description = 'Get Telegram bot info';

    public function handle(): int
    {
        $bot = app(TelegramBotService::class);
        $result = $bot->getMe();

        if ($result['ok'] ?? false) {
            $info = $result['result'];
            $this->info("Bot: @{$info['username']}");
            $this->info("Name: {$info['first_name']}");
            $this->info("ID: {$info['id']}");

            return self::SUCCESS;
        }

        $this->error('Failed to get bot info');

        return self::FAILURE;
    }
}
