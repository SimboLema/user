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
                    <option
                        value="{{ $currency->id }}">{{ $currency->name }}</option>
                        <option>United States Dollar</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Exchange Rate</label>
            <input class="form-control" name="exchange_rate" type="number"
                   step="0.01" value="1">
        </div>
    </div>

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
