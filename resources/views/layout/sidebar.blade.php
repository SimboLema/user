<?php
  use App\Models\Setting;

  $settings = Setting::first();

?>



<!-- Menu -->

<aside id="layout-menu" class="layout-menu menu-vertical menu" >

    <div class="app-brand demo ">
      <a href="/dash" class="app-brand-link">

        @if($settings && $settings->system_logo)
                    <img class="logo" style="width:150px;height:150px;" src="{{ $settings && $settings->system_logo ? asset('storage/'.$settings->system_logo) : asset('assets/images/app-logo.svg') }}" alt="logo">

                @else
                <span class="app-brand-logo demo">
                    <span class="text-primary">
                    <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="currentColor" />
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="currentColor" />
                    </svg>
                    </span>
                </span>
            @endif
        {{-- <span class="app-brand-text demo menu-text fw-bold ms-3">{{ $settings->system_name ?? "TakaLink"}}</span> --}}
      </a>

      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
        <i class="icon-base ti menu-toggle-icon d-none d-xl-block"></i>
        <i class="icon-base ti tabler-x d-block d-xl-none"></i>
      </a>
    </div>

    <div class="menu-inner-shadow"></div>
    <ul class="menu-inner py-1">

        {{-- <li class="menu-item {{ Request::is('dashboard') ? 'active' : '' }}">
            <a href="/" class="menu-link">
              <i class="menu-icon icon-base ti tabler-smart-home"></i>
              <div data-i18n="Dashboards">Dashboard</div>
            </a>
        </li> --}}

        {{-- @if( Gate::check('show waste collection'))
        <li class="menu-item {{ Request::is('waste_collection*') ? 'active' : '' }}">
            <a href="/waste_collection" class="menu-link">
                <i class="menu-icon icon-base ti tabler-recycle"></i>
                <div data-i18n="Waste Collections">Waste Collections</div>
            </a>
        </li>
    @endif --}}

    {{-- @if( Gate::check('show recycling waste collection'))
        <li class="menu-item {{ Request::is('recycling_waste_collection*') ? 'active' : '' }}">
            <a href="/recycling_waste_collection" class="menu-link">
                <i class="menu-icon icon-base ti tabler-building-factory-2"></i>
                <div data-i18n="Facility Waste Collections">Facility Waste Collections</div>
            </a>
        </li>
    @endif

    @if( Gate::check('show recycling material'))
        <li class="menu-item {{ Request::is('recycling_material*') ? 'active' : '' }}">
            <a href="/recycling_material" class="menu-link">
                <i class="menu-icon icon-base ti tabler-package"></i>
                <div data-i18n="Recycled Material">Recycled Material</div>
            </a>
        </li>
    @endif --}}



        {{-- @if( Gate::check('show illegal dumping'))
            <li class="menu-item {{ Request::is('illegal_dumping') ? 'active' : '' }}">
                <a href="/illegal_dumping" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-trash"></i>
                <div data-i18n="Illegal Dumping">Illegal Dumping</div>
                </a>
            </li>
        @endif --}}


        {{-- @if( Gate::check('show inventory'))

        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Inventory">Inventory</span>
        </li>

        <li class="menu-item {{ Request::is('inventory_balance*') ? 'active' : '' }}">
            <a href="/inventory_balance" class="menu-link">
                <i class="menu-icon icon-base ti tabler-package"></i>
                <div data-i18n="Balance">Balance</div>
            </a>
        </li>

        @if( Gate::check('show product'))
        <li class="menu-item {{ Request::is('product*') ? 'active' : '' }}">
            <a href="/product" class="menu-link">
                <i class="menu-icon icon-base ti tabler-package"></i>
                <div data-i18n="Product Management">Product Management</div>
            </a>
        </li>
        @endif

        <li class="menu-item {{ Request::is('inventory_adjustment*') ? 'active' : '' }}">
            <a href="/inventory_adjustment" class="menu-link">
                <i class="menu-icon icon-base ti tabler-adjustments-horizontal"></i>

                <div data-i18n="Adjustment">Adjustment</div>
            </a>
        </li>

        @endif --}}



        @if( Gate::check('show recycling facility') ||
            Gate::check('show recycling facility user') ||
            Gate::check('show producer') ||
            Gate::check('show producer user') ||
            Gate::check('show waste picker') ||
            Gate::check('show collection center user') ||
            Gate::check('show collection center')
        )

        <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Peoples & Access">Peoples &amp; Access</span>
        </li>

        <li class="menu-item {{
            Request::is('recycling_facility_user') ||
            Request::is('recycling_facility') ||
            Request::is('producer_user') ||
            Request::is('producer') ||
            Request::is('waste_picker') ||
            Request::is('collection_center_user') ||
            Request::is('collection_center') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-building-factory"></i>
            <div data-i18n="Stake Holder">Stake Holder</div>
            </a>
            <ul class="menu-sub">

                @if( Gate::check('show collection center'))
                    <li class="menu-item {{ Request::is('collection_center') ? 'active' : '' }}">
                        <a href="/collection_center" class="menu-link">
                        <div data-i18n="Collection Centers">Collection Centers</div>
                        </a>
                    </li>
                @endif

                @if( Gate::check('show collection center user'))
                    <li class="menu-item {{ Request::is('collection_center_user') ? 'active' : '' }}">
                        <a href="/collection_center_user" class="menu-link">
                        <div data-i18n="Collectors">Collectors</div>
                        </a>
                    </li>
                @endif

                @if( Gate::check('show waste picker'))
                    <li class="menu-item {{ Request::is('waste_picker') ? 'active' : '' }}">
                        <a href="/waste_picker" class="menu-link">
                        <div data-i18n="Waste Picker">Waste Picker</div>
                        </a>
                    </li>
                @endif

                @if( Gate::check('show recycling facility'))
                    <li class="menu-item {{ Request::is('recycling_facility') ? 'active' : '' }}">
                        <a href="/recycling_facility" class="menu-link">
                        <div data-i18n="Recycling Facilities">Recycling Facilities</div>
                        </a>
                    </li>
                @endif

                @if( Gate::check('show recycling facility user'))
                    <li class="menu-item {{ Request::is('recycling_facility_user') ? 'active' : '' }}">
                        <a href="/recycling_facility_user" class="menu-link">
                        <div data-i18n="Recycle Agent">Recycle Agent</div>
                        </a>
                    </li>
                @endif

                @if( Gate::check('show production company'))
                    <li class="menu-item {{ Request::is('producer') ? 'active' : '' }}">
                        <a href="/producer" class="menu-link">
                        <div data-i18n="Production Company">Production Company</div>
                        </a>
                    </li>
                @endif

                @if( Gate::check('show producer'))
                    <li class="menu-item {{ Request::is('producer_user') ? 'active' : '' }}">
                        <a href="/producer_user" class="menu-link">
                        <div data-i18n="Producers">Producers</div>
                        </a>
                    </li>
                @endif


            </ul>
        </li>
        @endif

        @if( Gate::check('manage user') ||
            Gate::check('show role')
        )

        <li class="menu-item {{
            Request::is('user') ||
            Request::is('role')  ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon icon-base ti tabler-users"></i>
            <div data-i18n="Users & Roles">Users & Roles</div>
            </a>
            <ul class="menu-sub">

                @if( Gate::check('show user'))
                    <li class="menu-item {{ Request::is('user') ? 'active' : '' }}">
                        <a href="/user" class="menu-link">
                        <div data-i18n="Users">Users</div>
                        </a>
                    </li>
                @endif

                @if( Gate::check('show role'))
                    <li class="menu-item {{ Request::is('role') ? 'active' : '' }}">
                        <a href="/role" class="menu-link">
                        <div data-i18n="Roles">Roles</div>
                        </a>
                    </li>
                @endif


            </ul>
        </li>
        @endif


        <!-- More-->
        {{-- @if( Gate::check('show settings') ||
            Gate::check('show color') ||
            Gate::check('show country') ||
            Gate::check('show region') ||
            Gate::check('show smtp setting') ||
            Gate::check('show notification template') ||
            Gate::check('show email template') ||
            Gate::check('show pusher setting') ||
            Gate::check('show payment status') ||
            Gate::check('show sms setting')
        )
            <li class="menu-header small">
            <span class="menu-header-text" data-i18n="Settings & Configuration">Settings &amp; Configuration</span>
            </li>


            @if( Gate::check('show setting') ||
                Gate::check('show region') ||
                Gate::check('show smtp setting') ||
                Gate::check('show notification template') ||
                Gate::check('show email template') ||
                Gate::check('show pusher setting') ||
                Gate::check('show region') ||
                Gate::check('show district') ||
                Gate::check('show ward') ||
                Gate::check('show unit') ||
                Gate::check('show waste source') ||
                Gate::check('show waste type') ||
                Gate::check('show sms setting')
            )
            <li class="menu-item {{
                Request::is('setting') ||
                Request::is('sms') ||
                Request::is('pusher') ||
                Request::is('email_template') ||
                Request::is('notification_template') ||
                Request::is('payment-status') ||
                Request::is('smtp') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="icon-base ti tabler-settings icon-26px text-heading"></i>
                <div data-i18n="Settings">Settings</div>
                </a>
                <ul class="menu-sub">

                @if( Gate::check('show setting'))
                <li class="menu-item {{ Request::is('setting') ? 'active' : '' }}">
                    <a href="/setting" class="menu-link">
                    <div data-i18n="System Settings">System Settings</div>
                    </a>
                </li>
                @endif

                @if( Gate::check('show sms setting'))
                <li class="menu-item {{ Request::is('sms') ? 'active' : '' }}">
                    <a href="/sms" class="menu-link">
                    <div data-i18n="Sms Settings">Sms Settings</div>
                    </a>
                </li>
                @endif

                @if( Gate::check('show smtp setting'))
                <li class="menu-item {{ Request::is('smtp') ? 'active' : '' }}">
                    <a href="/smtp" class="menu-link">
                    <div data-i18n="Smtp Settings">Smtp Settings</div>
                    </a>
                </li>
                @endif



                @if( Gate::check('show pusher setting'))
                <li class="menu-item {{ Request::is('pusher') ? 'active' : '' }}">
                    <a href="/pusher" class="menu-link">
                    <div data-i18n="Pusher Settings">Pusher Settings</div>
                    </a>
                </li>
                @endif

                @if( Gate::check('show notification template'))
                <li class="menu-item {{ Request::is('notification_template') ? 'active' : '' }}">
                    <a href="/notification_template" class="menu-link">
                    <div data-i18n="Notification Template">Notification Template</div>
                    </a>
                </li>
                @endif

                @if( Gate::check('show email template'))
                <li class="menu-item {{ Request::is('email_template') ? 'active' : '' }}">
                    <a href="/email_template" class="menu-link">
                    <div data-i18n="Email Template">Email Template</div>
                    </a>
                </li>
                @endif



                </ul>
            </li>
            @endif --}}

            {{-- @if( Gate::check('show country') ||
                Gate::check('show color') ||
                Gate::check('show district') ||
                Gate::check('show ward') ||
                Gate::check('show unit') ||
                Gate::check('show waste source') ||
                Gate::check('show waste type') ||
                Gate::check('show payment status') ||
                Gate::check('show region')
            )

            <li class="menu-item {{
                Request::is('country') ||
                Request::is('color') ||
                Request::is('region') ||
                Request::is('district') ||
                Request::is('ward') ||
                Request::is('unit') ||
                Request::is('waste_source') ||
                Request::is('waste_type') ||
                Request::is('payment-status') ||
                Request::is('region') ? 'active open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-checkbox"></i>
                <div data-i18n="Configuration">Configuration</div>
                </a>
                <ul class="menu-sub">

                    @if( Gate::check('show unit'))
                        <li class="menu-item {{ Request::is('unit') ? 'active' : '' }}">
                            <a href="/unit" class="menu-link">
                            <div data-i18n="Units">Units</div>
                            </a>
                        </li>
                    @endif

                    @if( Gate::check('show color'))
                        <li class="menu-item {{ Request::is('color') ? 'active' : '' }}">
                            <a href="/color" class="menu-link">
                            <div data-i18n="Colors">Colors</div>
                            </a>
                        </li>
                    @endif

                @if( Gate::check('show waste source'))
                    <li class="menu-item {{ Request::is('waste_source') ? 'active' : '' }}">
                        <a href="/waste_source" class="menu-link">
                        <div data-i18n="Waste Source">Waste Source</div>
                        </a>
                    </li>
                @endif

                @if( Gate::check('show waste type'))
                    <li class="menu-item {{ Request::is('waste_type') ? 'active' : '' }}">
                        <a href="/waste_type" class="menu-link">
                        <div data-i18n="Waste Type">Waste Type</div>
                        </a>
                    </li>
                @endif

                @if( Gate::check('show payment status'))
                    <li class="menu-item {{ Request::is('payment-status') ? 'active' : '' }}">
                        <a href="/payment-status" class="menu-link">
                        <div data-i18n="Payment Status">Payment Status</div>
                        </a>
                    </li>
                @endif
                @if( Gate::check('show country'))
                    <li class="menu-item {{ Request::is('country') ? 'active' : '' }}">
                        <a href="/country" class="menu-link">
                        <div data-i18n="Country">Country</div>
                        </a>
                    </li>
                @endif

                @if( Gate::check('show region'))
                    <li class="menu-item {{ Request::is('region') ? 'active' : '' }}">
                        <a href="/region" class="menu-link">
                        <div data-i18n="Region">Region</div>
                        </a>
                    </li>
                @endif

                @if( Gate::check('show district'))
                    <li class="menu-item {{ Request::is('district') ? 'active' : '' }}">
                        <a href="/district" class="menu-link">
                        <div data-i18n="District">District</div>
                        </a>
                    </li>
                @endif

                @if( Gate::check('show ward'))
                    <li class="menu-item {{ Request::is('ward') ? 'active' : '' }}">
                        <a href="/ward" class="menu-link">
                        <div data-i18n="Ward">Ward</div>
                        </a>
                    </li>
                @endif





                </ul>
            </li>
            @endif --}}
        {{-- @endif --}}





    </ul>


</aside>
