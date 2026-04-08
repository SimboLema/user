@extends('layout.index')

@section('page-title')
Recycling Material
@endsection

@section('content')


<div class="card shadow-sm">
    <div class="card-body">
        <!-- TABS -->
        <ul class="nav nav-tabs d-flex flex-nowrap overflow-auto border-0 mb-3" id="recyclingTabs" role="tablist" style="gap: 0.5rem;">
            <li class="nav-item flex-shrink-0" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview"
                    type="button" role="tab">Overview</button>
            </li>
            <li class="nav-item flex-shrink-0" role="presentation">
                <button class="nav-link" id="items-tab" data-bs-toggle="tab" data-bs-target="#items"
                    type="button" role="tab">Input & Output Items</button>
            </li>

        </ul>

        <!-- TAB CONTENT -->
        <div class="tab-content pt-4" id="recyclingTabsContent">

            <!-- OVERVIEW -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row g-4">
                    @php
                        $details = [
                            ['label' => 'Facility', 'value' => $material->facility->name ?? '-', 'icon' => 'bi-building'],
                            ['label' => 'Process', 'value' => ucfirst($material->process), 'icon' => 'bi-arrow-repeat'],
                            ['label' => 'Waste Type', 'value' => $material->wasteType->name ?? '-', 'icon' => 'bi-trash'],
                            ['label' => 'Input Quantity', 'value' => $material->input_quantity . ' ' . ($material->unit->name ?? ''), 'icon' => 'bi-box-seam'],
                            ['label' => 'Output Product', 'value' => $material->outputProduct->name ?? '-', 'icon' => 'bi-box'],
                            ['label' => 'Output Quantity', 'value' => $material->output_product_quantity . ' ' . ($material->unit->name ?? ''), 'icon' => 'bi-box-seam'],
                            ['label' => 'Created By', 'value' => $material->createdBy->name ?? '-', 'icon' => 'bi-person-check'],
                            ['label' => 'Created At', 'value' => $material->created_at->format('Y-m-d') ?? '-', 'icon' => 'bi-calendar'],
                        ];
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

                    @if ($material->image)
                        <div class="col-12 pt-3">
                            <div>
                                <div class="text-muted small mb-1">Material Image</div>
                                <img src="{{ asset('storage/'.$material->image) }}" alt="Material Image" class="img-fluid rounded" style="max-height: 300px;">
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- INPUT & OUTPUT ITEMS -->
            <div class="tab-pane fade" id="items" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Input</td>
                                <td>{{ $material->wasteType->name ?? '-' }}</td>
                                <td>{{ $material->input_quantity }}</td>
                                <td>{{ $material->unit->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Output</td>
                                <td>{{ $material->outputProduct->name ?? '-' }}</td>
                                <td>{{ $material->output_product_quantity }}</td>
                                <td>{{ $material->unit->name ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>



        </div> <!-- /.tab-content -->
    </div> <!-- /.card-body -->
</div> <!-- /.card -->

@endsection
