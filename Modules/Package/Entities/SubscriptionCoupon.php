<?php


namespace Modules\Package\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Coupon\Entities\Coupon;
use Modules\Package\Entities\Subscription;

class SubscriptionCoupon extends Model
{
    protected $guarded = ['id'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

}
