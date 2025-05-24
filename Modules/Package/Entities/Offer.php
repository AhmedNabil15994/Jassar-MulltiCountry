<?php

namespace Modules\Package\Entities;

use Carbon\Carbon;
use Modules\Area\Entities\Country;
use Modules\Catalog\Entities\Product;
use Modules\Core\Traits\HasSlugTranslation;
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

class Offer extends Model implements HasMedia
{
    use CrudModel;
    use SchemalessAttributesTrait;

    use SoftDeletes;
    use HasSlugTranslation;

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


    public function products()
    {
        return $this->belongsToJson(Product::class, 'settings->products');
    }

    public function freeProducts()
    {
        return $this->belongsToJson(Product::class, 'settings->free_products');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot('expired_date');
    }

    public function getImageAttribute()
    {
        return $this->getFirstMediaUrl('images') != "" ? $this->getFirstMediaUrl('images') : asset(setting('logo'));
    }

    public function getPhotoAttribute()
    {
        return $this->getFirstMediaUrl('images') != "" ? $this->getFirstMediaUrl('images') : asset(setting('logo'));
    }

}
