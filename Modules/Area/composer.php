<?php

view()->composer([
    'area::dashboard.cities.*',
    'area::dashboard.states.*',
    'catalog::dashboard.products.index',
    'catalog::dashboard.products.edit',
    'catalog::dashboard.products.create',
    'company::dashboard.companies.create',
    'company::dashboard.companies.edit',
    'package::dashboard.packages.*',
    'package::dashboard.offers.*',
    'order::dashboard.shared._filter',
    'user::dashboard.admins.*',
    'user::dashboard.users.*',
    'coupon::dashboard.*',
    'apps::dukaan.components.layouts.footer',
], \Modules\Area\ViewComposers\Dashboard\CountryComposer::class);

view()->composer([
    'apps::dashboard.install.country-setup',
    'setting::dashboard.index',
], \Modules\Area\ViewComposers\Dashboard\CurrencySettingComposer::class);


view()->composer([

    'order::dashboard.shared._filter',
],
    \Modules\Area\ViewComposers\Dashboard\CityComposer::class);


view()->composer([
    'user::frontend.profile.addresses.components.country-selector.selector'
],
    \Modules\Area\ViewComposers\FrontEnd\CountryComposer::class);

view()->composer([
    'user::frontend.profile.index',
],
    \Modules\Area\ViewComposers\FrontEnd\currencyComposer::class);
