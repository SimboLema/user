@extends('layout.index')

@section('page-title')
Recycler Dashboard
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Total Waste Collected</h6>
                <h3>{{ number_format($wasteCollections->sum('quantity')) }} <span class="text-muted small">kg</span></h3>
                <small class="{{ $wasteChange >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $wasteChange >= 0 ? '+' : '' }}{{ number_format($wasteChange, 1) }}%
                </small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Active Pickers</h6>
                <h3>{{ number_format($activeWastePickers->count()) }}</h3>
                <small class="{{ $activeChange >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $activeChange >= 0 ? '+' : '' }}{{ number_format($activeChange, 1) }}%
                </small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Carbon Credits</h6>
                <h3>{{ number_format($carbonCredits) }}</h3>
                <small class="{{ $carbonChange >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $carbonChange >= 0 ? '+' : '' }}{{ number_format($carbonChange, 1) }}%
                </small>
            </div>
        </div>
    </div>
     <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Monthly Earning</h6>
                <h3>--</h3>
                <small class="{{ $carbonChange >= 0 ? 'text-success' : 'text-danger' }}">
                    --%
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-3" id="dashboardTabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="overview-tab" data-bs-toggle="tab" href="#overview" role="tab">Overview</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="picker-tab" data-bs-toggle="tab" href="#picker" role="tab">Picker Management</a>
    </li>
</ul>

<div class="tab-content" id="dashboardTabsContent">
    <!-- Overview Tab -->
    <div class="tab-pane fade show active" id="overview" role="tabpanel">
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Waste Collection Trends</h5>
                        <div id="wasteCollectionChart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Plastic Types</h5>
                        <p class="text-muted">Distribution of plastic types collected</p>
                        <div id="plasticTypesChart" style="height: 260px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Collections Table -->
        <div class="card shadow mt-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Recent Collections</h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light fw-bold">
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
                                @foreach ($wasteCollections->latest()->take(5)->get() as $index => $item)
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

                                            <span title="Status" style="background-color: {{$item->paymentStatus->color ?? 'white'}}; padding: 5px; color: white; border-radius: 50%; display: inline-block;"></span> {{$item->paymentStatus->name ?? ''}}


                                        </td>
                                        <td class="Action">
                                            @can('show waste collection')
                                                <a href="/waste_collection/show/{{Crypt::encrypt($item->id)}}" class="btn btn-success btn-sm">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Picker Management Tab -->
    <div class="tab-pane fade" id="picker" role="tabpanel">
        <div class="card shadow">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Picker Management</h6>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light fw-bold">
                            <tr>
                                <th>{{ __('S/N') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Gender') }}</th>

                                <th>{{ __('GPS') }}</th>
                                <th>{{ __('ID Type') }}</th>
                                <th>{{ __('ID Number') }}</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            @foreach ($activeWastePickers->latest()->take(5)->get() as $index => $user)
                                <tr class="row-clickable" data-id="{{ \Crypt::encrypt($user->id) }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->user->name ?? '' }}</td>
                                    <td>{{ $user->user->email ?? '' }}</td>
                                    <td>{{ $user->user->phone ?? '' }}</td>
                                    <td>{{ ucfirst($user->user->gender ?? "") ?? '' }}</td>

                                    <td>{{ "--"}}</td>
                                    <td>{{ $user->id_type ?? '' }}</td>
                                    <td>{{ $user->id_number ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-end">
                        <a href="/waste_picker" class="btn btn-link">View All Pickers</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Waste Collection Trends
        Highcharts.chart('wasteCollectionChart', {
            chart: {
                type: 'column'
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: @json($months),
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Kilograms'
                }
            },
            tooltip: {
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: @json($parentWasteTypes)
        });

        // Plastic Types Pie Chart
        Highcharts.chart('plasticTypesChart', {
            chart: {
                type: 'pie'
            },
            title: {
                text: ''
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                name: 'Waste Types',
                colorByPoint: true,
                data: @json($childWasteTypes)
            }]
        });
    });
</script>


@endsection
