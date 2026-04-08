@extends('layout.index')

@section('page-title')
Inventory Balance
@endsection

@section('content')
<div class="d-flex justify-content-sm-between align-items-sm-center pb-2">
    <h5 class="card-title mb-sm-0 "></h5>
    <div class="action-btns">

    </div>
</div>

<div class="row">
    <div class="col-md-12" id="getView"></div>
</div>


<script>
    $(document).ready(function () {
        getView();
    });



    function getView() {
        showLoader('getView', true);
        $.get("/inventory_balance/view", function (data) {
            showLoader('getView', false);
            $("#getView").html(data);
        });
    }

</script>
@endsection
