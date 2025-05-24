<?php

namespace App\Tenancy\Models;

use App\Jobs\TenantDeletion;
use App\Jobs\TenantInstallation;
use App\Notifications\NewCustomerRegistration;
use App\Tenancy\Models\AccountType;
use App\Tenancy\Models\Plan;
use App\Tenancy\Models\Subscription;
use App\Traits\HasSchemalessAttributes;
use App\Traits\HasShemalessSettings;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    use HasSchemalessAttributes;
    use HasShemalessSettings;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'subdomain',
        'domain',

        'account_type_id',
        'plan_id',
        'subscription_id',

        'phone',
        'email',
        'password',

        'extra_attributes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'database',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'extra_attributes' => 'array',
    ];

    public function installed()
    {
        return (
            $this->setting('install.disk') &&
            $this->setting('install.migration') &&
            $this->setting('install.passport') &&
            $this->setting('install.websockets')
        );
    }

    public static function booted()
    {
        static::creating(fn (Tenant $model) => $model->createDatabase($model));

        static::created(function (Tenant $model) {
            // notify our admin
            Notification::route('mail', config('multitenancy.admin_email'))
                // ->route('discord', config('services.discord.webhook_url'))
                ->notify(new NewCustomerRegistration($model));

            // dispatch(new TenantInstallation($model))
            //     ->onQueue($model->accountType->slug);
        });

        static::updating(function (Tenant $model) {
            if ($model->isDirty('domain') && $model->getOriginal('domain')) {
                // Delete custom domain tenant name from redis.
                Redis::del($model->getOriginal('domain'));
            }
        });

        static::updated(function (Tenant $model) {
            if ($model->wasChanged('domain', 'subdomain')) {
                Artisan::call('tenants:update-nginx-configs');
            }
        });

        static::deleting(function (Tenant $model) {
            dispatch(new TenantDeletion($model->only('id', 'name', 'email', 'subdomain', 'domain', 'database')))
                ->onQueue($model->accountType->slug);
        });

        static::deleted(function () {
            Artisan::call('tenants:update-nginx-configs');
        });
    }

    protected function createDatabase(Tenant $model)
    {
        $model->database = Str::slug(config('app.name') . '_' . $model->subdomain, '_');

        $connection = config('multitenancy.tenant_database_connection_name');

        $charset = config("database.connections.{$connection}.charset");
        $collation = config("database.connections.{$connection}.collation");

        DB::connection($connection)
            ->statement("CREATE DATABASE IF NOT EXISTS `{$model->database}` CHARACTER SET {$charset} COLLATE {$collation}");

        // ->statement("CREATE TABLE `{$model->database}`.oauth_access_tokens SELECT * FROM toucart_shared_database.oauth_access_tokens")

            // ->statement("CREATE TABLE `{$model->database}`.oauth_auth_codes SELECT * FROM toucart_shared_database.oauth_auth_codes")

            // ->statement("CREATE TABLE `{$model->database}`.oauth_clients SELECT * FROM toucart_shared_database.oauth_clients")

            // ->statement("CREATE TABLE `{$model->database}`.oauth_personal_access_clients SELECT * FROM toucart_shared_database.oauth_personal_access_clients")

            // ->statement("CREATE TABLE `{$model->database}`.oauth_refresh_tokens SELECT * FROM toucart_shared_database.oauth_refresh_tokens");
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }

    public function currentSubscription()
    {
        return $this->belongsTo(Subscription::class, "subscription_id");
    }

    public function historySubscriptions()
    {
        return $this->belongsTo(Subscription::class, "subscription_id");
    }

    public function createSubscriptionHistory($plan, $is_paid = true)
    {
        $now = now();
        $discount = 0;
        $data = [
            "is_paid"       => $is_paid ,
            "start_at"      => $now,
            "duration"      => $plan->duration,
            "end_at"        => $now->copy()->addDays($plan->duration) ,
            "origin_total"  => $plan->price,
            "discount"      => $discount ,
            "plan_id"       => $plan->id,
            "tenant_id"     => $this->id,
            "total"         => $plan->price - $discount

        ];
        $subscription = $this->historySubscriptions()->updateOrCreate(
            ["is_paid" => false, "tenant_id"     => $this->id],
            $data
        );
        $subscription
                    ->setSetting('max_products_num', $plan->setting("max_products_num"))
                    ->setSetting('max_branches_num', $plan->setting("max_branches_num"))
                    ->setSetting('plan', $plan)
                    ->save()  ;

        // if ($is_paid) {
        $this->update(["subscription_id" => $subscription->id, "plan_id" => $plan->id]);
        // }

        return $subscription;
    }
}