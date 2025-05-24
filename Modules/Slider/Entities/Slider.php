<?php

namespace Modules\Slider\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ScopesTrait;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;
use Modules\Core\Traits\HasSlugTranslation;

class Slider extends Model  implements HasMedia
{
    use HasSlugTranslation;
    use HasTranslations, SoftDeletes, ScopesTrait,InteractsWithMedia;

    protected $with = [];
    protected $table = 'sliders';
    protected $guarded = ['id'];
    public $translatable = ['title', 'short_description', 'slug'];
    protected $appends = ['morph_model','image_path'];

    protected function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function getMorphModelAttribute()
    {
        return !is_null($this->sliderable) ? (new \ReflectionClass($this->sliderable))->getShortName() : null;
    }

    public function sliderable()
    {
        return $this->morphTo();
    }

}
