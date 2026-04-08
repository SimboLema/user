@extends('kmj.layouts.app')

@section('title', 'Policy Submission')

@section('content')

    <style>
        body {
            background-image: none !important;
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

    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body pt-6">

                        <div class="card mt-5">
                            <div class="card-header card-header-stretch">
                                <div class="card-title">
                                    <h3 class="m-0 text-gray-800">Policy Submissions</h3>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm align-self-center"
                                        style="background-color: #003153; color: white;" data-bs-toggle="modal"
                                        data-bs-target="#createPolicyModal">
                                        <i class="fas fa-plus text-white"></i> Create Policy Submission
                                    </button>
                                </div>
                            </div>

                            <!-- Create Policy Submission Modal -->
                            <div class="modal fade" id="createPolicyModal" tabindex="-1"
                                aria-labelledby="createPolicyModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content">
                                        <form id="createPolicyForm" action="{{ route('policy.submission.save') }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="createPolicyModalLabel">Create New Policy
                                                    Submission</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label">Policy Number</label>
                                                        <input type="text" name="policy_number" class="form-control"
                                                            required>
                                                    </div>

                                                    <div class="col-md-8">
                                                        <label class="form-label">Policy Operative Clause</label>
                                                        <input type="text" name="policy_operative_clause"
                                                            class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Special Conditions</label>
                                                        <textarea name="special_conditions" class="form-control" rows="2"></textarea>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">Exclusions</label>
                                                        <textarea name="exclusions" class="form-control" rows="2"></textarea>
                                                    </div>

                                                    <div class="col-md-12 mt-3">
                                                        <label class="form-label">Cover Note Reference Numbers</label>
                                                        <div id="covernote-container"></div>
                                                        <button type="button" class="btn btn-outline-primary mt-2"
                                                            id="add-covernote-btn">
                                                            + Add Cover Note
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" id="submitBtn" class="btn btn-light">
                                                    <span id="submitText">Save Policy Submission</span>
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
                            <div class="tab-content mt-4">
                                <div class="card-body p-0 tab-pane fade show active">
                                    <div class="table-responsive">
                                        <table class="table align-middle table-row-bordered table-row-solid gy-4 gs-9">
                                            <thead class="border-gray-200 fs-5 fw-semibold bg-lighten text-center">
                                                <tr>
                                                    <th>Policy Number</th>
                                                    <th>Operative Clause</th>
                                                    <th>Status</th>
                                                    <th>Ack Code</th>
                                                    <th>Ack Desc</th>
                                                    <th>Resp Code</th>
                                                    <th>Resp Desc</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($policySubmissions as $policy)
                                                    <tr class="text-center text-gray-600 fw-semibold">
                                                        <td>{{ $policy->policy_number }}</td>
                                                        <td>{{ Str::limit($policy->policy_operative_clause, 30) }}</td>
                                                        <td>
                                                            <span class="badge border border-info text-info">
                                                                {{ ucfirst($policy->status ?? 'pending') }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $policy->acknowledgement_status_code }}</td>
                                                        <td>{{ $policy->acknowledgement_status_desc }}</td>
                                                        <td>{{ $policy->response_status_code }}</td>
                                                        <td>{{ $policy->response_status_desc }}</td>
                                                        <td>
                                                            @if ($policy->status === 'pending')
                                                                <a href="{{ route('policy.submission.sendTira', $policy->id) }}"
                                                                    class="btn p-0 border-0 bg-transparent shadow-none align-self-center"
                                                                    title="Send TIRA">
                                                                    <img src="{{ asset('assets/dash/board_files/TIRAlogo.png') }}"
                                                                        alt="TIRA Logo" style="width: 35px; height: auto;">
                                                                </a>
                                                            @elseif($policy->status === 'success')
                                                                <span class="badge border border-success text-success">
                                                                    Risk Issued
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr class="text-center">
                                                        <td colspan="8">No Policy Submissions found.</td>
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
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(function() {
            let coverNoteIndex = 0;

            $('#createPolicyModal').on('shown.bs.modal', function() {
                $('#add-covernote-btn').off('click.covernote').on('click.covernote', function() {
                    coverNoteIndex++;
                    const idx = coverNoteIndex;
                    const card = $(`
                    <div class="input-group mt-2 covernote-card" data-idx="${idx}">
                        <input type="text" name="cover_note_reference_numbers[]" class="form-control" placeholder="Cover Note Ref #">
                        <button type="button" class="btn btn-danger remove-covernote">Remove</button>
                    </div>
                `);
                    $('#covernote-container').append(card);
                });

                $('#covernote-container').off('click.remove').on('click.remove', '.remove-covernote',
                    function() {
                        $(this).closest('.covernote-card').remove();
                    });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('createPolicyForm');
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
                    submitText.textContent = 'Save Policy Submission';
                    submitSpinner.style.display = 'none';
                }, 2000);
            });
        });
    </script>

@endsection
