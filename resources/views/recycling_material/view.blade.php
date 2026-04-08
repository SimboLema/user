<div class="card">
    <div class="card-body table-border-style table-responsive">
        <table class="table dataTable">
            <thead>
                <tr>
                    <th>{{ __('S/N') }}</th>
                    <th>{{ __('Process') }}</th>
                    <th>{{ __('Facility') }}</th>
                    <th>{{ __('Waste Type') }}</th>
                    <th>{{ __('Input Quantity') }}</th>
                    <th>{{ __('Output Product') }}</th>
                    <th>{{ __('Output Quantity') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach ($data as $index => $material)
                    <tr class="row-clickable" data-id="{{ \Crypt::encrypt($material->id) }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ ucfirst($material->process) }}</td>
                        <td>{{ $material->facility->name ?? '' }}</td>
                        <td>{{ $material->wasteType->name ?? '' }}</td>
                        <td>{{ $material->input_quantity . ' ' . ($material->unit->name ?? '') }}</td>
                        <td>{{ $material->outputProduct->name ?? '' }}</td>
                        <td>{{ $material->output_product_quantity }}</td>
                        <td class="Action">
                            @can('show recycling material')
                            <a href="/recycling_material/show/{{Crypt::encrypt($material->id)}}" class="btn btn-success btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        @endcan
                            @can('edit recycling material')
                                <button type="button" onclick="editRecyclingMaterial('{{ $material->id }}')" class="btn btn-success btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            @endcan
                            @can('delete recycling material')
                                <button type="button" onclick="deleteRecyclingMaterial('{{ $material->id }}')" class="btn btn-danger btn-sm">
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
