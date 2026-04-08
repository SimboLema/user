

<div class="card">
    <div class="card-body table-responsive">
        <table class=" dataTable">
            <thead>
                <tr>
                    <th scope="col">{{ __('S/N') }}</th>
                    <th scope="col">{{ __('Company') }}</th>
                    <th scope="col">{{ __('Waste type') }}</th>
                    <th scope="col">{{ __('Quantity') }}</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach ($balances as $index => $row)
                    <tr class="row-clickable" >
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if (auth()->user()->role == 3)
                               {{$row->collectionCenter->name ?? ""}}
                            @elseif (auth()->user()->role == 4)
                                {{$row->facility->name ?? ""}}
                            @elseif (auth()->user()->role == 5)
                                {{$row->producer->name ?? ""}}
                            @else
                                --
                            @endif
                        </td>

                        <td>
                            {{ $row->wasteType->name ?? '' }}
                        </td>

                        <td>
                            <span class="qty-display">{{ $row->quantity ?? '' }} {{ $row->unit->name ?? '' }}</span>

                            <div class="qty-edit d-none" data-id="{{ $row->id }}">
                                <button class="btn btn-sm btn-danger reduce-qty">-</button>
                                <input type="number" class="form-control d-inline-block qty-input" value="{{ $row->quantity ?? 0 }}" style="width:80px;" />
                                <button class="btn btn-sm btn-success add-qty">+</button>
                            </div>
                        </td>


                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <div class="mt-3 d-none text-end" id="editActions">
            <button id="cancelEdit" class="btn btn-secondary">Cancel</button>
            <button id="saveEdit" class="btn btn-primary">Save</button>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        initializeDataTable();
    });
</script>
