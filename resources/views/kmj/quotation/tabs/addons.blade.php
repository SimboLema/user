@extends('kmj.layouts.app')

@section('title', 'Addons')

@section('content')

    <style>
        body {
            background-image: none !important;
            background-repeat: initial !important;
            background-position: initial !important;
            background-size: initial !important;
        }

        .btn-light {
            color: #ffffff !important;
            background-color: #003153 !important;
            border-color: #003153 !important;
        }

        .btn-light:hover {
            color: #001f33 !important;
            background-color: #001f33 !important;
            border-color: #001f33 !important;
        }
    </style>

    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content  flex-column-fluid ">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container  container-xxl ">



                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card body-->
                    <div class="card-body pt-6">

                        @include('kmj.quotation.components.tabs-nav')

                        {{-- <div class="mt-5">
                            <h3>Addons Section</h3>
                            <p>This is the Addons section of the quotation.</p>
                        </div> --}}

                        <!--begin::Addons Table-->
                        <div class="card mt-5">
                            <div class="card-header card-header-stretch">
                                <div class="card-title">
                                    <h3 class="m-0 text-gray-800">Addons</h3>
                                </div>

                            </div>

                            <div id="kt_addons_tab_content" class="tab-content">
                                @php
                                    $sampleAddons = [
                                        [
                                            'date' => 'Oct 05, 2025',
                                            'id' => 'ADD001',
                                            'detail' => 'Roadside assistance added',
                                            'type' => 'Safety Addon',
                                        ],
                                        [
                                            'date' => 'Sep 22, 2025',
                                            'id' => 'ADD002',
                                            'detail' => 'Zero depreciation cover',
                                            'type' => 'Premium Addon',
                                        ],
                                        [
                                            'date' => 'Aug 18, 2025',
                                            'id' => 'ADD003',
                                            'detail' => 'Passenger accident cover',
                                            'type' => 'Safety Addon',
                                        ],
                                        [
                                            'date' => 'Jul 12, 2025',
                                            'id' => 'ADD004',
                                            'detail' => 'Natural calamity cover',
                                            'type' => 'Premium Addon',
                                        ],
                                    ];
                                @endphp

                                @for ($i = 1; $i <= 4; $i++)
                                    <div id="kt_addons_{{ $i }}"
                                        class="card-body p-0 tab-pane fade @if ($i == 1) show active @endif"
                                        role="tabpanel">
                                        <div class="table-responsive">
                                            <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                                <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                                    <tr>
                                                        <th class="min-w-100px text-center">Date</th>
                                                        <th class="min-w-100px text-center">Addon ID</th>
                                                        <th class="min-w-100px text-center">Details</th>
                                                        <th class="min-w-100px text-center">Type</th>
                                                        <th class="min-w-100px text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($sampleAddons as $addon)
                                                        <tr class="text-gray-600 fs-6 fw-semibold text-center border-bottom-2">
                                                            <td>{{ $addon['date'] }}</td>
                                                            <td>{{ $addon['id'] }}</td>
                                                            <td>{{ $addon['detail'] }}</td>
                                                            <td>{{ $addon['type'] }}</td>
                                                            <td>
                                                                <button
                                                                    class="btn btn-light btn-sm btn-active-light-primary">View</button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <!--end::Addons Table-->

                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->


            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->

    </div>
    <!--end::Content wrapper-->

@endsection
