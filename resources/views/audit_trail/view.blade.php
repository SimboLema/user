<?php
  use App\Models\User;
?>
<div class="card">
    <div class="card-body table-responsive">
    <table class="table dataTable " >
        <thead>
            <tr>
                <th scope="col">{{ __('S/N') }}</th>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Description') }}</th>
                <th scope="col">{{ __('Date') }}</th>
                <th scope="col">{{ __('User') }}</th>
                <th scope="col">{{ __('Location') }}</th>
                <th scope="col">{{ __('Device') }}</th>
                <th scope="col">{{ __('Ip-Address') }}</th>
                <th scope="col">{{ __('Browser') }}</th>
                <th scope="col">{{ __('Os') }}</th>
            </tr>
        </thead>
        <tbody class="list">
            @foreach ($data as $index => $docu)

                @php 
                $properties=json_decode($docu->properties,TRUE);
                    $userRow=User::find($properties['created_by'] ?? "");
                @endphp
                <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $docu->log_name ?? "" }}</td>
                        <td>{{ $docu->description  ?? ""}}</td>
                        <td>
                              {{ auth()->user()->companySetting()->formatDate($docu->created_at)  }}
                              {{ auth()->user()->companySetting()->formatTime($docu->created_at)  }}
                        </td>
                        <td>{{ $userRow->name ?? ""}}</td>
                        <td>{{ $properties['location'] ?? "" }}</td>
                        <td>{{ $properties['device'] ?? ""}}</td>
                        <td>{{ $properties['ip_address'] ?? "" }}</td>
                        <td>{{ $properties['browser'] ?? "" }}</td>
                        <td>{{ $properties['os'] ?? "" }}</td>
                    
                  </tr>
                  @endforeach
            </tbody>
      </table>


    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        initializeDataTable();
    });
</script>
