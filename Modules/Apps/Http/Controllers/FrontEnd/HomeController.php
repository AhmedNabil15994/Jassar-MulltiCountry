<?php

namespace Modules\Apps\Http\Controllers\FrontEnd;

use Notification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Apps\Http\Requests\FrontEnd\ContactUsRequest;
use Modules\Apps\Notifications\FrontEnd\ContactUsNotification;
use Cart;
use Modules\Apps\Transformers\Frontend\HomeFilterResource;
use Modules\Apps\Repositories\Frontend\AppHomeRepository as Home;
use AmrShawky\LaravelCurrency\Facade\Currency;
use Setting;

class HomeController extends Controller
{

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        return view('apps::dukaan.index');
    }

    public function landing(Request $request)
    {
        $this->home = new Home;
        $home_sections = $this->home->getAll($request);
        $home_sections = view('apps::frontend.home-sections.section-builder', compact('home_sections'))->render();

        return view('apps::frontend.index', compact('home_sections'));

    }

    public function contactUs()
    {
        return view('apps::dukaan.contact-us');
    }

    public function sendContactUs(ContactUsRequest $request)
    {
        Notification::route('mail', setting('contact_us.email'))
            ->notify((new ContactUsNotification($request))->locale(locale()));

        return response()->json();
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Throwable
     */
    public function autocompleteProducts(Request $request)
    {
        $results = HomeFilterResource::collection($this->product->autoCompleteSearch($request)->take(30)->get(['title', 'id', 'slug']))->jsonSerialize();
        $response = view('apps::frontend.components.live-search-menu', compact('results'))->render();
        return response()->json(['html' => $response]);
    }

    public function updateCurrency(Request $request)
    {
        refreshCurrency($request->code);
    }

    public function updateCountry(Request $request)
    {
        if (in_array($request->country_id,(array)Setting::get('supported_countries'))) {
            session()->put('current_country', $request->country_id);
        }
    }
}
