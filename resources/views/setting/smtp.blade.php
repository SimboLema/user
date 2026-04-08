@extends('layout.index')
@section('page-title')
Email Settings
@endsection
@section('content')

<div class="row">

    <div class="col-md-12" id="getView"></div>
</div>

<script>
    $(document).ready(function () {
        getView()
    });

    function getView() {
        showLoader('getView',true)
        jQuery.ajax({
            type: "GET",
            url: "/smtp/smtp_view",
            dataType: 'html',
            cache: false,
            success: function (data) {
                showLoader('getView',false)
                $("#getView").html(data)
            }
        });
    }

    function save(e) {
        e.preventDefault();

        disableBtn("submitBtn", true);
        var form = document.getElementById('form');
        var formData = new FormData(form);

        jQuery.ajax({
            type: "POST",
            url: "/smtp/saveSmtpSetting",
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                if (data.status == 200) {
                        showFlashMessage("success", data.message);
                        getView()
                } else {
                        showFlashMessage("warning", data.message);
                }

                disableBtn("submitBtn", false);
                $("#submitBtn").html("Save ")
            }
        });
    }


</script>

@endsection
