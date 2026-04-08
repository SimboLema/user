

  <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCenterTitle">Make Payment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form class="row g-3 needs-validation" novalidate id="form2" onsubmit=save2(event) enctype="multipart/form-data">
                @csrf
            <input type="hidden" name="collection_id" id="collection_id">
            <div class="row g-6">


                <div class="form-group col-md-6">
                    <label>Payment Method<span style="color:red">*</span></label>
                    <select class="form-control" name="payment_method" required>
                        <option value="Cash">Cash</option>
                        <option value="Online Payment">Online Payment</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Amount<span style="color:red">*</span></label>
                    <input type="number" class="form-control" name="amount" id="payment_amount" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Date<span style="color:red">*</span></label>
                    <input type="date" class="form-control" value="{{date('Y-m-d')}}" name="date" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Attachment</label>
                    <input type="file" class="form-control" name="attachment">
                </div>
                <div class="form-group col-md-6">
                    <label>Reference Number</label>
                    <input type="text" class="form-control" name="reference_number" >
                </div>

            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="submitBtn2">Save Payment</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
<script>


    function save2(e) {
        e.preventDefault();
        disableBtn("submitBtn2", true);
        var form = document.getElementById('form2');
        var formData = new FormData(form);

        jQuery.ajax({
            type: "POST",
            url: "/recycling_waste_collection/payment",
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                if (data.status == 200) {
                    showFlashMessage("success", data.message);
                    $("#modalCenter").modal('hide')
                    getView()
                } else {
                    showFlashMessage("warning", data.message);
                }
                disableBtn("submitBtn2", false);
                $("#submitBtn2").html("Save")
            }
        });
    }

    function getPaymentDetails(id,amount){
        $('#collection_id').val(id);
        $('#payment_amount').val(amount);
    }

</script>
