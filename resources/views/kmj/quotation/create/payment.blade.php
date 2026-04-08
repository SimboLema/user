<div class="step-content d-none">
    <h5 class="step-title"><i class="bi bi-shield-exclamation me-2"></i>Payment
    </h5>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Payment Mode</label>
            <select class="form-select" name="payment_mode_id"
                    id="payment_mode_id">
                <option value="">Select Payment Mode</option>
                @foreach ($paymentModes as $paymentMode)
                    <option
                        value="{{ $paymentMode->id }}">{{ $paymentMode->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="payment-details" class="mt-3">
            {{-- CASH --}}
            <div id="cash-fields" class="payment-type d-none">
                {{--                                        <h6 class="fw-bold text-primary mt-3">Cash Payment Details</h6> --}}
                {{--                                        <div class="row g-3"> --}}
                {{--                                            <div class="col-md-6"> --}}
                {{--                                                <label class="form-label">Amount Received</label> --}}
                {{--                                                <input type="number" class="form-control" name="cash_amount" --}}
                {{--                                                       step="0.01"> --}}
                {{--                                            </div> --}}
                {{--                                            <div class="col-md-6"> --}}
                {{--                                                <label class="form-label">Received By</label> --}}
                {{--                                                <input type="text" class="form-control" name="cash_received_by"> --}}
                {{--                                            </div> --}}
                {{--                                        </div> --}}
            </div>

            {{-- CHEQUE --}}
            <div id="cheque-fields" class="payment-type d-none">
                <h6 class="fw-bold text-primary mt-3">Cheque Payment
                    Details</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Cheque Number</label>
                        <input type="text" class="form-control"
                               name="cheque_number">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bank Name</label>
                        <input type="text" class="form-control"
                               name="cheque_bank_name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Cheque Date</label>
                        <input type="date" class="form-control"
                               name="cheque_date">
                    </div>
                    {{--                                            <div class="col-md-6"> --}}
                    {{--                                                <label class="form-label">Amount</label> --}}
                    {{--                                                <input type="number" class="form-control" name="cheque_amount" --}}
                    {{--                                                       step="0.01"> --}}
                    {{--                                            </div> --}}
                </div>
            </div>

            {{-- EFT / MOBILE --}}
            <div id="eft-fields" class="payment-type d-none">
                <h6 class="fw-bold text-primary mt-3">EFT / Mobile Payment
                    Details</h6>
                <div class="row g-3">
                    {{--                                            <div class="col-md-6"> --}}
                    {{--                                                <label class="form-label">Transaction Reference</label> --}}
                    {{--                                                <input type="text" class="form-control" name="eft_reference"> --}}
                    {{--                                            </div> --}}
                    <div class="col-md-6">
                        <label class="form-label">Mobile Number /
                            Account</label>
                        <input type="text" class="form-control"
                               name="eft_payment_phone_no">
                    </div>
                    {{--                                            <div class="col-md-6"> --}}
                    {{--                                                <label class="form-label">Payment Date</label> --}}
                    {{--                                                <input type="date" class="form-control" name="eft_date"> --}}
                    {{--                                            </div> --}}
                    {{--                                            <div class="col-md-6"> --}}
                    {{--                                                <label class="form-label">Amount</label> --}}
                    {{--                                                <input type="number" class="form-control" name="eft_amount" step="0.01"> --}}
                    {{--                                            </div> --}}
                </div>
            </div>
        </div>

    </div>

    <div class="d-flex justify-content-between mt-4">
        <button type="button" class="btn text-white" style="background-color: #9aa89b"
                onclick="changeStep(-1)">
            <i class="bi bi-arrow-left me-2"></i> Back
        </button>
        <button type="button" class="btn text-white" style="background-color: #003153"
                onclick="changeStep(1)">
            Next <i class="bi bi-arrow-right ms-2"></i>
        </button>
    </div>
</div>
