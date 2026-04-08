

<div class="card">
    <div class="card-body table-responsive">
        <table class=" dataTable">
            <thead>
                <tr>
                    <th scope="col">{{ __('S/N') }}</th>
                    <th scope="col">{{ __('Reported User') }}</th>
                    <th scope="col">{{ __('Name') }}</th>
                    <th scope="col">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach ($data as $index => $row)
                    <tr class="row-clickable" data-id="{{ \Crypt::encrypt($row->id) }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $row->user->name ?? '' }}
                        </td>

                        <td>
                            {{ $row->location_name ?? '' }}
                        </td>
                        <td class="Action">
                            <span>
                                @if(Gate::check('edit illegal dumping') || Gate::check('delete illegal dumping'))
                                    @if(Gate::check('edit illegal dumping') )
                                        <button type="button" onclick="editIllegalDumping('{{ $row->id }}')" class="btn btn-success btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endif
                                    @if(Gate::check('delete illegal dumping') )
                                        <button type="button" onclick="deleteIllegalDumping('{{ $row->id }}')" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                @endif
                            </span>
                        </td>
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
