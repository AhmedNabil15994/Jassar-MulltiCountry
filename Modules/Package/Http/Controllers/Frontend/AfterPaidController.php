<?php

namespace Modules\Package\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Order\Events\ActivityLog;
use Modules\Package\Entities\Subscription;
use Modules\Apps\Http\Controllers\Api\ApiController;

class AfterPaidController extends ApiController
{
    public function success(Request $request)
    {
        $action = $this->updatePaidStatus($request);
        if ($action['status']) {
            // $this->fireLog($action['model']['id']);
            return redirect()->route('frontend.subscriptions.index')
                   ->with('alert', 'success')
                   ->with('msg', __('you subscribed successfully'));
        }
        return $this->error(__('apps::api.messages.failed'));
    }

    public function failed(Request $request)
    {
        $model = $this->updatePaidStatus($request);

        return redirect()->route('frontend.home')
                         ->with('alert', 'filed')
                         ->with('msg', __('you subscribed field'));
    }



    public function updatePaidStatus($request)
    {
        $model = Subscription::find($request['OrderID']);
        DB::beginTransaction();
        try {
            if ($model) {
                $status['paid'] = $request['Result'] == 'CAPTURED' ? 'paid' : 'failed';
                $status['is_default'] = $request['Result'] == 'CAPTURED' ? true : false;
                $model->update($status);
                $model->transactions()->updateOrCreate([
                    'payment_id' => $request->PaymentID,
                ], [
                    'method'    => 'knet',
                    'payment_id' => $request->PaymentID,
                    'tran_id' => $request->TranID,
                    'result' => $request->Result,
                    'post_date' => $request->PostDate,
                    'ref' => $request->Ref,
                    'track_id' => $request->TrackID,
                    'auth' => $request->Auth,
                ]);

                if($request['Result'] == 'CAPTURED'){

                    Subscription::where("user_id", $model->user_id)
                    ->where("id", "!=", $model->id)
                    ->update(["is_default" => false]);
                }
                
                DB::commit();
                return ['status' => true];
            }
            return ['status' => false];
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }


    public function fireLog($reservations_id)
    {
        $data = [
            'id' => $reservations_id,
            'type' => 'orders',
            'url' => url(route('dashboard.reservations.show', $reservations_id)),
            'description_en' => 'New Reservation',
            'description_ar' => 'حجز جديد',
        ];

        event(new ActivityLog($data));
    }
}
