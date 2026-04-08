@php
    use App\Models\WasteSource;
@endphp

<div class="card">
    <div class="card-body table-responsive">
        <table class=" dataTable">
            <thead>
                <tr>
                    <th scope="col">{{ __('S/N') }}</th>
                    <th scope="col">{{ __('Category') }}</th>
                    <th scope="col">{{ __('Name') }}</th>
                    <th scope="col">{{ __('Sources') }}</th>
                    <th scope="col">{{ __('Description') }}</th>
                    <th scope="col">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach ($data as $index => $row)
                @php
                    $waste_sources = is_array($row->waste_sources)
                        ? $row->waste_sources
                        : json_decode($row->waste_sources ?? '', true);
                @endphp
                    <tr class="row-clickable" data-id="{{ \Crypt::encrypt($row->id) }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            {{ $row->parent->name ?? '' }}
                        </td>
                        <td>
                            {{ $row->name ?? '' }}
                        </td>
                        <td>
                            @foreach ($waste_sources as $source_id)
                                @php
                                    $wasteSource = WasteSource::find($source_id);
                                @endphp
                                @if($wasteSource)
                                    <span class="badge badge-info">{{ $wasteSource->name }}</span>
                                @endif
                            @endforeach


                        </td>
                        <td>
                            {{ $row->description ?? '' }}
                        </td>
                        <td class="Action">
                            <span>
                                @if(Gate::check('edit waste type') || Gate::check('delete waste type'))
                                    @if(Gate::check('edit waste type') )
                                        <button type="button" onclick="editWasteType('{{ $row->id }}')" class="btn btn-success btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endif
                                    @if(Gate::check('delete waste type') )
                                        <button type="button" onclick="deleteWasteType('{{ $row->id }}')" class="btn btn-danger btn-sm">
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
