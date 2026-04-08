@extends('layout.index')
@section('page-title')
Admin Dashboard
@endsection
@section('content')
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Total Waste Collected</h6>
                <h3>{{ number_format($totalWasteCollected) }} <span class="text-muted small">kg</span></h3>
                <small class="{{ $wasteChange >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $wasteChange >= 0 ? '+' : '' }}{{ number_format($wasteChange, 1) }}%
                </small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>CO<sub>2</sub>e Reduced</h6>
                <h3>-- <span class="text-muted small">kg</span></h3>
                <small class="{{ $co2Change >= 0 ? 'text-success' : 'text-danger' }}">
                   -- %
                </small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h6>Active Users</h6>
                <h3>{{ number_format($activeUsers) }}</h3>
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
</div>


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

<div class="row mt-4">
    <!-- Recent User Registrations -->
    <div class="col-md-6">
        <div class="card p-3 shadow-sm rounded-4">
            <h5 class="mb-1 fw-semibold">Recent User Registrations</h5>
            <small class="text-muted mb-3">New users in the past week</small>

            @foreach($recentUsers as $user)
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-light text-center me-3" style="width: 40px; height: 40px; line-height: 40px; font-weight: 600;">
                            {{ strtoupper(substr($user->roleRow->name ?? "", 0, 2)) }}
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $user->name }}</div>
                            <small class="text-muted">{{ $user->roleRow->name }}</small>
                        </div>
                    </div>
                    <span class="btn btn-outline-primary btn-sm">{{date('Y F d',strtotime($user->created_at))}}</span>
                </div>
            @endforeach

            <div class="text-center mt-2">
                <a href="/user" class="text-decoration-none text-primary">View All Users</a>
            </div>
        </div>
    </div>

    <!-- Smart Bin Status -->
    <div class="col-md-6">
        <div class="card p-3 shadow-sm rounded-4">
            <h5 class="mb-1 fw-semibold">Smart Bin Status</h5>
            <small class="text-muted mb-3">Live smart bin fill levels and alerts</small>

            @foreach($smartBins as $bin)
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle text-white text-center me-3" style="width: 40px; height: 40px; line-height: 40px; background-color: {{ $bin->color }};">
                            {{ $bin->fill_level }}%
                        </div>
                        <div>
                            <div class="fw-semibold">Bin ID#{{ $bin->id }}</div>
                            <small class="text-muted">{{ $bin->location }}</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <small class="fw-semibold" style="color: {{ $bin->status_color }}">{{ $bin->status_text }}</small>
                    </div>
                </div>
            @endforeach

            <div class="text-center mt-2">
                <a href="/bin" class="text-decoration-none text-primary">View All Bins</a>
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
