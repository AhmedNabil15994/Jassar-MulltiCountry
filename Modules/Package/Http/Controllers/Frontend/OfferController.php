<?php

namespace Modules\Package\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use IlluminateAgnostic\Collection\Support\Carbon;
use Modules\Area\Entities\Country;
use Modules\Coupon\Http\Controllers\Frontend\CouponController;
use Modules\Package\Entities\Offer;
use Modules\Package\Entities\Package;
use Modules\Package\Entities\PackagePrice;
use Modules\Package\Repositories\Frontend\OfferRepository;
use Modules\Transaction\Services\PaymentService;

use Modules\Area\Repositories\FrontEnd\CountryRepository;
use Modules\Authentication\Foundation\Authentication;
use Modules\Package\Http\Requests\Frontend\{SubscribeRequest, PauseSubscriptionRequest};
use Modules\Package\Repositories\Frontend\PackageRepository;
use Modules\Authentication\Repositories\Frontend\AuthenticationRepository;

class OfferController extends Controller
{
    use Authentication;

    protected $country;

    public function __construct(OfferRepository $offerRepository)
    {
        $this->offerRepository = $offerRepository;
        $this->middleware('auth')->only(['subscribeForm', 'subscribe', 'renew', 'pauseSubscription']);
    }

    public function index()
    {
        $offers =  $this->offerRepository->getAllOffers()->get();
        return view('package::frontend.offers.index', compact('offers'));
    }
    public function show($countryPrefix,$slug)
    {
        $offer = $this->offerRepository->findOfferBySlug($slug);
        return view('package::frontend.offers.show', compact('offer'));
    }
}
