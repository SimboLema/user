@extends('kmj.layouts.app')

@section('title', 'Documents')

@section('content')

    <style>
        body {
            background-image: none !important;
            background-repeat: initial !important;
            background-position: initial !important;
            background-size: initial !important;
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
                            <h3>Documents Section</h3>
                            <p>This is the Documents section of the quotation.</p>
                        </div> --}}

                        <!--begin::Documents toolbar-->
                        <div class="d-flex flex-wrap flex-stack mb-6">
                            <!--begin::Title-->
                            <h3 class="fw-bold my-2">
                                Uploaded Documents
                                {{-- <span class="fs-6 text-gray-500 fw-semibold ms-1">4 resources</span> --}}
                            </h3>
                            <!--end::Title-->

                        </div>
                        <!--end::Documents toolbar-->


                        <!--begin::Row-->
                        <div class="row g-6 g-xl-9 mb-6 mb-xl-9">


                            <!--begin::Col-->
                            @if ($quotationDocuments->isEmpty())
                                <div class="col-12 text-center py-10">
                                    <span class="text-gray-600 fw-bold fs-5">No uploaded documents</span>
                                </div>
                            @else
                                @foreach ($quotationDocuments as $qD)
                                    <div class="col-md-6 col-lg-3 col-xl-3">
                                        <!--begin::Card-->
                                        <div class="card h-100 ">
                                            <!--begin::Card body-->
                                            <div
                                                class="card-body d-flex justify-content-center text-center flex-column p-8">
                                                <!--begin::Name-->
                                                <img href="#"
                                                    class="text-gray-800 text-hover-primary d-flex flex-column">
                                                <!--begin::Image-->
                                                {{--                                                <div class="symbol symbol-75px mb-5"> --}}
                                                {{--                                                    <img src="{{ url($qD->file_path) }}" --}}
                                                {{--                                                         class="theme-light-show" alt=""/> --}}
                                                {{--                                                    <img src="{{ asset('assets/dash/board_files/doc.svg') }}" --}}
                                                {{--                                                         class="theme-dark-show" alt=""/> --}}

                                                {{--                                                </div> --}}

                                                <div class="symbol symbol-75px mb-5">
                                                    @php
                                                        $filePath = $qD->file_path;
                                                        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
                                                        $isImage = in_array(strtolower($extension), [
                                                            'webp',
                                                            'jpg',
                                                            'jpeg',
                                                            'png',
                                                        ]);
                                                    @endphp

                                                    @if ($isImage)
                                                        <img src="{{ asset($filePath) }}" class="theme-light-show"
                                                            alt="{{ $qD->name }}" />
                                                        <img src="{{ asset($filePath) }}" class="theme-dark-show"
                                                            alt="{{ $qD->name }}" />
                                                    @else
                                                        <img src="{{ asset('assets/dash/board_files/doc.svg') }}"
                                                            class="theme-light-show" />
                                                    @endif
                                                </div>
                                                <!--end::Image-->

                                                <!--begin::Title-->
                                                <div class="fs-5 fw-bold mb-2">
                                                    {{ $qD->name }}
                                                </div>
                                                <!--end::Title-->
                                                </a>
                                                <!--end::Name-->

                                                <!--begin::Description-->
                                                <div class="fs-7 fw-semibold text-gray-500">
                                                    1 files
                                                </div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Card body-->
                                        </div>
                                        <!--end::Card-->
                                    </div>
                                    <!--end::Col-->
                                @endforeach
                            @endif


                        </div>
                        <!--end:Row-->


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
