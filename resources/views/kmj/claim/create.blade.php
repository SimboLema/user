@extends('kmj.layouts.app')

@section('title', 'Claim Notification')

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
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">

                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card body-->
                    <div class="card-body pt-6">




                        <!--begin::Endorsements Table-->
                        <div class="card mt-5">
                            <div class="card-header card-header-stretch">
                                <div class="card-title">
                                    <h3 class="m-0 text-gray-800">Claim Notification</h3>
                                </div>
                                <div class="d-flex align-items-center gap-2">

                                    <button type="button" class="btn btn-sm align-self-center"
                                        style="background-color: #003153; color: white;" data-bs-toggle="modal"
                                        data-bs-target="#createNotificationModal"> <i class="fas fa-plus text-white"></i>
                                        Create Notification
                                    </button>
                                </div>
                            </div>

                            <!-- Create Notification Modal -->
                            <div class="modal fade" id="createNotificationModal" tabindex="-1"
                                aria-labelledby="createNotificationModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <form id="createNotificationFomr"
                                            action="{{ route('kmj.claimNotification.store') }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="createNotificationModalLabel">Create New
                                                    Notification</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">

                                                <input type="hidden" name="quotation_id" value="{{ $quotation->id }}">

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="claim_notification_number"
                                                            class="form-label">Notification Number</label>
                                                        <input type="text" name="claim_notification_number"
                                                            id="claim_notification_number" class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="claim_report_date" class="form-label">Report
                                                            Date</label>
                                                        <input type="date" name="claim_report_date"
                                                            id="claim_report_date" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="claim_form_dully_filled" class="form-label">Form Duly
                                                            Filled</label>
                                                        <select name="claim_form_dully_filled" id="claim_form_dully_filled"
                                                            class="form-control" required>
                                                            <option value="" disabled selected>Select Yes or No
                                                            </option>
                                                            <option value="Y">Yes</option>
                                                            <option value="N">No</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="loss_date" class="form-label">Loss Date</label>
                                                        <input type="date" name="loss_date" id="loss_date"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="loss_nature" class="form-label">Loss Nature</label>
                                                        <input type="text" name="loss_nature" id="loss_nature"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="loss_type" class="form-label">Loss Type</label>
                                                        <input type="text" name="loss_type" id="loss_type"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="loss_location" class="form-label">Loss Location</label>
                                                        <input type="text" name="loss_location" id="loss_location"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="officer_name" class="form-label">Officer Name</label>
                                                        <input type="text" name="officer_name" id="officer_name"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="officer_title" class="form-label">Officer Title</label>
                                                        <input type="text" name="officer_title" id="officer_title"
                                                            class="form-control" required>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" id="submitBtn" class="btn btn-light">
                                                    <i class="bi bi-check-circle me-2 text-white"></i>
                                                    <span id="submitText">Save Notification</span>
                                                    <span id="submitSpinner"
                                                        class="spinner-border spinner-border-sm text-light ms-2"
                                                        style="display: none;"></span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>



                            <div id="kt_endorsements_tab_content" class="tab-content">

                                <div id="kt_endorsements_tab_pane_1" class="card-body p-0 tab-pane fade show active"
                                    role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten">
                                                <tr>
                                                    <th class="min-w-100px text-center">Date</th>
                                                    <th class="min-w-100px text-center">Claim Notification #</th>
                                                    <th class="min-w-100px text-center">Loss Date</th>
                                                    <th class="min-w-100px text-center">Loss Type</th>
                                                    <th class="min-w-100px text-center">Officer</th>
                                                    <th class="min-w-100px text-center">Status</th>
                                                    <th class="min-w-100px text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($claims as $claim)
                                                    <tr class="text-gray-600 fs-6 fw-semibold text-center border-bottom-2">
                                                        <td>{{ \Carbon\Carbon::parse($claim->claim_report_date)->format('d-m-Y') }}
                                                        </td>
                                                        <td>{{ $claim->claim_notification_number }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($claim->loss_date)->format('d-m-Y') }}
                                                        </td>
                                                        <td>{{ $claim->loss_type }}</td>
                                                        <td>{{ $claim->officer_name }}</td>
                                                        <td>
                                                            @if ($claim->claim_notification_status == 'pending')
                                                                <span class="badge bg-warning">Pending</span>
                                                            @elseif($claim->claim_notification_status == 'approved')
                                                                <span class="badge bg-success">Approved</span>
                                                            @else
                                                                <span class="badge bg-danger">Rejected</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="#"
                                                                class="btn btn-sm btn-primary">
                                                                View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">No claims found
                                                            for this quotation.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Endorsements Table-->

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




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('createNotificationFomr');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');

            form.addEventListener('submit', function(e) {

                // Prevent double submission
                if (submitBtn.disabled) {
                    e.preventDefault();
                    return;
                }

                // Disable button and show loader
                submitBtn.disabled = true;
                submitText.textContent = 'Submitting...';
                submitSpinner.style.display = 'inline-block';

                // Just simulate loading (you can remove this timeout if you want)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitText.textContent = 'Save Endorsement';
                    submitSpinner.style.display = 'none';
                    console.log('Form submitted (loader demo only)');
                }, 2000);
            });
        });
    </script>



@endsection
