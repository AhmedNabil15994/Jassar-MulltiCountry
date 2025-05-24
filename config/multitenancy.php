<?php

use Illuminate\Broadcasting\BroadcastEvent;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Mail\SendQueuedMailable;
use Illuminate\Notifications\SendQueuedNotifications;
use Spatie\Multitenancy\Actions\ForgetCurrentTenantAction;
use Spatie\Multitenancy\Actions\MakeQueueTenantAwareAction;
use Spatie\Multitenancy\Actions\MakeTenantCurrentAction;
use Spatie\Multitenancy\Actions\MigrateTenantAction;
// use Spatie\Multitenancy\Models\Tenant;
use App\Tenancy\Models\Tenant;

return [
    /*
     * This class is responsible for determining which tenant should be current
     * for the given request.
     *
     * This class should extend `Spatie\Multitenancy\TenantFinder\TenantFinder`
     *
     */
    'tenant_finder' => \App\Tenancy\Finders\SubDomainTenantFinder::class,

    /*
     * These fields are used by tenant:artisan command to match one or more tenant
     */
    'tenant_artisan_search_fields' => [
        'id',
        'subdomain',
    ],

    /*
     * These tasks will be performed when switching tenants.
     *
     * A valid task is any class that implements Spatie\Multitenancy\Tasks\SwitchTenantTask
     */
    'switch_tenant_tasks' => [
        // Custom task
        \App\Tenancy\Tasks\SwitchDatabaseConnectionTask::class,

        \Spatie\Multitenancy\Tasks\SwitchTenantDatabaseTask::class,
        \Spatie\Multitenancy\Tasks\PrefixCacheTask::class,

        // Custom tasks
        // \App\Tenancy\Tasks\SetAppUrlTask::class,
        // \App\Tenancy\Tasks\SetLocalFilesystemTask::class,
        \App\Tenancy\Tasks\SetS3FilesystemTask::class,
        \App\Tenancy\Tasks\SetLaravelBackupTask::class,
        \App\Tenancy\Tasks\PassportTask::class,
        \App\Tenancy\Tasks\SmtpTask::class,
        // \App\Tenancy\Tasks\PaymentTask::class,
        \App\Tenancy\Tasks\BroadcastingTask::class,
    ],

    /*
     * This class is the model used for storing configuration on tenants.
     *
     * It must be or extend `Spatie\Multitenancy\Models\Tenant::class`
     */
    'tenant_model' => Tenant::class,

    /*
     * If there is a current tenant when dispatching a job, the id of the current tenant
     * will be automatically set on the job. When the job is executed, the set
     * tenant on the job will be made current.
     */
    'queues_are_tenant_aware_by_default' => true,

    /*
     * The connection name to reach the tenant database.
     *
     * Set to `null` to use the default connection.
     */
    'tenant_database_connection_name' => 'tenant',

    /*
     * The connection name to reach the landlord database
     */
    'landlord_database_connection_name' => 'landlord',

    /*
     * This key will be used to bind the current tenant in the container.
     */
    'current_tenant_container_key' => 'currentTenant',

    /*
     * You can customize some of the behavior of this package by using our own custom action.
     * Your custom action should always extend the default one.
     */
    'actions' => [
        'make_tenant_current_action' => MakeTenantCurrentAction::class,
        'forget_current_tenant_action' => ForgetCurrentTenantAction::class,
        'make_queue_tenant_aware_action' => MakeQueueTenantAwareAction::class,
        'migrate_tenant' => MigrateTenantAction::class,
    ],

    /*
     * You can customize the way in which the package resolves the queuable to a job.
     *
     * For example, using the package laravel-actions (by Loris Leiva), you can
     * resolve JobDecorator to getAction() like so: JobDecorator::class => 'getAction'
     */
    'queueable_to_job' => [
        SendQueuedMailable::class => 'mailable',
        SendQueuedNotifications::class => 'notification',
        CallQueuedListener::class => 'class',
        BroadcastEvent::class => 'event',
    ],

    'www_domain' => env('WWW_DOMAIN', 'www.toucart.127.0.0.1.xip.io'),

    'main_domain' => env('MAIN_DOMAIN', 'toucart.com'),


    'reserved_subdomains' => [
        // 'www',
        // 'www-res',
        // 'api',
        // 'app',
        // 'cdn',
        // 'cdn-res',
        // 'dashboard',
        // 'business',
    ],

    'admin_email' => env('ADMIN_EMAIL', 'tech@tocaan.com'),

    'tenant_type' => env('TENANT_TYPE', 'ecommerce'),

    'nginx_subdomains_path' => env('NGINX_SUBDOMAINS_PATH'),
];
