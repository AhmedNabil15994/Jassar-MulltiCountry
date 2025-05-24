<?php

use App\Tenancy\Models\Tenant;

if (! function_exists('tenant_url')) {
    /**
     * Generate a url for the application.
     *
     * @param  string|null  $path
     * @param  mixed  $parameters
     * @param  bool|null  $secure
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function tenant_url($path = null, $parameters = [], $secure = null)
    {
        if ($path && strpos($path, 'storage/') !== false && Tenant::checkCurrent()) {
            $path = str_replace('storage/', 'storage/t/' . Tenant::current()->id . '/', $path);
        }

        return url($path, $parameters, $secure);
    }
}
