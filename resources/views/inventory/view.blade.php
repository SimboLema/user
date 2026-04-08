

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
                            {{ $row->quantity ?? '' }} {{ $row->unit->name ?? '' }}
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
