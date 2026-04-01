<?php

namespace App\Filament\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    protected Width | string | null $maxWidth = Width::SevenExtraLarge;

    protected array $extraBodyAttributes = [
        'class' => 'sr-login-body',
    ];

    public function hasLogo(): bool
    {
        return false;
    }

    public function getHeading(): string | Htmlable | null
    {
        return null;
    }

    public function getSubheading(): string | Htmlable | null
    {
        return null;
    }
}

