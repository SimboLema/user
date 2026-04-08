@extends('kmj.layouts.app')

@section('title', 'Motor Details')

@section('content')

    <style>
        body {
            background-image: none !important;
        }
    </style>

    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <div class="card">
                    <div class="card-body pt-6">

                        @include('kmj.quotation.components.tabs-nav')

                        {{-- <div class="mt-5">
                            <h3>Motor Details Section</h3>
                            <p>This is the Motor Details section of the quotation.</p>
                        </div> --}}

                        <!--begin::Motor Info-->
                        <div class="card mb-5 mb-xl-10">
                            <div class="card-header border-0 cursor-pointer">
                                <div class="card-title m-0">
                                    <h3 class="fw-bold m-0">Motor Details</h3>
                                </div>
                            </div>

                            <div id="motor_details" class="collapse show">
                                <div class="card-body border-top p-9">

                                    <!--begin::Avatar-->
                                    {{-- <div class="row mb-6">
                                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Motor Image</label>
                                        <div class="col-lg-8">
                                            <div class="image-input image-input-outline" data-kt-image-input="true"
                                                style="background-image: url('../assets/media/svg/avatars/blank.svg')">
                                                <div class="image-input-wrapper w-125px h-125px"
                                                    style="background-image: url(https://preview.keenthemes.com/keen/demo3/assets/media/avatars/300-1.jpg)">
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <!--end::Avatar-->

                                    @php
                                        $motor = [
                                            'category' => $quotation->motorCategory->name ?? '',
                                            'type' => $quotation->motorType->name ?? '',
                                            'registration' => $quotation->registration_number,
                                            'chassis' => $quotation->chassis_number,
                                            'make' => $quotation->make,
                                            'model' => $quotation->model,
                                            'body_type' => $quotation->body_type,
                                            'color' => $quotation->color,
                                            'engine_number' => $quotation->engine_number,
                                            'engine_capacity' => $quotation->engine_capacity,
                                            'fuel' => $quotation->fuel_used,
                                            'axles' =>  $quotation->number_of_axles,
                                            'axle_distance' =>  $quotation->axle_distance,
                                            'sitting_capacity' => $quotation->sitting_capacity,
                                            'year' => $quotation->year_of_manufacture,
                                            'owner_name' => ucwords(strtolower($quotation->owner_name)),
                                            'owner_address' => $quotation->owner_address,
                                        ];
                                    @endphp

                                    @foreach (array_chunk($motor, 3, true) as $chunk)
                                        <div class="row mb-6">
                                            @foreach ($chunk as $key => $value)
                                                <div class="col-md-4">
                                                    <label class="col-form-label fw-semibold fs-6">
                                                        {{ ucwords(str_replace('_', ' ', $key)) }}
                                                    </label>
                                                    <input type="text"
                                                           class="form-control form-control-lg form-control-solid"
                                                           value="{{ $value }}" readonly/>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                        <!--end::Motor Info-->

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
