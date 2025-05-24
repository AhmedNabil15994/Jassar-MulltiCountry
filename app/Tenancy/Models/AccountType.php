<?php

namespace App\Tenancy\Models;

use App\Traits\HasSchemalessAttributes;
use App\Traits\HasShemalessSettings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Multitenancy\Models\Concerns\UsesLandlordConnection;

class AccountType extends Model
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
        'name', 'slug',

        'extra_attributes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'extra_attributes' => 'array',
        'show_in_signup_page' => 'boolean',
    ];
}
