<!-- Cover Note Search Modal -->
<div class="modal fade" id="coverNoteModal" tabindex="-1" aria-labelledby="coverNoteLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color:#003153;">
                <h5 class="modal-title" id="coverNoteLabel"><i class="bi bi-file-earmark-text me-2"></i>Search Cover Note
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search Input -->
                <div class="mb-3 position-relative">
                    <input type="text" id="coverNoteSearch" class="form-control"
                        placeholder="Search by Cover Note Reference">
                    <div id="spinner" class="spinner-border position-absolute top-50 end-0 translate-middle-y me-2"
                        role="status"
                        style="display: none; width: 2rem; height: 2rem; border-width: 0.25em; border-top-color: #9aa89b; border-right-color: transparent; border-bottom-color: transparent; border-left-color: transparent;">
                    </div>
                </div>

                <!-- Table for results -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="coverNoteResults">
                        <thead class="table-light">
                            <tr>
                                <th>Cover Note Reference</th>
                                <th>Customer Name</th>
                                <th>Total Premium</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Results loaded via AJAX -->
                        </tbody>
                    </table>
                </div>

                <div id="noResults" class="text-center text-muted mt-3" style="display: none;">
                    No cover notes found.
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        function fetchCoverNotes(query = '') {
            $('#spinner').show();

            $.ajax({
                url: '/dash/quotations/search', // route ya search
                method: 'GET',
                data: {
                    q: query
                },
                success: function(data) {
                    const tbody = $('#coverNoteResults tbody');
                    tbody.empty();

                    if (data.length === 0) {
                        $('#noResults').show();
                    } else {
                        $('#noResults').hide();
                        data.forEach(item => {
                            const row = `<tr>
                            <td>${item.cover_note_reference}</td>
                            <td>${item.customer_name}</td>
                            <td>${item.total_premium_including_tax}</td>
                            <td>
                                <button class="btn btn-sm select-cover-note" style="background-color: #003153;color:white"
                                        data-quotation-id="${item.id}" data-cover-note="${item.cover_note_reference}">
                                    Select
                                </button>
                            </td>
                        </tr>`;
                            tbody.append(row);
                        });
                    }
                },
                error: function() {
                    $('#noResults').show();
                },
                complete: function() {
                    $('#spinner').hide();
                }
            });
        }

        // Fetch initial
        fetchCoverNotes();

        // Input search
        $('#coverNoteSearch').on('input', function() {
            fetchCoverNotes($(this).val());
        });

        // Select cover note
        $(document).on('click', '.select-cover-note', function() {
            const quotationId = $(this).data('quotation-id');
            const coverNoteRef = $(this).data('cover-note');

            // Close modal
            const modalEl = document.getElementById('coverNoteModal');
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);
            modalInstance.hide();

            // Redirect to next page with quotation ID
            window.location.href =
                `/dash/claim-notifications/create/${quotationId}?cover_note=${coverNoteRef}`;
        });

    });
</script>
