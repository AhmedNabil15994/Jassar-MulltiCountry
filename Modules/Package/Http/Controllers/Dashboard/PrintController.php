<?php
namespace Modules\Package\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\Package\Entities\PrintSetting;
use Illuminate\Routing\Controller;
use Modules\Package\Repositories\Dashboard\SubscriptionRepository;

class PrintController extends Controller
{

    protected $subscription;

    function __construct(SubscriptionRepository $subscription)
    {
        $this->subscription = $subscription;
    }
    public function index(Request $request)
    {
        return view('package::dashboard.print.index');
    }

    public function renderPrint(Request $request){


        if($request->from && $request->to && $request->from == Carbon::now()->toDateString()
        && $request->from == $request->to){

            $subscriptions = $this->subscription->QueryTable($request)->Today()->get();
        }else{

            $request->merge([
                'req' => $request->all()
            ]);
            $subscriptions = $this->subscription->QueryTable($request)->get();
        }

        $print_setting_details    = PrintSetting::find($request->print_setting_id);
       
        if($print_setting_details->stickers_in_one_row == 1){
            $print_setting_details->col_distance = 0;
            $print_setting_details->row_distance = 0;
        }
        $margin_top = $print_setting_details->is_continuous ? 0: $print_setting_details->top_margin*1;
        $margin_left = $print_setting_details->is_continuous ? 0: $print_setting_details->left_margin*1;
        $paper_width = $print_setting_details->paper_width* 1;
       
        $total_qty    = count($subscriptions);
        $paper_height = $print_setting_details->paper_height  ? $print_setting_details->paper_height : $total_qty * $print_setting_details->height  ;
        

        $html     = view("package::dashboard.print.ajex.print",
                 compact(
                    "request", 
                    "subscriptions",
                    "print_setting_details",
                    "margin_top",
                    "margin_left",
                    "paper_height",
                    "total_qty"
                    )
                 )->render();

        return response()->json(["html"=>$html]);
    } 
}
