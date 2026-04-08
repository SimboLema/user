

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
                @foreach ($data as $index => $paymentStatus)
                    <tr class="row-clickable" data-id="{{ \Crypt::encrypt($paymentStatus->id) }}">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <span title="{{ $paymentStatus->name }}" style="background-color: {{ $paymentStatus->color ?? '#fff' }}; padding: 5px; color: white; border-radius: 50%; display: inline-block;"></span>
                            {{ $paymentStatus->name ?? '' }}
                        </td>
                        <td class="Action">
                            <span>
                                @if(Gate::check('edit payment status') || Gate::check('delete payment status'))
                                    @if(Gate::check('edit payment status') && $paymentStatus->created_by != 1)
                                        <button type="button" onclick="editPaymentStatus('{{ $paymentStatus->id }}')" class="btn btn-success btn-sm">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    @endif
                                    @if(Gate::check('delete payment status') && $paymentStatus->created_by != 1)
                                        <button type="button" onclick="deletePaymentStatus('{{ $paymentStatus->id }}')" class="btn btn-danger btn-sm">
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
