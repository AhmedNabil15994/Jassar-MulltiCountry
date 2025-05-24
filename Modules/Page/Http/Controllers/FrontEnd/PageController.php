<?php

namespace Modules\Page\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Page\Repositories\FrontEnd\PageRepository as Page;

class PageController extends Controller
{

    function __construct(Page $page)
    {
        $this->page = $page;
    }

    public function page($countryPrefix,$slug)
    {
        $page = $this->page->findBySlug($slug);

        if(!$page)
            abort(404);



        if ($page && !checkRouteLocale($page, $slug)) {

            return redirect()->route('frontend.pages.index', [
                $page->slug
            ]);
        }
        return view('page::frontend.pages.index',compact('page'));
    }
}
