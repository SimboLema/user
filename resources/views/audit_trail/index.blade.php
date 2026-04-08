@extends('layout.index')

@section('page-title')
Audit Trail
@endsection

@section('breadcrumb')
<div class="d-flex justify-content-between align-items-center mx-4 ">
    <!-- Breadcrumb -->
    <div class="pagetitle">
        <h3>{{ __('Audit Trail') }}</h1>
        <nav>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Audit Trail') }}</li>
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
        getView();
    });


    function getView() {
        showLoader('getView', true);
        jQuery.ajax({
            type: "GET",
            url: "/audit_trail/audit_trail_view",
            dataType: 'html',
            cache: false,
            success: function (data) {
                showLoader('getView', false);
                $("#getView").html(data);
            }
        });
    }

</script>

@endsection
