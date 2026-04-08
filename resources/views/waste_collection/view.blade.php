<div class="card">
    <div class="card-body table-border-style table-responsive">
        <table class="table dataTable">
            <thead>
                <tr>
                    <th>{{ __('S/N') }}</th>
                    <th>{{ __('Waste Picker') }}</th>
                    <th>{{ __('Collection Center') }}</th>
                    <th>{{ __('Waste Type') }}</th>
                    <th>{{ __('Color') }}</th>
                    <th>{{ __('Quantity') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Paid') }}</th>
                    <th>{{ __('Payments') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody class="list">
                @foreach ($data as $index => $item)
                    <tr class="row-clickable" data-id="{{ \Crypt::encrypt($item->id) }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ ($item->wastePicker && $item->wastePicker->user) ? $item->wastePicker->user->name : "" }}</td>
                        <td>{{ $item->collectionCenter->name ?? '' }}</td>
                        <td>{{ $item->wasteType->name ?? '' }}</td>
                        <td>{{ $item->color->name ?? '' }}</td>
                        <td>{{ $item->quantity }} {{ $item->unit->name ?? '' }}</td>
                        <td>{{ number_format($item->total_amount) }}</td>
                        <td>{{ number_format($item->totalPayment()) }}</td>
                        <td>

                            @if ($item->payment_status == 3)

                               <span title="Status" style="background-color: {{$item->paymentStatus->color ?? 'white'}}; padding: 5px; color: white; border-radius: 50%; display: inline-block;"></span> {{$item->paymentStatus->name ?? ''}}

                            @else
                                <button class="btn btn-primary btn-sm make-payment-btn"
                                data-bs-toggle="modal" data-bs-target="#modalCenter" onclick="getPaymentDetails('{{ $item->id }}','{{ $item->total_amount - $item->totalPayment() }}')">
                                    Make Payment
                                </button>
                            @endif


                        </td>
                        <td class="Action">
                            @can('show waste collection')
                                <a href="/waste_collection/show/{{Crypt::encrypt($item->id)}}" class="btn btn-success btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                            @endcan
                            @can('edit waste collection')
                                <button type="button" onclick="editWasteCollection('{{ $item->id }}')" class="btn btn-success btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            @endcan
                            @can('delete waste collection')
                                <button type="button" onclick="deleteWasteCollection('{{ $item->id }}')" class="btn btn-danger btn-sm">
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
    });
</script>
