<?php

namespace App\Tenancy\Tasks;

use Spatie\Multitenancy\Models\Tenant;
use JanisKelemen\Setting\Facades\Setting;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class PaymentTask implements SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void
    {
        if (Setting::has('payment.upayments.merchant_id')) {
            config()->set([
                'services.upayments' => array_merge(
                    config('services.upayments'),
                    Setting::get('payment.upayments'),
                ),
            ]);
        }

        if (Setting::has('payment.myfatoorah.username')) {
            config()->set([
                'services.myfatoorah' => array_merge(
                    config('services.myfatoorah'),
                    Setting::get('payment.myfatoorah'),
                ),
            ]);
        }
    }

    public function forgetCurrent(): void
    {
        config()->set([
            'services.upayments.merchant_id' => config('services.upayments.default.merchant_id'),

            'services.upayments.username' => config('services.upayments.default.username'),

            'services.upayments.password' => config('services.upayments.default.password'),

            'services.upayments.api_key' => config('services.upayments.default.api_key'),
        ]);

        config()->set([
            'services.myfatoorah.username' => config('services.myfatoorah.default.username'),

            'services.myfatoorah.password' => config('services.myfatoorah.default.password'),
        ]);
    }
}
