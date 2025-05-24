<?php

use Modules\Package\ViewComposers\Dashboard\{SubscriptionComposer,MostPopulerPackageComposer};

view()->composer([
    'user::dashboard.users.show',
    'package::dashboard.subscriptions.index',
    'package::dashboard.subscriptions.today-orders',
    'coupon::dashboard.*',
], \Modules\Package\ViewComposers\Dashboard\PackageComposer::class);

view()->composer([
    'apps::dashboard.index'
], SubscriptionComposer::class);

view()->composer([
    'apps::dashboard.index'
], MostPopulerPackageComposer::class);
