<?php

namespace Modules\Order\Entities;

use Modules\Course\Entities\Note;
use Illuminate\Database\Eloquent\Model;
use Modules\Package\Entities\Offer;
use Modules\Package\Entities\Package;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;
use Illuminate\Database\Eloquent\Builder;
use Modules\Category\Entities\Category;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Modules\Course\Entities\Course;

class OrderOffer extends Model
{

    use HasJsonRelationships {
        HasJsonRelationships::getAttributeValue as getAttributeValueJson;
    }
    protected $table = "order_offer";
    protected $fillable = [
        'has_offer',
        'offer_price',
        'total',
        'expired_date',
        'period',
        'settings',
        'offer_id',
        'selected_products',
        'order_id',
        'user_id',
    ];

    public $casts = [
        'settings' => SchemalessAttributes::class,
    ];

    public function scopeWithSettings(): Builder
    {
        return $this->settings->modelScope();
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class)->withTrashed();
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
