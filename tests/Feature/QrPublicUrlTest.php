<?php

namespace Tests\Feature;

use App\Services\QrCodeService;
use Tests\TestCase;

class QrPublicUrlTest extends TestCase
{
    public function test_qr_public_url_uses_configured_base_url(): void
    {
        config([
            'shiforeyting.qr_public_base_url' => 'http://192.168.100.10/dashboard/1/public',
        ]);

        $url = app(QrCodeService::class)->buildSurveyUrl('dep 2 13');

        $this->assertSame('http://192.168.100.10/dashboard/1/public/f/dep%202%2013', $url);
    }
}

