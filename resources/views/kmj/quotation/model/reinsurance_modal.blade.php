<!-- Reinsurance Modal -->
<div class="modal fade" id="reinsuranceModal" tabindex="-1" aria-labelledby="reinsuranceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#003153; color:white;">
                <h5 class="modal-title">Create Reinsurance</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <form id="reinsuranceForm">
                @csrf
                <input type="hidden" name="quotation_id" id="quotation_id">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Currency</label>
                            <select name="currency_id" id="currencySelect" class="form-select">
                                <option value="">-- Select --</option>
                                @foreach ($currencies as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Reinsurance Category</label>
                            <select name="reinsurance_category_id" id="categorySelect" class="form-select">
                                <option value="">-- Select --</option>
                                @foreach ($reinsurance_categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Exchange Rate</label>
                            <input type="text" name="exchange_rate" class="form-control" value="1.00">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Authorizing Officer Name</label>
                            <input type="text" name="authorizing_officer_name" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Authorizing Officer Title</label>
                            <input type="text" name="authorizing_officer_title" class="form-control">
                        </div>
                    </div>

                    <hr>
                    <h6>Participants</h6>
                    <table class="table table-bordered" id="participantsTable">
                        <thead>
                            <tr style="background:#f2f2f2;">
                                <th>Code</th>
                                <th>Type</th>
                                <th>Form</th>
                                <th>Type</th>
                                <th>Rebroker code</th>
                                <th>Brokerage Commission</th>
                                <th>Reinsurance Commission</th>
                                <th>Premium Share</th>
                                <th>
                                    <button type="button" class="btn btn-sm btn-primary" id="addRow">+</button>
                                </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="saveReinsurance">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const reinsuranceModal = document.getElementById('reinsuranceModal');

        // Capture quotation ID
        reinsuranceModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const quotationId = button.getAttribute('data-quotation-id');
            document.getElementById('quotation_id').value = quotationId;
        });

        // Add participant row
        document.getElementById('addRow').addEventListener('click', function() {
            const tbody = document.querySelector('#participantsTable tbody');
            const row = document.createElement('tr');

            row.innerHTML = `
            <td><input type="text" class="form-control participant_code"></td>
            <td>
                <select class="form-select participant_type_id">
                    <option value="">--</option>
                    @foreach ($participant_types as $pt)
                        <option value="{{ $pt->id }}">{{ $pt->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select class="form-select reinsurance_form_id">
                    <option value="">--</option>
                    @foreach ($reinsurance_forms as $rf)
                        <option value="{{ $rf->id }}">{{ $rf->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select class="form-select reinsurance_type_id">
                    <option value="">--</option>
                    @foreach ($reinsurance_types as $rt)
                        <option value="{{ $rt->id }}">{{ $rt->name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="text" class="form-control rebroker_code"></td>
            <td><input type="text" class="form-control brokerage_commission"></td>
            <td><input type="text" class="form-control reinsurance_commission"></td>
            <td><input type="text" class="form-control premium_share"></td>
            <td><button type="button" class="btn btn-danger btn-sm removeRow">x</button></td>
        `;
            tbody.appendChild(row);
        });

        // Remove participant row
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('removeRow')) {
                e.target.closest('tr').remove();
            }
        });

        // Save form
        // Save form
        document.getElementById('saveReinsurance').addEventListener('click', function() {
            const form = document.getElementById('reinsuranceForm');

            // Build participants array properly
            const participants = [];
            document.querySelectorAll('#participantsTable tbody tr').forEach(row => {
                participants.push({
                    participant_code: row.querySelector('.participant_code').value,
                    participant_type_id: row.querySelector('.participant_type_id')
                        .value,
                    reinsurance_form_id: row.querySelector('.reinsurance_form_id')
                        .value,
                    reinsurance_type_id: row.querySelector('.reinsurance_type_id')
                        .value,
                    rebroker_code: row.querySelector('.rebroker_code').value,
                    brokerage_commission: row.querySelector('.brokerage_commission')
                        .value,
                    reinsurance_commission: row.querySelector('.reinsurance_commission')
                        .value,
                    premium_share: row.querySelector('.premium_share').value
                });
            });

            // Build full payload
            const payload = {
                _token: document.querySelector('input[name="_token"]').value,
                quotation_id: document.getElementById('quotation_id').value,
                currency_id: document.getElementById('currencySelect').value,
                reinsurance_category_id: document.getElementById('categorySelect').value,
                exchange_rate: form.querySelector('input[name="exchange_rate"]').value,
                authorizing_officer_name: form.querySelector(
                    'input[name="authorizing_officer_name"]').value,
                authorizing_officer_title: form.querySelector(
                    'input[name="authorizing_officer_title"]').value,
                reinsurance_details: participants // <-- Hapa ni array halisi
            };

            fetch('{{ route('kmj.reinsurance.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json', // JSON
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ ' + data.message);
                        const modal = bootstrap.Modal.getInstance(reinsuranceModal);
                        modal.hide();
                        location.reload();
                    } else {
                        alert('❌ ' + data.message);
                    }
                })
                .catch(err => alert(err));
        });

    });
</script>
