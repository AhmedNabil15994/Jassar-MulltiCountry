<?php

namespace Modules\Package\Entities;

use Modules\Core\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Modules\Package\Entities\Subscription;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Concerns\HasUuid;

class Suspension extends Model
{
    use HasFactory;
    use UsesUuid;

    protected $guarded = ['id'];

    /**
     * Get the subscription that owns the Suspension
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
