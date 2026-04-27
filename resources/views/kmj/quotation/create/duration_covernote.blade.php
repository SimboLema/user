<div class="step-content d-none">
    <h5 class="step-title"><i class="bi bi-box-seam me-2"></i>Duration and Cover
        Note
        Information</h5>
    <div class="row g-3">
        {{-- <div class="col-md-6">
            <label class="form-label required-field">Cover Note Type</label>
            <select class="form-select" name="cover_note_type_id"
                    id="cover_note_type_id"
                    required>
                <option value="">Select Cover Note Type</option>
                @foreach ($coverNoteTypes as $coverNoteType)
                    <option
                        value="{{ $coverNoteType->id }}">{{ $coverNoteType->name }}
                    </option>
                @endforeach
            </select>
        </div> --}}

        <div class="col-md-6">
            <label class="form-label">Cover Note Duration (months)</label>
            <select class="form-select" name="cover_note_duration_id"
                    id="cover_note_duration_id" required>
                <option value="">Select Cover Note Type</option>
                @foreach ($coverNoteDurations as $coverNoteDuration)
                    <option value="{{ $coverNoteDuration->id }}"
                            data-months="{{ $coverNoteDuration->months }}">
                        {{ $coverNoteDuration->label }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Cover Note Description</label>
            <textarea type="text" class="form-control" name="cover_note_desc"
                      rows="3">PROVIDES PROTECTION AGAINST ANY INSURED PERILS AS SPECIFIED UNDER THE COVER NOTE AGREEMENT.</textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label">Operative Clause</label>
            <textarea class="form-control" name="operative_clause" rows="3">THE INSURANCE COVER SHALL APPLY ONLY TO LOSSES OCCURRING DURING THE PERIOD OF INSURANCE AND ARISING DIRECTLY FROM INSURED RISKS.</textarea>
        </div>

        <div class="col-md-6">
            <label class="form-label">Cover Note Start Date (mm/dd/yy)</label>
            <input class="form-control" type="date" name="cover_note_start_date"
                   id="cover_note_start_date">
        </div>

        <div class="col-md-6">
            <label class="form-label">Cover Note End Date (mm/dd/yy)</label>
            <input class="form-control" type="date" name="cover_note_end_date"
                   id="cover_note_end_date" disabled>
        </div>

        <div class="col-md-6">
            <label class="form-label">Currency Code</label>
            <select class="form-select" name="currency_id" id="currency_id" required>
                <option value="">Select Currency Code</option>
                @foreach ($currencies as $currency)
                    <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Exchange Rate</label>
            <input class="form-control" name="exchange_rate" type="number"
                   step="0.01" value="1">
        </div>

        {{-- ══════════════════════════════════════════════════════════
             Items Covered (non-motor / package policies)
             Only show when insurance type is NOT motor
        ══════════════════════════════════════════════════════════ --}}
        <div class="col-md-12" id="items-covered-section">
            <hr class="my-2">
            <label class="form-label fw-bold fs-6">
                <i class="bi bi-list-check me-1"></i> Items Covered
            </label>
            <p class="text-muted small mb-2">Add each item/risk covered under this policy with its sum insured and premium.</p>

            {{-- Column headers --}}
            <div class="row g-2 mb-1 d-none d-md-flex">
                <div class="col-md-5">
                    <span class="form-label form-label-sm fw-semibold text-secondary">Item Name / Description</span>
                </div>
                <div class="col-md-3">
                    <span class="form-label form-label-sm fw-semibold text-secondary">Sum Insured (TZS)</span>
                </div>
                <div class="col-md-3">
                    <span class="form-label form-label-sm fw-semibold text-secondary">Premium (TZS)</span>
                </div>
            </div>

            {{-- Rows wrapper --}}
            <div id="items-covered-wrapper">
                <div class="item-covered-row row g-2 align-items-center mb-2">
                    <div class="col-md-5">
                        <input type="text"
                               class="form-control form-control-sm"
                               name="items_covered[0][name]"
                               placeholder="e.g. Fire and Allied Perils">
                    </div>
                    <div class="col-md-3">
                        <input type="number"
                               class="form-control form-control-sm items-sum-insured"
                               name="items_covered[0][sum_insured]"
                               placeholder="0.00" step="0.01" min="0">
                    </div>
                    <div class="col-md-3">
                        <input type="number"
                               class="form-control form-control-sm items-premium"
                               name="items_covered[0][premium]"
                               placeholder="0.00" step="0.01" min="0">
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>

            <button type="button"
                    class="btn btn-sm mt-1"
                    style="background-color:#003153;color:#fff;"
                    id="add-item-covered-btn">
                <i class="bi bi-plus-circle me-1"></i> Add Item
            </button>
        </div>

    </div>{{-- /.row --}}

    <div class="d-flex justify-content-between mt-4">
        <button type="button" class="btn text-white" style="background-color: #9aa89b"
                onclick="changeStep(-1)">
            <i class="bi bi-arrow-left me-2 text-white"></i> Back
        </button>
        <button type="button" class="btn text-white" style="background-color: #003153"
                onclick="changeStep(1)">
            Next <i class="bi bi-arrow-right ms-2 text-white"></i>
        </button>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     JS — Dynamic Items Covered rows
══════════════════════════════════════════════════════════ --}}
<script>
(function () {
    let rowIndex = 1;

    const addBtn = document.getElementById('add-item-covered-btn');
    const wrapper = document.getElementById('items-covered-wrapper');

    if (!addBtn || !wrapper) return;

    addBtn.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'item-covered-row row g-2 align-items-center mb-2';
        row.innerHTML = `
            <div class="col-md-5">
                <input type="text"
                       class="form-control form-control-sm"
                       name="items_covered[${rowIndex}][name]"
                       placeholder="e.g. Plate Glass Cover">
            </div>
            <div class="col-md-3">
                <input type="number"
                       class="form-control form-control-sm items-sum-insured"
                       name="items_covered[${rowIndex}][sum_insured]"
                       placeholder="0.00" step="0.01" min="0">
            </div>
            <div class="col-md-3">
                <input type="number"
                       class="form-control form-control-sm items-premium"
                       name="items_covered[${rowIndex}][premium]"
                       placeholder="0.00" step="0.01" min="0">
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button"
                        class="btn btn-sm btn-danger remove-item-btn"
                        title="Remove">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;

        wrapper.appendChild(row);
        rowIndex++;

        row.querySelector('.remove-item-btn').addEventListener('click', function () {
            row.remove();
        });
    });
})();
</script>
