@if (Module::isEnabled('Vendor'))
            @if (\Auth::user()->can([
                'show_sellers',
                'show_vendors',
                'show_sections',
                'show_vendor_categories',
                'show_subscriptions',
            ]))
                <ul class="page-sidebar-menu  page-header-fixed" data-keep-expanded="false" data-auto-scroll="true"
                    data-slide-speed="200">
                    <li
                        class="nav-item  {{ active_slide_menu(['sellers', 'vendors', 'sections', 'vendor-categories', 'subscriptions']) }}">
                        <a href="javascript:;" class="nav-link nav-toggle">
                            <i class="fa fa-group"></i>
                            <span class="title">{{ __('apps::dashboard.aside.tab.vendors') }}</span>
                            <span class="arrow"></span>
                            <span class="selected"></span>
                        </a>
                        <ul class="sub-menu">

                            @permission('show_sellers')
                                <li class="nav-item {{ active_menu('sellers') }}" id="sideMenuVendorsSeller"
                                    style="display: {{ toggleSideMenuItemsByVendorType() }}">
                                    <a href="{{ url(route('dashboard.sellers.index')) }}" class="nav-link nav-toggle">
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        <span class="title">{{ __('apps::dashboard.aside.sellers') }}</span>
                                    </a>
                                </li>
                            @endpermission

                            @permission('show_vendors')
                                <li class="nav-item {{ active_menu('vendors') }}" id="sideMenuVendors"
                                    style="display: {{ toggleSideMenuItemsByVendorType() }}">
                                    <a href="{{ url(route('dashboard.vendors.index')) }}" class="nav-link nav-toggle">
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        <span class="title">{{ __('apps::dashboard.aside.vendors') }}</span>
                                    </a>
                                </li>
                            @endpermission

                            @permission('show_sections')
                                <li class="nav-item {{ active_menu('sections') }}" id="sideMenuVendorsSections"
                                    style="display: {{ toggleSideMenuItemsByVendorType() }}">
                                    <a href="{{ url(route('dashboard.sections.index')) }}" class="nav-link nav-toggle">
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        <span class="title">{{ __('apps::dashboard.aside.sections') }}</span>
                                    </a>
                                </li>
                            @endpermission

                            @permission('show_vendor_categories')
                                <li class="nav-item {{ active_menu('vendor-categories') }}"
                                    id="sideMenuVendorsCategories"
                                    style="display: {{ toggleSideMenuItemsByVendorType() }}">
                                    <a href="{{ url(route('dashboard.vendor_categories.index')) }}"
                                        class="nav-link nav-toggle">
                                        {{-- <i class="icon-briefcase"></i> --}}
                                        <span class="title">{{ __('apps::dashboard.aside.vendor_categories') }}</span>
                                    </a>
                                </li>
                            @endpermission

                            @if (setting('other.enable_subscriptions') == 1)
                                @if (Module::isEnabled('Subscription'))
                                    @permission('show_subscriptions')
                                        <li class="nav-item {{ active_menu('subscriptions') }}">
                                            <a href="{{ url(route('dashboard.subscriptions.index')) }}"
                                                class="nav-link nav-toggle">
                                                {{-- <i class="icon-briefcase"></i> --}}
                                                <span
                                                    class="title">{{ __('apps::dashboard.aside.subscriptions') }}</span>
                                            </a>
                                        </li>
                                    @endpermission


                                @endif
                            @endif

                        </ul>
                    </li>
                </ul>
            @endif
        @endif