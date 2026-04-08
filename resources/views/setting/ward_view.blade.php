

<div class="card">
    <div class="card-body table-responsive">
        <table class=" dataTable">
            <thead>
                <tr>
                    <th scope="col">{{ __('S/N') }}</th>
                    <th scope="col">{{ __('Country') }}</th>
                    <th scope="col">{{ __('Region') }}</th>
                    <th scope="col">{{ __('District') }}</th>
                    <th scope="col">{{ __('Name') }}</th>
                    <th scope="col">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach ($data as $index => $row)
                    <tr class="row-clickable" data-id="{{ \Crypt::encrypt($row->id) }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $row->country->name ?? '' }}
                        </td>

                        <td>
                            {{ $row->region->name ?? '' }}
                        </td>

                        <td>
                            {{ $row->district->name ?? '' }}
                        </td>
                         <td>
                            {{ $row->name ?? '' }}
                        </td>
                        <td class="Action">
                            <span>
                                @if(Gate::check('edit ward') || Gate::check('delete ward'))
                                    @if(Gate::check('edit ward') )
                                        <button type="button" onclick="editWard('{{ $row->id }}')" class="btn btn-success btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endif
                                    @if(Gate::check('delete ward') )
                                        <button type="button" onclick="deleteWard('{{ $row->id }}')" class="btn btn-danger btn-sm">
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
