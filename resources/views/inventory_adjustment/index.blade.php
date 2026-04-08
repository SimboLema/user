@extends('layout.index')

@section('page-title')
Inventory Adjustment
@endsection

@section('content')
<div class="d-flex justify-content-sm-between align-items-sm-center pb-2">
    <h5 class="card-title mb-sm-0 "></h5>
    <div class="action-btns">
        <button id="editModeBtn" class="btn btn-sm btn-primary btn-sm" >Edit Quantity</button>
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
        $.get("/inventory_adjustment/view", function (data) {
            showLoader('getView', false);
            $("#getView").html(data);
        });
    }

    $(document).ready(function () {
        $('#editModeBtn').click(function () {
            $('.qty-display').addClass('d-none');
            $('.qty-edit').removeClass('d-none');
            $('#editActions').removeClass('d-none');
            $(this).hide();
        });

        $(document).on('click', '#cancelEdit', function () {
            $('.qty-display').removeClass('d-none');
            $('.qty-edit').addClass('d-none');
            $('#editActions').addClass('d-none');
            $('#editModeBtn').show();
        });

        $(document).on('click', '.add-qty', function () {
            const input = $(this).siblings('.qty-input');
            input.val(parseFloat(input.val()) + 1);
        });

        $(document).on('click', '.reduce-qty', function () {
            const input = $(this).siblings('.qty-input');
            const newVal = Math.max(0, parseFloat(input.val()) - 1);
            input.val(newVal);
        });

        $(document).on('click', '#saveEdit', function () {
            const data = [];
            $('.qty-edit').each(function () {
                data.push({
                    id: $(this).data('id'),
                    quantity: $(this).find('.qty-input').val()
                });
            });

            $.ajax({
                url: '/inventory_adjustment/save',
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    items: data
                },
                success: function (res) {
                    if (res.status === 200) {
                        showFlashMessage("success", res.message);
                        getView();
                        $('.qty-display').removeClass('d-none');
                        $('.qty-edit').addClass('d-none');
                        $('#editActions').addClass('d-none');
                        $('#editModeBtn').show();
                    } else {
                        showFlashMessage("warning", res.message);
                    }
                }
            });
        });
    });



</script>

@endsection
