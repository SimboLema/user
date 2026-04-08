<div class="step-content d-none">
    <h5 class="step-title"><i class="bi bi-calculator me-2"></i>Premium
        Calculation</h5>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Sum Insured</label>
            <input class="form-control" name="sum_insured" type="number"
                   step="0.01">
            {{-- <input class="form-control fw-bold" type="text" name="sum_insured" placeholder="0.00"
                inputmode="numeric"
                oninput="let v=this.value.replace(/[^0-9]/g,'');
                        this.value=v.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        this.setAttribute('value',v)"
                onchange="this.setAttribute('value', this.value.replace(/,/g,''))"> --}}
        </div>

        <div class="col-md-6">
            <label class="form-label">Premium</label>
            <input class="form-control" name="premium" type="number" step="0.01" value="0" disabled>
        </div>
        <div class="col-md-6">
            <label class="form-label">Premium Rate</label>
            <input class="form-control" name="premium_rate" type="number" step="0.01" disabled>
        </div>
        <div class="col-md-6">
            <label class="form-label">Tax Rate </label>
            <input class="form-control" name="tax_rate" type="number" step="0.01" value="0.18" disabled>
        </div>
        <div class="col-md-6">
            <label class="form-label">Tax Amount</label>
            <input class="form-control" name="tax_amount" type="number" step="0.01" disabled>
        </div>
        <div class="col-md-6">
            <label class="form-label">Total Premium Including Tax</label>
            <input class="form-control" name="total_premium_including_tax" type="number" step="0.01" disabled>
        </div>
        <div class="col-md-6">
            <label class="form-label">Total Premium Excluding Tax</label>
            <input class="form-control" name="total_premium_excluding_tax" type="number" step="0.01" disabled>
        </div>
        <div class="col-md-6">
            <label class="form-label">Commission Rate (%)</label>
            <input class="form-control" name="commission_rate" type="number" step="0.01" value="0">
        </div>
        <div class="col-md-6">
            <label class="form-label">Commission Paid</label>
            <input class="form-control" name="commission_paid" type="number" value="0" step="0.01">
        </div>

        <div class="col-md-6">
            <label class="form-label">Is Tax Exempted</label>
            <select class="form-select" name="is_tax_exempted" id="is_tax_exempted" required>
                <option value="N" selected>No</option>
                <option value="Y">Yes</option>

            </select>
        </div>
    </div>

    <div class="d-flex justify-content-between mt-4">
        <button type="button" class="btn text-white" style="background-color: #9aa89b" onclick="changeStep(-1)">
            <i class="bi bi-arrow-left me-2 text-white"></i> Back
        </button>
        <button type="button" class="btn text-white" style="background-color: #003153" onclick="changeStep(1)">
            Next <i class="bi bi-arrow-right ms-2 text-white"></i>
        </button>
    </div>
</div>
