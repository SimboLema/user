@extends('kmj.layouts.app')

@section('title', 'Cover Note Verification')

@section('content')

    <style>
          body {
            background-image: none !important;
            background-repeat: initial !important;
            background-position: initial !important;
            background-size: initial !important;
        }

        .btn-light:hover {
            background-color: #001f33 !important;
            border-color: #001f33 !important;
        }
    </style>

    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body pt-6">
                        <div class="card mt-5">
                            <div class="card-header card-header-stretch">
                                <div class="card-title">
                                    <h3 class="m-0 text-gray-800">Cover Note Verifications</h3>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm align-self-center"
                                        style="background-color: #003153; color: white;" data-bs-toggle="modal"
                                        data-bs-target="#createCoverNoteModal">
                                        <i class="fas fa-plus text-white"></i> New Verification
                                    </button>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="createCoverNoteModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <form id="createCoverNoteForm" action="{{ route('covernote.verification.save') }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Create Cover Note Verification</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Cover Note Reference Number</label>
                                                        <input type="text" name="cover_note_reference_number"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Sticker Number</label>
                                                        <input type="text" name="sticker_number" class="form-control"
                                                            required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Motor Registration Number</label>
                                                        <input type="text" name="motor_registration_number"
                                                            class="form-control" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Motor Chassis Number</label>
                                                        <input type="text" name="motor_chassis_number"
                                                            class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" id="submitBtn" class="btn btn-light">
                                                    <span id="submitText">Save Verification</span>
                                                    <span id="submitSpinner"
                                                        class="spinner-border spinner-border-sm text-light ms-2"
                                                        style="display: none;"></span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive mt-4">
                                <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                    <thead class="border-gray-200 fs-5 fw-semibold bg-lighten text-center">
                                        <tr>
                                            <th>Cover Note Ref</th>
                                            <th>Sticker Number</th>
                                            <th>Reg No</th>
                                            <th>Chassis</th>
                                            <th>Status</th>
                                            <th>Resp Code</th>
                                            <th>Resp Desc</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($coverNotes as $note)
                                            <tr class="text-center text-gray-600 fw-semibold">
                                                <td>{{ $note->cover_note_reference_number }}</td>
                                                <td>{{ $note->sticker_number }}</td>
                                                <td>{{ $note->motor_registration_number }}</td>
                                                <td>{{ $note->motor_chassis_number }}</td>
                                                <td>
                                                    <span class="badge border border-info text-info">
                                                        {{ ucfirst($note->status ?? 'pending') }}
                                                    </span>
                                                </td>
                                                <td>{{ $note->response_status_code }}</td>
                                                <td>{{ $note->response_status_desc }}</td>
                                                <td>
                                                    @if ($note->status === 'pending')
                                                        <a href="{{ route('covernote.verification.sendTira', $note->id) }}"
                                                            class="btn p-0 border-0 bg-transparent shadow-none align-self-center"
                                                            title="Send TIRA">
                                                            <img src="{{ asset('assets/dash/board_files/TIRAlogo.png') }}"
                                                                alt="TIRA Logo" style="width: 35px;">
                                                        </a>
                                                    @elseif($note->status === 'success')
                                                        <span class="badge border border-success text-success">
                                                            Verified
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="text-center">
                                                <td colspan="10">No Cover Note Verifications found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('createCoverNoteForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');

            form.addEventListener('submit', function(e) {
                if (submitBtn.disabled) {
                    e.preventDefault();
                    return;
                }
                submitBtn.disabled = true;
                submitText.textContent = 'Submitting...';
                submitSpinner.style.display = 'inline-block';

                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitText.textContent = 'Save Verification';
                    submitSpinner.style.display = 'none';
                }, 2000);
            });
        });
    </script>

@endsection
