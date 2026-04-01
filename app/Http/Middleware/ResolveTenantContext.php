<?php

namespace App\Http\Middleware;

use App\Models\Clinic;
use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $context = app(TenantContext::class);
        $user = $request->user();

        if ($user && $user->clinic_id) {
            $context->setClinicId((int) $user->clinic_id);
        }

        if ($user && $user->isSuperAdmin() && $request->filled('clinic_id')) {
            $clinicId = (int) $request->integer('clinic_id');
            if (Clinic::query()->whereKey($clinicId)->exists()) {
                $context->setClinicId($clinicId);
            }
        }

        if (! $context->hasClinic() && $user) {
            $requestClinicId = $request->header('X-Clinic-ID');
            if ($requestClinicId && is_numeric($requestClinicId) && $user->isSuperAdmin()) {
                $context->setClinicId((int) $requestClinicId);
            }
        }

        return $next($request);
    }
}

