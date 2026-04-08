@php
    $tabs = [
        ['name' => 'Notification', 'route' => 'claim.notification'],
        ['name' => 'Intimation', 'route' => 'claim.intimation'],
        ['name' => 'Assessment', 'route' => 'claim.assessment'],
        ['name' => 'Discharge Voucher', 'route' => 'claim.discharge.voucher'],
        ['name' => 'Payment', 'route' => 'claim.payment'],
        ['name' => 'Rejection', 'route' => 'claim.rejection'],
    ];
@endphp

<div id="kt_user_profile_nav" class="rounded bg-gray-200 d-flex flex-stack flex-wrap mb-9 p-2 justify-content-end" data-kt-sticky="true"
    data-kt-sticky-name="sticky-profile-navs" data-kt-sticky-offset="{default: false, lg: '400px'}"
    data-kt-sticky-width="{target: '#kt_user_profile_panel'}" data-kt-sticky-left="auto" data-kt-sticky-top="80px"
    data-kt-sticky-animation="true" data-kt-sticky-zindex="95">

    <ul class="nav flex-wrap border-transparent">
        @foreach ($tabs as $tab)
            @php
                $isActive = request()->routeIs($tab['route']) ? 'active' : '';
            @endphp
            <li class="nav-item my-1">
                <a href="{{ route($tab['route'], [$claim->id]) }}"
                    class="btn btn-sm btn-color-gray-600 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1 {{ $isActive }}">
                    {{ $tab['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
