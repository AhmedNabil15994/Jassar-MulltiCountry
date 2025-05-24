<?php

namespace App\Tenancy\Models;

use App\Tenancy\Models\AccountType;
use App\Traits\HasSchemalessAttributes;
use App\Traits\HasShemalessSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class Plan extends Model
{
    use UsesLandlordConnection;
    use HasSchemalessAttributes;
    use HasShemalessSettings;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'price',

        'account_type_id',
        'extra_attributes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'extra_attributes' => 'array',
        'show_in_signup_page' => 'boolean',
    ];

    public function accountType()
    {
        return $this->belongsTo(AccountType::class);
    }
}
