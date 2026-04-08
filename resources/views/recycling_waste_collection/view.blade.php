<div class="card">
    <div class="card-body table-border-style table-responsive">
        <table class="table dataTable">
            <thead>
                <tr>
                    <th>{{ __('S/N') }}</th>
                    <th>{{ __('Facility') }}</th>
                    <th>{{ __('Collect From') }}</th>
                    <th>{{ __('Total Qty') }}</th>
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
                        <td>{{ ($item->facility) ? $item->facility->name : "" }}</td>
                        <td>{{ $item->collectionCenter->name ?? $item->producer->name ?? "" }}</td>
                        <td>{{ number_format($item->totalQty()) }}</td>
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
                            @can('edit recycling waste collection')
                                <a href="/recycling_waste_collection/show/{{Crypt::encrypt($item->id)}}" class="btn btn-success btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                            @endcan

                            @can('edit recycling waste collection')
                                <button type="button" onclick="editRecyclingWasteCollection('{{ $item->id }}')" class="btn btn-success btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            @endcan
                            @can('delete recycling waste collection')
                                <button type="button" onclick="deleteRecyclingWasteCollection('{{ $item->id }}')" class="btn btn-danger btn-sm">
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
