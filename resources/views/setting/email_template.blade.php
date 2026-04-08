@extends('layout.index')
@section('page-title')
Email Template
@endsection
@section('breadcrumb')
<div class="d-flex justify-content-between align-items-center mx-4">
    <!-- Breadcrumb -->
    <div class="pagetitle">
        <h3>{{ __('Email Template') }}</h1>
        <nav>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item active">Email Template </li>
            </ol>
        </nav>
    </div>
    
</div>

@endsection

@section('content')
<!-- Modal Dialog Scrollable -->
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
            url: "/email_template/email_template_view",
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
            url: "/email_template/saveEmailTemplate",
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
