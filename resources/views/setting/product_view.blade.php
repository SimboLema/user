

<div class="card">
    <div class="card-body table-responsive">
        <table class=" dataTable">
            <thead>
                <tr>
                    <th scope="col">{{ __('S/N') }}</th>
                    <th scope="col">{{ __('Name') }}</th>
                    <th scope="col">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach ($data as $index => $row)
                    <tr class="row-clickable" data-id="{{ \Crypt::encrypt($row->id) }}">
                        <td>{{ $index + 1 }}</td>

                        <td>
                            {{ $row->name ?? '' }}
                        </td>

                        </td>
                        <td class="Action">
                            <span>
                                @if(Gate::check('edit product') || Gate::check('delete product'))
                                    @if(Gate::check('edit product'))
                                        <button type="button" onclick="editProduct('{{ $row->id }}')" class="btn btn-success btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endif
                                    @if(Gate::check('delete product') )
                                        <button type="button" onclick="deleteProduct('{{ $row->id }}')" class="btn btn-danger btn-sm">
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
