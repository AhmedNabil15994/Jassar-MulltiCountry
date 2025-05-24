<?php

namespace Modules\Advertising\Entities;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Traits\ScopesTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Advertising extends Model implements HasMedia
{
    use SoftDeletes, ScopesTrait,InteractsWithMedia;

    protected $table = 'advertising';
    protected $fillable = ['image', 'link', 'status', 'sort', 'start_at', 'end_at', 'advertable_id', 'advertable_type', 'ad_group_id'];
    protected $with = ['media'];
    protected $appends = ['morph_model','image_path'];

    public function getMorphModelAttribute()
    {
        return !is_null($this->advertable) ? (new \ReflectionClass($this->advertable))->getShortName() : null;
    }

    public function advertable()
    {
        return $this->morphTo();
    }

    public function advertGroup()
    {
        return $this->belongsTo(AdvertisingGroup::class, 'ad_group_id');
    }
    public function getImagePathAttribute()
    {
        return $this->getFirstMediaUrl('images') != '' ? $this->getFirstMediaUrl('images') : 
        '/dukaan/images/home-slider/'.(int)substr($this->id, -1).'.jpg';
    }
}
