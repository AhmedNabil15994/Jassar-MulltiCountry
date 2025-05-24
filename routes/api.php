<?php

use App\Jobs\TenantInstallation;
use App\Jobs\TestJob;
use App\Tenancy\Models\AccountType;
use App\Tenancy\Models\Plan;
use App\Tenancy\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Modules\Transaction\Services\KnetPaymentService;
use Modules\Transaction\Services\MyFatoorahPayment;

Route::get('/create', function (Request $request) {
    if (! $request->query('name')) {
        return response()->json(['message' => 'Please enter a valid tenant name.'], 422);
    }

    return Tenant::firstOrCreate(
        [
            'subdomain' => $slug = \Str::slug($request->query('name'), '-'),
        ],
        [
            'name' => $request->query('name'),
            'subdomain' => $slug,

            'email' => "{$slug}@example.com",
            'password' => bcrypt('password'),

            'plan_id' => Plan::firstOrFail()->id,
            // 'account_type_id' => AccountType::whereSlug(config('multitenancy.tenant_type'))
                // ->firstOrFail()->id,
            'account_type_id' => AccountType::whereSlug($request->query('type', config('multitenancy.tenant_type')))
                ->firstOrFail()->id,

        ],
    );
});

Route::get('/delete', function (Request $request) {
    if (! $request->query('name')) {
        return response()->json(['message' => 'Please enter a valid tenant name.'], 422);
    }

    $tenant = Tenant::where('subdomain', \Str::slug($request->query('name'), '-'))
        ->firstOrFail();

    // Delete the tenant record
    $tenant->delete();

    return response('tenant is being deleted.', 202);
});

Route::post('/validate-subdomain', function (\App\Http\Requests\Subdomain $request) {
    return ['message' => 'رابط المتجر متاح.'];
});

Route::post('/plans', function (Request $request) {
    if ($request->input('account_type_id')) {
        $plans = Plan::where('account_type_id', $request->input('account_type_id'))
            ->where('show_in_signup_page', 1)
            ->orderBy('price')->get();
    } else {
        $plans = Plan::orderBy('price')
            ->where('show_in_signup_page', 1)
            ->get();
    }

    return $plans
        ->map(function ($item) {
            $item->name = json_decode($item->name)->ar ?? $item->name;
            $item->price = number_format($item->price, 2, '.', ',');

            return $item;
        });
});

// use App\Tenancy\Models\Subscription;
Route::post('/register', function (\App\Http\Requests\TenantStore $request, KnetPaymentService $payment) {

    try {
        DB::beginTransaction();
        $tenant = new \App\Tenancy\Models\Tenant();
        $tenant->fill($request->all());
        $tenant->subdomain = \Str::slug($request->subdomain, '-');
        $tenant->domain = null;
        $tenant->password = bcrypt($request->password);
        $tenant->phone = $request->phone_prefix . ' ' . $request->phone;

        $tenant->plan_id = Plan::where('account_type_id', $request->account_type_id)
            ->where('price', 0)
            ->firstOrFail()->id;

        $tenant->save();

        $plan = Plan::where('id', $tenant->plan_id)->first();

        if ($plan->price > 0) {
            // Create a new subscription
            $tenant->createSubscriptionHistory($plan, false);

            DB::commit();
            $payment_url = $payment->send([
                'id' => $tenant->id,
                'total' => $plan->price,
                'currency' => 'KWD',
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->phone,
                'phone_code' => $request->phone_prefix,

                'success_url' => url('/upayments/success'),
                'error_url' => url('/payment/error'),
            ]);
        } else {
            // Create a new subscription
            $tenant->createSubscriptionHistory($plan, true);

            DB::commit();
            $payment_url = '';

            // Get the newly created tenant with account type
            $tenant = Tenant::with('accountType')
                ->findOrFail($tenant->id);

            // Complete tenant installation
            dispatch(new TenantInstallation($tenant))
                ->onQueue($tenant->accountType->slug);
        }
        
        return response()->json([
            true,
            'url' => $payment_url,
            'message' => $payment_url
                ? 'جاري تحويلك إلي صفحة الدفع.'
                : 'جاري انشاء متجرك الالكتروني الان. سيتم ارسال بيانات الدخول لمتجرك على بريدك الالكتروني خلال دقائق.',
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        throw $e;
    }
})
->name('app.register.post');
