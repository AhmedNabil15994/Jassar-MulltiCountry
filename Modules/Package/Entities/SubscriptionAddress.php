<?php


namespace Modules\Package\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Package\Entities\Subscription;

class SubscriptionAddress extends Model
{
    protected $guarded = ['id'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function state()
    {
        return $this->belongsTo(\Modules\Area\Entities\State::class);
    }

    public function getLineOneAttribute()
    {
        $lineOne = '';
        $first = '';
        $attrs = $this->attributes()->get();

        if (count($attrs)) {
            foreach ($attrs as $attr) {

                $lineOne .= "{$first}{$attr->name}:{$attr->value}";
                $first = ',';
            }
        }

        return $lineOne;
    }

}
