<?php

namespace Modules\Core\Packages\Spatie\Media;

use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PathGenerator extends DefaultPathGenerator
{
    

    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        $tenant = app('currentTenant');
        $prefix = config('media-library.prefix', '') 
            . ($tenant ? "/{$tenant->subdomain}" : '') 
            . '/' . Carbon::parse($media->created_at)->year 
            . '/' . Carbon::parse($media->created_at)->month 
            . '/' . Carbon::parse($media->created_at)->day;

        if ($prefix !== '') {
            return $prefix . '/' . $media->getKey();
        }

        return $media->getKey();
    }
}
