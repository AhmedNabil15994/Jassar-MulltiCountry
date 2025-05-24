<?php

namespace App\Tenancy\Models;

use App\Tenancy\Models\Plan;
use App\Tenancy\Models\Tenant;
use App\Traits\HasSchemalessAttributes;
use App\Traits\HasShemalessSettings;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class Subscription extends Model
{
    use UsesLandlordConnection;
    use HasSchemalessAttributes;
    use HasShemalessSettings;

    protected $fillable = [
        "start_at" ,
        "end_at" ,
        "duration",
        "original_total",
        "discount",
        "total",
        "is_paid",
        "plan_id",
        "tenant_id",
        'extra_attributes',
    ];

    /**
    * The attributes that should be cast.
    *
    * @var array
    */
    protected $casts = [
        'total' => 'decimal:3',
        'is_paid' => 'bool',
        'extra_attributes' => 'array',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
