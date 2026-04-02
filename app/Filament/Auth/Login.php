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
        return true;
    }

    public function getHeading(): string | Htmlable | null
    {
        return new class implements Htmlable {
            public function toHtml(): string
            {
                $logoHtml = '';
                if (file_exists(public_path('images/logo.png'))) {
                    $logoHtml = '<img src="' . asset('images/logo.png') . '" alt="Logo" style="width:64px;height:64px;border-radius:12px;margin:0 auto 12px;display:block;object-fit:contain;">';
                }
                return $logoHtml . '<div style="text-align:center;font-size:22px;font-weight:800;color:#0f766e;">ShifoReyting AI</div>';
            }
        };
    }

    public function getSubheading(): string | Htmlable | null
    {
        return 'Tizimga kirish';
    }
}
