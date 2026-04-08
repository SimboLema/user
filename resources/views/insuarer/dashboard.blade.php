@extends('kmj.layouts.app')

@section('title', 'Insuarer Dashboard')

@section('content')
<style>
    :root {
        --sidebar-bg: #111827;
        --brand-primary: #3b82f6;
        --brand-light: #eff6ff;
        --app-bg: #f8fafc;
        --text-main: #1e293b;
        --text-muted: #64748b;
    }

    body { background-color: var(--app-bg); font-family: 'Inter', sans-serif; color: var(--text-main); }

    /* Sidebar Styling */
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
    }
    .menu-link:hover { background: rgba(255, 255, 255, 0.05); color: #fff !important; }
    .menu-link.active {
        background: var(--brand-primary);
        color: #fff !important;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    .menu-link i { font-size: 1.2rem; margin-right: 12px; }

    /* Unified Card Styling */
    .stat-card {
        border: 1px solid rgba(0,0,0,0.05);
        border-radius: 16px;
        background: #ffffff;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05) !important;
    }

    /* Icon Box - Unified Color */
    .icon-box {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--brand-light);
        color: var(--brand-primary);
        border-radius: 12px;
    }

    .stat-number {
        font-size: 2.25rem;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -0.02em;
    }

    /* Glass Toolbar */
    .glass-toolbar {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(0,0,0,0.06);
        position: sticky;
        top: 0;
        z-index: 100;
    }
</style>

<div class="d-flex flex-row min-vh-100">
    <nav id="sidebar" class="shadow-lg p-3">
        <div class="d-flex align-items-center mb-10 px-3">
        </div>

        <ul class="nav flex-column px-2">
            <li class="small text-uppercase text-muted fw-bold mb-3 opacity-50 px-3" style="font-size: 0.7rem;">Main Menu</li>
            <li><a href="{{ route('insuarer.dashboard') }}" class="menu-link active"><i class="bi bi-grid-fill"></i> Dashboard</a></li>
            <li><a href="{{ route('insuarer.quotations') }}" class="menu-link"><i class="bi bi-file-text"></i> Quotations</a></li>
        </ul>
    </nav>

    <div class="flex-grow-1">
        <header class="glass-toolbar py-4 mb-8">
            <div class="container-fluid px-8 d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-bold mb-0">Welcome, {{ Auth::guard('insuarer')->user()->name }}</h5>
                    <p class="text-muted small mb-0">Overview of your insurance portfolio</p>
                </div>
                <div class="d-flex gap-3">
                    <button id="toggleSidebar" class="btn btn-icon btn-light border-0 rounded-circle">
                        <i class="bi bi-list fs-4"></i>
                    </button>
                    <a href="{{ route('insuarer.support') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                        Support Center
                    </a>
                </div>
            </div>
        </header>

        <div class="container-fluid px-8">
            <div class="row g-6">
                <div class="col-md-4">
                    <div class="card stat-card shadow-sm border-0">
                        <div class="card-body p-7">
                            <div class="d-flex justify-content-between align-items-center mb-5">
                                <div class="icon-box">
                                    <i class="bi bi-clock-history fs-3"></i>
                                </div>
                                <span class="badge bg-light text-primary border border-primary border-opacity-10 px-3">Pending</span>
                            </div>
                            <h2 class="stat-number mb-1">{{ $quotations }}</h2>
                            <p class="text-muted fw-medium mb-4">Quotations</p>
                            <div class="progress h-4px mb-4 bg-light">
                                <div class="progress-bar bg-primary" style="width: 40%"></div>
                            </div>
                            <a href="{{ route('insuarer.quotations') }}" class="btn btn-light-primary btn-sm w-100 rounded-pill fw-bold">View List</a>
                        </div>
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
