@php
    $tabs = [
        [
            'name' => $quotation->status === 'pending' ? 'Quotation' : 'CoverNote',
            'route' => 'kmj.quotation.covernote',
        ],
        ['name' => 'Payments', 'route' => 'kmj.quotation.payment'],
        // ['name' => 'Transactions', 'route' => 'kmj.quotation.transaction'],
        ['name' => 'Addons', 'route' => 'kmj.quotation.addons'],
        ['name' => 'Customer', 'route' => 'kmj.quotation.customer'],
        ['name' => 'Motor Details', 'route' => 'kmj.quotation.motorDetails'],
        ['name' => 'Documents', 'route' => 'kmj.quotation.documents'],
        ['name' => 'Endorsements', 'route' => 'kmj.quotation.endorsement'],
    ];
@endphp

<div id="kt_user_profile_nav" class="rounded  d-flex flex-stack flex-wrap mb-9 p-2 justify-content-end" data-kt-sticky="true"  style="background-color:#9aa89b"
    data-kt-sticky-name="sticky-profile-navs" data-kt-sticky-offset="{default: false, lg: '400px'}"
    data-kt-sticky-width="{target: '#kt_user_profile_panel'}" data-kt-sticky-left="auto" data-kt-sticky-top="80px"
    data-kt-sticky-animation="true" data-kt-sticky-zindex="95">

    <ul class="nav flex-wrap border-transparent">
        @foreach ($tabs as $tab)
            @php
                $isActive = request()->routeIs($tab['route']) ? 'active' : '';
            @endphp
            <li class="nav-item my-1">
                <a href="{{ route($tab['route'], [$quotation->id]) }}"
                    class="btn btn-sm btn-color-gray-200 bg-state-body btn-active-color-gray-800 fw-bolder fw-bold fs-6 fs-lg-base nav-link px-3 px-lg-4 mx-1 {{ $isActive }}">
                    {{ $tab['name'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
