<?php

namespace Modules\Package\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use IlluminateAgnostic\Collection\Support\Carbon;
use Modules\Area\Entities\Country;
use Modules\Coupon\Http\Controllers\Frontend\CouponController;
use Modules\Package\Entities\Package;
use Modules\Package\Entities\PackagePrice;
use Modules\Transaction\Services\PaymentService;

use Modules\Area\Repositories\FrontEnd\CountryRepository;
use Modules\Authentication\Foundation\Authentication;
use Modules\Package\Http\Requests\Frontend\{SubscribeRequest, PauseSubscriptionRequest};
use Modules\Package\Repositories\Frontend\PackageRepository;
use Modules\Authentication\Repositories\Frontend\AuthenticationRepository;

class PackageController extends Controller
{
    use Authentication;

    protected $country;

    public function __construct(PackageRepository $packageRepository)
    {
        $this->packageRepository = $packageRepository;
        $this->middleware('auth')->only(['subscribeForm', 'subscribe', 'renew', 'pauseSubscription']);
    }



    public function index()
    {
        $packages =  $this->packageRepository->getAllPackages()->get();

        return view('package::frontend.index', compact('packages'));
    }
    public function show($countryPrefix,$slug)
    {
        $package = $this->packageRepository->findPackageById($slug);
        return view('package::frontend.show', compact('package'));
    }
    public function subscribeForm($packagePrice)
    {
        $packagePrice = PackagePrice::findOrFail($packagePrice);
        $package = $packagePrice->package;
        $countries = Country::active()->get();
        return view('package::frontend.subscribe', compact('package', 'packagePrice', 'countries'));
    }

    public function subscribe(SubscribeRequest $request, $packagePrice)
    {
        if (!auth()->check()) {
            $this->auth->register($request->validated());
            $this->loginAfterRegister($request);
        }
        $packagePrice = PackagePrice::findOrFail($packagePrice);
        $package = $packagePrice->package;

        if ($request->coupon_code) {
            $coupon_data = (new CouponController)->getCouponData($request->coupon_code, $packagePrice, $package);
        } else {
            $coupon_data = null;
        }

        $subscription =  $package
            ->createSubscriptions(
                auth()->id(),
                $packagePrice,
                $coupon_data,
                false,
                [
                    'start_at' => $request->start_date,
                    'note' => $request->note,
                    'paid' => $package->is_free ? 'paid' : 'pending',
                ]
            );

        $subscription->refresh();
        $subscription->createAddress($request);
        if ($package->is_free) {
            return redirect()->route('frontend.subscriptions.index')
                ->with('alert', 'success')
                ->with('msg', __('you subscribed successfully'));
        }
        $url = $this->payment->send($subscription, 'orders', $request['payment'], ($coupon_data && $coupon_data[0] ? $coupon_data[1]['data']['total'] : null));
        return Response()->json([true, 'Redirect to get way', 'url' => $url]);
    }

    public function renew(Request $request)
    {
        $currentSubscription = auth()->user()->currentSubscription->load('package');

        $package =  $currentSubscription->package;
        $data =
            [
                'start_at' => $request->start_date,
                'paid' => 'pending',
                'renew_from_count' => (++$currentSubscription->renew_from_count),
            ];

        if ($currentSubscription->renew_from_count <= $currentSubscription->same_pricerenew_times)
            $data['price'] = $currentSubscription->price;

        $subscription = $package->createSubscriptions(auth()->id(), $currentSubscription->packagePrice, false, false, $data);
        if ($package->is_free) {
            return redirect()->route('frontend.subscriptions.index')
                ->with('alert', 'success')
                ->with('msg', __('you subscribed successfully'));
        }
        $url = $this->payment->send($subscription, 'orders', $request['payment']);
        return redirect($url);
    }

    public function pauseSubscription(PauseSubscriptionRequest $request)
    {

        $currentSubscription = auth()->user()->currentSubscription->load('package');

        $startDate = Carbon::createFromFormat('Y-m-d', $currentSubscription->start_at);
        $endDate = Carbon::createFromFormat('Y-m-d', $currentSubscription->end_at);

        if (
            Carbon::parse($request->pause_start_at)->between($startDate, $endDate) &&
            Carbon::parse($request->pause_end_at)->between($startDate, $endDate)
        ) {
            $def = Carbon::parse($request->pause_start_at)->diffInDays(Carbon::parse($request->pause_end_at));
            if ($def > $currentSubscription->max_puse_days) {

                return redirect()->route('frontend.subscriptions.index')
                    ->with('alert', 'danger')
                    ->with('msg', "the max number days for pause subscription is {$currentSubscription->max_puse_days}");
            }

            $currentSubscription->update([
                'pause_start_at' => $request->pause_start_at,
                'pause_end_at' => $request->pause_end_at,
                'end_at' => Carbon::parse($currentSubscription->end_at)->addDays($def)->toDateString(),
            ]);

            return redirect()->route('frontend.subscriptions.index')
                ->with('alert', 'success')
                ->with('msg', __('you paused your subscription successfully'));
        } else {

            return redirect()->route('frontend.subscriptions.index')
                ->with('alert', 'filed')
                ->with('msg', 'start and end date must between subscription period');
        }
    }
}
