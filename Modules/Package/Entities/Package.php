<?php

namespace Modules\Package\Entities;

use Carbon\Carbon;
use Modules\Area\Entities\Country;
use Modules\Catalog\Entities\Product;
use Modules\Order\Entities\Order;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;
use Modules\Core\Traits\Dashboard\CrudModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\SchemalessAttributes\SchemalessAttributes;
use Spatie\SchemalessAttributes\SchemalessAttributesTrait;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Package extends Model implements HasMedia
{
    use CrudModel;
    use SchemalessAttributesTrait;

    use SoftDeletes;

    use InteractsWithMedia;
    use HasJsonRelationships, HasTranslations {
        HasJsonRelationships::getAttributeValue as getAttributeValueJson;
        HasTranslations::getAttributeValue as getAttributeValueTranslations;
    }
    public function getAttributeValue($key)
    {
        if (!$this->isTranslatableAttribute($key)) {
            return $this->getAttributeValueJson($key);
        }
        return $this->getAttributeValueTranslations($key);
    }

    // use CoreHelpers;
    protected $guarded = ["id"];
    public $translatable = ['title', 'description'];
    public $appends = ['photo'];
    protected $schemalessAttributes = [
        'settings',
    ];
    public function scopeWithSettings(): Builder
    {
        return $this->settings->modelScope();
    }
    public function prices()
    {
        return $this->hasMany(PackagePrice::class, "package_id");
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, "package_id");
    }

    public function toDaySubscriptions()
    {
        return $this->hasMany(Subscription::class, "package_id")->ToDay();
    }

    public function products()
    {
        return $this->belongsToJson(Product::class, 'settings->products');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('expired_date');
    }




    public function createSubscriptions($user_id, $price, $coupon_data, $from_admin = false, $attribute = [])
    {
        $data = [
            "from_admin" => $from_admin,
            "paid" => $from_admin == false ? 'pending' : 'paid',
            "price" => $price->active_price['price'],
            "same_pricerenew_times" => $price->same_pricerenew_times,
            "max_puse_days" => $price->max_puse_days,
            "package_price_id" => $price->id,
            "start_at" => Carbon::parse($attribute['start_at']),
            "is_free" => $price->active_price['price'] <= 0,
            "user_id" => $user_id,
            "is_default" => $price->active_price['price'] <= 0 ? true : false,
        ];
        $data['end_at'] = $this->calculateEndAt($data['start_at'], $price->days_count);
        $data = array_merge($data, $attribute);
        $subscription = $this->subscriptions()->create($data);

        if ($coupon_data && $coupon_data[0]) {
            $coupon = $coupon_data[2];
            $subscription->coupon()->create([
                'coupon_id' => $coupon->id,
                'code' => $coupon->code,
                'discount_type' => $coupon->discount_type,
                'discount_percentage' => $coupon->discount_percentage,
                'discount_value' => $coupon->discount_value
            ]);
        }
        if ($price->active_price['price'] <= 0) {
            Subscription::where("user_id", $user_id)
                ->where("id", "!=", $subscription->id)
                ->update(["is_default" => false]);
        }
        return $subscription;
    }




    public function ScopeCategories($q, $categories)
    {
        return $q->whereHas(
            'categories',
            fn ($q) => $q->whereIn('categorized.category_id', $categories)

        );
    }
    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('images') != "" ? $this->getFirstMediaUrl('images') : asset(setting('logo'));
    }

    public function getPhotoAttribute()
    {
        return $this->getFirstMediaUrl('images') != "" ? $this->getFirstMediaUrl('images') : asset(setting('logo'));
    }

    public function ScopeSearch($q, $search)
    {
        return $q
            ->where(
                fn ($query) =>
                $query
                    ->Where("title->" . locale(), 'like', '%' . $search . '%')
            );
    }

}
