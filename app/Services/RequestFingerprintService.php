<?php

namespace App\Services;

use Illuminate\Http\Request;

class RequestFingerprintService
{
    /**
     * @param array<string,mixed> $payload
     * @return array{ip_hash:string|null,device_hash:string|null,fingerprint_hash:string|null,country:string|null}
     */
    public function build(Request $request, array $payload = []): array
    {
        $salt = (string) config('app.key');

        $ip = $request->ip();
        $userAgent = (string) $request->userAgent();
        $fingerprint = (string) ($payload['device_fingerprint'] ?? '');

        return [
            'ip_hash' => $ip ? hash('sha256', $salt.'|'.$ip) : null,
            'device_hash' => $userAgent !== '' ? hash('sha256', $salt.'|'.$userAgent) : null,
            'fingerprint_hash' => $fingerprint !== '' ? hash('sha256', $salt.'|'.$fingerprint) : null,
            'country' => $request->header('CF-IPCountry') ?: $request->header('X-Country'),
        ];
    }
}

