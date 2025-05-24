<?php

namespace Modules\Coupon\Entities;

use Illuminate\Database\Eloquent\Model;

class CouponCountry extends Model
{
    protected $fillable = ['coupon_id','country_id'];
}
