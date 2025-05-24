<?php

use App\Http\Requests\TenantStore;
use App\Jobs\TenantInstallation;
use App\Tenancy\Models\AccountType;
use App\Tenancy\Models\Plan;
use App\Tenancy\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Modules\Transaction\Services\MyFatoorahPayment;

Route::webhooks('tenancy-webhook');

Route::get('/', function () {
    return 'homepage';
    // return view('welcome');
});

Route::get('/register', function () {
    $plans = Plan::orderBy('price')->get()
        ->map(function ($item) {
            $item->name = json_decode($item->name)->ar ?? $item->name;
            return $item;
        });

    $account_types = AccountType::where('show_in_signup_page', 1)
        ->get()
        ->map(function ($item) {
            $item->name = json_decode($item->name)->ar ?? $item->name;
            return $item;
        });
        
    return view('app.register', compact('account_types', 'plans'));
})
->name('app.register');

Route::post('/register', function (TenantStore $request) {
    // dd($request->all());

    $tenant = new Tenant();
    $tenant->fill($request->all());
    $tenant->subdomain = Str::slug($request->subdomain, '-');
    $tenant->password = bcrypt($request->password);
    $tenant->phone = $request->phone_prefix . ' ' . $request->phone;
    // dd($tenant);

    $tenant->plan_id = Plan::where('account_type_id', $request->account_type_id)
        ->where('price', 0)
        ->firstOrFail()->id;

    $tenant->save();

    return redirect()->to(route('app.register'))->with('message', 'يتم إنشاء حسابك الآن.');
    // return Tenant::create($request->all());
})
->name('app.register.post');

Route::get('/payment/error', function (Request $request) {
    logger('error');
    logger($request->all());

    return redirect()->route('app.register')
        ->withErrors([
            'An unexpected error occurred.',
        ]);
});

Route::get('/payment/success', function (Request $request, MyFatoorahPayment $payment) {
    logger('success');
    logger($request->all());

    // Get transaction details
    $response = $payment->getTransactionDetails($request->paymentId);
    logger($response);

    $status = strtoupper($response['InvoiceStatus']);

    if ($status !== 'PAID') {
        return redirect()->route('app.register')
            ->withErrors([
                'An unexpected error occurred.',
            ]);
    }

    // Get transaction tenant
    $tenant = Tenant::with('accountType', 'currentSubscription', 'plan')
        ->findOrFail($response['UserDefinedField']);

    if (! $tenant->currentSubscription) {
        $tenant->createSubscriptionHistory($tenant->plan, false);
        $tenant->load('currentSubscription');
    }

    if (! $tenant->currentSubscription->is_paid) {
        // @TODO: Create or Update subscription...
        $tenant->currentSubscription
            ->extra_attributes->set('payment_response', $request->all());

        $tenant->currentSubscription
            ->fill(['is_paid' => true])
            ->save();

        // Complete tenant installation
        dispatch(new TenantInstallation($tenant))
            ->onQueue($tenant->accountType->slug);
    }

    return redirect()->route('app.register')
        ->with('message', 'جاري انشاء متجرك الالكتروني الان. سيتم ارسال بيانات الدخول لمتجرك على بريدك الالكتروني خلال دقائق.');
});

Route::get('/upayments/success', function (Request $request) {
    logger('success');
    logger($request->all());

    $status = strtoupper($request->input('Result'));

    if ($status !== 'CAPTURED' || ! $request->input('OrderID')) {
        return redirect()->route('app.register')
            ->withErrors([
                'An unexpected error occurred.',
            ]);
    }

    // Get transaction tenant
    $tenant = Tenant::with('accountType', 'currentSubscription', 'plan')
        ->findOrFail($request->input('OrderID'));

    if (! $tenant->currentSubscription) {
        $tenant->createSubscriptionHistory($tenant->plan, false);
        $tenant->load('currentSubscription');
    }

    if (! $tenant->currentSubscription->is_paid) {
        // @TODO: Create or Update subscription...
        $tenant->currentSubscription
            ->extra_attributes->set('payment_response', $request->all());

        $tenant->currentSubscription
            ->fill(['is_paid' => true])
            ->save();

        // Complete tenant installation
        dispatch(new TenantInstallation($tenant))
            ->onQueue($tenant->accountType->slug);
    }

    return redirect()->route('app.register')
        ->with('message', 'جاري انشاء متجرك الالكتروني الان. سيتم ارسال بيانات الدخول لمتجرك على بريدك الالكتروني خلال دقائق.');
});
