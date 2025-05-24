<?php

/*use Modules\Vendor\Entities\Vendor;
function getDefaultVendor()
{
    if (isset(setting('other')['is_multi_vendors']) && setting('other')['is_multi_vendors'] == 0) {
        $vendorId = setting('default_vendor');
        if ($vendorId)
            return Vendor::find($vendorId);
        else
            return null;
    } else
        return null;
}*/

return [
    'name' => 'Core',
//    'default_vendor' => getDefaultVendor(),
];
