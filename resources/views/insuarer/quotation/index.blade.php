@extends('kmj.layouts.app')

@section('title', 'My Quotations')

@section('content')
<style>
    :root {
        --sidebar-bg: #111827;
        --brand-primary: #3b82f6;
        --app-bg: #f8fafc;
        --text-main: #1e293b;
    }

    body { background-color: var(--app-bg); font-family: 'Inter', sans-serif; color: var(--text-main); }

    /* Sidebar - Matching Dashboard */
    #sidebar {
        min-width: 260px;
        background: var(--sidebar-bg);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #sidebar.collapsed { margin-left: -260px; }

    .menu-link {
        color: #9ca3af !important;
        padding: 0.85rem 1.25rem;
        margin-bottom: 0.25rem;
        border-radius: 10px;
        font-weight: 500;
        display: flex;
        align-items: center;
        transition: 0.2s;
        text-decoration: none;
    }
    .menu-link:hover { background: rgba(255, 255, 255, 0.05); color: #fff !important; }
    .menu-link.active {
        background: var(--brand-primary);
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    .menu-link i { font-size: 1.2rem; margin-right: 12px; }

    /* Glass Toolbar */
    .glass-toolbar {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(0,0,0,0.06);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    /* Table & Card Refinement */
    .content-card {
        border-radius: 16px;
        border: 1px solid rgba(0,0,0,0.05);
        background: #fff;
    }
    .table thead th {
        background-color: #fcfcfd;
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.025em;
        color: #64748b;
        padding: 1.25rem 1rem;
        border-bottom: 1px solid #f1f5f9;
    }
    .table tbody td {
        padding: 1.25rem 1rem;
        vertical-align: middle;
        font-size: 0.9rem;
        border-bottom: 1px solid #f8fafc;
    }

    /* Search Input */
    .search-input {
        background-color: #f1f5f9 !important;
        border: none !important;
        border-radius: 10px !important;
        padding-left: 2.5rem !important;
    }

    /* Status Badge Refinement */
    .badge-status {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.75rem;
    }
</style>

<div class="d-flex flex-row min-vh-100">
    <nav id="sidebar" class="shadow-lg p-3">
        <div class="d-flex align-items-center mb-10 px-3">

        </div>

        <ul class="nav flex-column px-2">
            <li class="small text-uppercase text-muted fw-bold mb-3 opacity-50 px-3" style="font-size: 0.7rem;">Main Menu</li>
            <li><a href="{{ route('insuarer.dashboard') }}" class="menu-link"><i class="bi bi-grid-fill"></i> Dashboard</a></li>
            <li><a href="{{ route('insuarer.quotations') }}" class="menu-link active"><i class="bi bi-file-text-fill"></i> Quotations</a></li>
        </ul>
    </nav>

    <div class="flex-grow-1">
        <header class="glass-toolbar py-4 mb-8">
            <div class="container-fluid px-8 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0">Quotations Registry</h5>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0" style="--bs-breadcrumb-divider: '›';">
                            <li class="breadcrumb-item small"><a href="#" class="text-muted text-decoration-none">Home</a></li>
                            <li class="breadcrumb-item small text-primary active fw-medium">Quotations List</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-3">
                    <button id="toggleSidebar" class="btn btn-icon btn-light border-0 rounded-circle">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                </div>
            </div>
        </header>

        <div class="container-fluid px-8 pb-8">
            <div class="card content-card shadow-sm border-0">
                <div class="card-header border-0 pt-6 bg-transparent">
                    <div class="card-title w-100 d-flex justify-content-between align-items-center">
                        <form action="{{ route('insuarer.quotations') }}" method="GET" class="position-relative">
                            <i class="bi bi-search position-absolute top-50 translate-middle-y ms-4 text-muted"></i>
                            <input type="text"
                                   name="search"
                                   value="{{ $search ?? '' }}"
                                   class="form-control search-input w-350px ps-12"
                                   placeholder="Search by customer, ID, or risk...">

                            @if($search)
                                <a href="{{ route('insuarer.quotations') }}"
                                   class="position-absolute top-50 end-0 translate-middle-y me-4 text-muted hover-primary">
                                    <i class="bi bi-x-circle-fill"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="card-body py-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead>
                                <tr class="text-start">
                                    <th class="ps-4">#</th>
                                    <th>Customer</th>
                                    <th>Product & Risk</th>
                                    <th>Premium (TZS)</th>
                                    <th>Created Date</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600">
                                @forelse ($quotations as $quotation)
                                    <tr>
                                        <td class="ps-4 text-muted">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold">{{ ucwords(strtolower($quotation->customer->name)) }}</span>
                                                <span class="text-muted small">ID: #Q-{{ $quotation->id + 1000 }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-medium text-dark">{{ $quotation->coverage->product->insurance->name ?? '-' }}</div>
                                            <div class="text-muted small">{{ $quotation->coverage->risk_name ?? '-' }}</div>
                                        </td>
                                        <td class="fw-bold text-dark">
                                            {{ number_format($quotation->total_premium_including_tax) }}
                                        </td>
                                        <td class="small">{{ $quotation->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @php
                                                $statusColors = match($quotation->status) {
                                                    'success'   => 'bg-success bg-opacity-10 text-success border-success border-opacity-25',
                                                    'pending'   => 'bg-warning bg-opacity-10 text-warning border-warning border-opacity-25',
                                                    'cancelled' => 'bg-danger bg-opacity-10 text-danger border-danger border-opacity-25',
                                                    default     => 'bg-secondary bg-opacity-10 text-dark border-secondary border-opacity-25'
                                                };
                                            @endphp
                                            <span class="badge badge-status border {{ $statusColors }}">
                                                {{ ucfirst($quotation->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end align-items-center gap-2">
                                                <a href="{{ route('insuarer.quotation.show', $quotation->id) }}"
                                                   class="btn btn-sm btn-light-primary rounded-pill px-3 fw-bold">
                                                    View
                                                </a>

                                                @if ($quotation->status === 'pending')
                                                    <form action="{{ route('insuarer.quotation.updateStatus', $quotation->id) }}"
                                                          method="POST" class="d-flex gap-1">
                                                        @csrf @method('PUT')
                                                        <select name="status" class="form-select form-select-sm rounded-pill" style="width: 110px;" required>
                                                            <option value="" disabled selected>Action</option>
                                                            <option value="approved">Approve</option>
                                                            <option value="cancelled">Cancel</option>
                                                        </select>
                                                        <button type="submit" class="btn btn-dark btn-sm rounded-pill px-3">Go</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-20">
                                            <div class="opacity-25 mb-4">
                                                <i class="bi bi-folder-x fs-1" style="font-size: 4rem !important;"></i>
                                            </div>
                                            <p class="text-muted fw-medium">No quotations found in the registry.</p>
                                        </td>
                                    </tr>
                                @endforelse
                                <div class="mt-4 px-4">
                                    {{ $quotations->appends(['search' => $search])->links() }}
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleSidebar');
    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
    });
</script>
@endsection
