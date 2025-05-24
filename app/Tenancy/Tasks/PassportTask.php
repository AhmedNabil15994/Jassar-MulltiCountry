<?php

namespace App\Tenancy\Tasks;

use Laravel\Passport\Token;
use Laravel\Passport\Client;
use Laravel\Passport\AuthCode;
use Laravel\Passport\Passport;
use Spatie\Multitenancy\Models\Tenant;
use Laravel\Passport\PersonalAccessClient;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

class PassportTask implements SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void
    {
        $this->setTenantPassport($tenant);

        // Set the storage location of the encryption keys.
        Passport::loadKeysFrom(storage_path('tenants/' . $tenant->id));

        // Set database connection
        config()->set('passport.storage.database.connection', 'tenant');
    }

    public function forgetCurrent(): void
    {
        $this->setTenantPassport();

        // Set the storage location of the encryption keys.
        Passport::loadKeysFrom(storage_path());

        // Set database connection
        config()->set('passport.storage.database.connection', 'landlord');
    }

    protected function setTenantPassport(?Tenant $tenant = null): void
    {
        Passport::useTokenModel(TokenTenantAware::class);
        Passport::useClientModel(ClientTenantAware::class);
        Passport::useAuthCodeModel(AuthCodeTenantAware::class);
        Passport::usePersonalAccessClientModel(PersonalAccessClientTenantAware::class);
    }
}

class TokenTenantAware extends Token
{
    use UsesTenantConnection;
}

class ClientTenantAware extends Client
{
    use UsesTenantConnection;
}

class AuthCodeTenantAware extends AuthCode
{
    use UsesTenantConnection;
}

class PersonalAccessClientTenantAware extends PersonalAccessClient
{
    use UsesTenantConnection;
}
