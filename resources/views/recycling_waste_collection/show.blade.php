@extends('layout.index')

@section('page-title')
Waste Collection
@endsection

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <div class="card shadow-sm">
        <div class="card-body">
            <!-- TABS -->
            <ul class="nav nav-tabs d-flex flex-nowrap overflow-auto border-0 mb-3" id="wasteTabs" role="tablist" style="gap: 0.5rem;">
                <li class="nav-item flex-shrink-0" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview"
                        type="button" role="tab">Overview</button>
                </li>
                <li class="nav-item flex-shrink-0" role="presentation">
                    <button class="nav-link" id="items-tab" data-bs-toggle="tab" data-bs-target="#items"
                        type="button" role="tab">Items List</button>
                </li>
                <li class="nav-item flex-shrink-0" role="presentation">
                    <button class="nav-link" id="payments-tab" data-bs-toggle="tab" data-bs-target="#payments"
                        type="button" role="tab">Payments</button>
                </li>
                <li class="nav-item flex-shrink-0" role="presentation">
                    <button class="nav-link" id="map-tab" data-bs-toggle="tab" data-bs-target="#map"
                        type="button" role="tab">Map</button>
                </li>

            </ul>


            <!-- TAB CONTENT -->
            <div class="tab-content pt-4" id="wasteTabsContent">

                <!-- OVERVIEW -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="row g-4">
                        @php
                            $details = [
                                ['label' => 'Facility', 'value' => $waste->facility->name ?? '-', 'icon' => 'bi-building'],
                            ];

                            if ($waste->collectionCenter) {
                                $details[] = ['label' => 'Collection Center', 'value' => $waste->collectionCenter->name ?? '-', 'icon' => 'bi-pin-map'];
                                $details[] = ['label' => 'Collected By', 'value' => $waste->collectionUser->name ?? '-', 'icon' => 'bi-person'];
                            }

                            if ($waste->producer) {
                                $details[] = ['label' => 'Producer', 'value' => $waste->producer->name ?? '-', 'icon' => 'bi-person-badge'];
                            }

                            $details[] = ['label' => 'Total Quantity', 'value' => $waste->totalQty() . ' units', 'icon' => 'bi-box-seam'];
                            $details[] = ['label' => 'Total Payment', 'value' => number_format($waste->totalPayment(), 2), 'icon' => 'bi-cash'];
                            $details[] = ['label' => 'Payment Status', 'value' => ucfirst($waste->paymentStatus->name ?? ''), 'icon' => 'bi-receipt'];
                            $details[] = ['label' => 'Latitude', 'value' => $waste->latitude, 'icon' => 'bi-geo'];
                            $details[] = ['label' => 'Longitude', 'value' => $waste->longitude, 'icon' => 'bi-geo'];
                            $details[] = ['label' => 'Weather', 'value' => $waste->weather_condition, 'icon' => 'bi-cloud-sun'];
                            $details[] = ['label' => 'Created By', 'value' => $waste->createdBy->name ?? '-', 'icon' => 'bi-person-check'];
                        @endphp

                        @foreach ($details as $detail)
                            <div class="col-md-6 col-12">
                                <div class="d-flex align-items-center gap-3 border-bottom pb-3">
                                    <i class="bi {{ $detail['icon'] }} fs-2 text-primary"></i>
                                    <div>
                                        <div class="text-muted small">{{ $detail['label'] }}</div>
                                        <div class="fw-semibold fs-6">{{ $detail['value'] }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($waste->image)
                            <div class="col-12 pt-3">
                                <div>
                                    <div class="text-muted small mb-1">Image</div>
                                    <img src="{{ asset('storage/'.$waste->image) }}" alt="Waste Image" class="img-fluid rounded" style="max-height: 300px;">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>


                <!-- ITEMS LIST -->
                <div class="tab-pane fade" id="items" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Waste Type</th>
                                    <th>Quantity</th>
                                    <th>Unit</th>
                                    <th>Color</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($waste->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item->wasteType->name ?? '-' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->unit->name ?? '-' }}</td>
                                        <td>{{ $item->color->name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">No items found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- PAYMENTS -->
                <div class="tab-pane fade" id="payments" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                    <th>Attachment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($waste->collectionPayments as $index => $pay)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $pay->date }}</td>
                                        <td>{{ number_format($pay->amount, 2) }}</td>
                                        <td>{{ $pay->payment_method }}</td>
                                        <td>{{ $pay->reference_number }}</td>
                                        <td>
                                            @if ($pay->attachment)
                                                <a href="{{ $pay->attachment_url }}" target="_blank">View</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center">No payments found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- MAP -->
                <div class="tab-pane fade" id="map" role="tabpanel">
                    <div id="wasteMap" style="height: 400px;" class="rounded border"></div>
                </div>


            </div> <!-- /.tab-content -->
        </div> <!-- /.card-body -->
    </div> <!-- /.card -->

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const map = L.map('wasteMap').setView([{{ $waste->latitude ?? 0 }}, {{ $waste->longitude ?? 0 }}], 14);

            L.tileLayer('https://stamen-tiles.a.ssl.fastly.net/terrain/{z}/{x}/{y}.jpg', {
                attribution: 'Map tiles by Stamen Design, under CC BY 3.0. Data by OpenStreetMap, under ODbL.',
                maxZoom: 18,
            }).addTo(map);

            L.marker([{{ $waste->latitude ?? 0 }}, {{ $waste->longitude ?? 0 }}])
                .addTo(map)
                .bindPopup('Waste Location')
                .openPopup();
        });
    </script>
@endsection
