<div class="card">
    <div class="card-body table-border-style table-responsive">
        <table class="table dataTable">
            <thead>
                <tr>
                    <th>{{ __('S/N') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('District') }}</th>
                    <th>{{ __('Ward') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach ($data as $index => $center)
                    <tr class="row-clickable" data-id="{{ \Crypt::encrypt($center->id) }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $center->name ?? '' }}</td>
                        <td>{{ $center->district->name ?? '' }}</td>
                        <td>{{ $center->ward->name ?? '' }}</td>
                        <td class="Action">
                            @can('edit production company')
                                <button type="button" onclick="editProducer('{{ $center->id }}')" class="btn btn-success btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            @endcan
                            @can('delete production company')
                                <button type="button" onclick="deleteProducer('{{ $center->id }}')" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        initializeDataTable();
        $('.dataTable tbody').on('click', 'tr.row-clickable td:not(:last-child)', function() {
            // Optional row click action
        });
    });
</script>
