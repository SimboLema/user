<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-hover table-sm dataTable">
            <thead>
                <tr>
                    <th scope="col">{{ __('S/N') }}</th>
                    <th scope="col">{{ __('Name') }}</th>
                    <th scope="col">{{ __('Description') }}</th>
                    <th scope="col">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach ($data as $index => $fineType)
                    <tr class="row-clickable" >
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $fineType->name ?? '' }}</td>
                        <td>{{ $fineType->description ?? '' }}</td>
                        <td class="Action">
                            <span>
                                @if(Gate::check('edit fine type') || Gate::check('delete fine type'))
                                    @if(Gate::check('edit fine type'))
                                        <button type="button" onclick="editFineType('{{ $fineType->id }}')" class="btn btn-success btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endif
                                    @if(Gate::check('delete fine type'))
                                        <button type="button" onclick="deleteFineType('{{ $fineType->id }}')" class="btn btn-danger btn-sm">
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