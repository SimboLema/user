@extends('layout.index')
@section('page-title')
Notifications
@endsection
@section('breadcrumb')
<div class="d-flex justify-content-between align-items-center mx-4 ">
    <!-- Breadcrumb -->
    <div class="pagetitle">
        <h3>{{ __('Notifications') }}</h3>
        <nav>
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="/">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Notification') }}</li>
            </ol>
        </nav>
    </div>
    
</div>

@endsection

@section('content')
<!-- Modal Dialog Scrollable -->
<div class="row">
    <div class="col-md-12" id="getView">
    <div class="card">
    <div class="card-body table-responsive">
    <table class="table dataTable " >
        <thead>
            <tr>
                <th scope="col">{{ __('S/N') }}</th>
                <th scope="col">{{ __('Title') }}</th>
                <th scope="col">{{ __('Message') }}</th>
                <th scope="col">{{ __('Date') }}</th>
              
            </tr>
        </thead>
        <tbody class="list">
        @foreach($notifications as $index=> $notification)

            <tr class="row-clickable" data-href="javascript:void(0)">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <a href="<?=$notification->redirect_link ?? "javascript:void(0)"?>">
                            {{ $notification->title ?? "" }} </a>
                        </td>
                        <td>{{ $notification->message ?? "" }}</td>
                        <td>{{ $notification->date ?? "". $notification->time ?? ""}}</td>
                      
                  </tr>
                  @endforeach
            </tbody>
      </table>
    </div>
</div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Initialize DataTable
        initializeDataTable();
    });
</script>

@endsection
