<?php

namespace Modules\DeveloperTools\Entities;

use Illuminate\Database\Eloquent\Model;

class SiteColor extends Model
{

    protected $fillable = ["json", "css"];
}
