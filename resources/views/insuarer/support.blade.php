@extends('kmj.layouts.app')

@section('title', 'Support Center')

@section('content')
<style>
    :root {
        --brand-primary: #3b82f6;
        --brand-light: #eff6ff;
    }

    .support-container {
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8fafc;
    }

    .support-card-box {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid rgba(0,0,0,0.05);
        max-width: 850px;
        width: 100%;
        padding: 3.5rem;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.08);
        animation: boxReveal 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes boxReveal {
        0% { opacity: 0; transform: scale(0.95) translateY(20px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }

    .contact-option {
        transition: all 0.3s ease;
        border: 1px solid #f1f5f9;
        background: #ffffff;
        text-decoration: none !important;
    }

    .contact-option:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(59, 130, 246, 0.15);
        border-color: var(--brand-primary);
    }

    .icon-square {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        background-color: var(--brand-light);
        color: var(--brand-primary);
    }

    /* Overriding any default Bootstrap colors to force the brand blue */
    .btn-brand-solid {
        background-color: var(--brand-primary) !important;
        border-color: var(--brand-primary) !important;
        color: #ffffff !important;
    }

    .btn-brand-outline {
        background-color: transparent !important;
        border-color: var(--brand-primary) !important;
        color: var(--brand-primary) !important;
    }

    .btn-brand-outline:hover {
        background-color: var(--brand-primary) !important;
        color: #ffffff !important;
    }
</style>

<div class="container support-container">
    <div class="support-card-box text-center">
        <div class="mb-12">
            <h2 class="fw-bolder text-dark mb-3 fs-1">Support Center</h2>
            <p class="text-muted fs-6">Contact our team for any assistance regarding your account or quotations.</p>
        </div>

        <div class="row g-8 mb-12">
            <div class="col-md-6">
                <div class="card contact-option p-9 rounded-4 h-100">
                    <div class="icon-square">
                        <i class="bi bi-envelope-fill fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Email Support</h4>
                    <p class="text-muted small mb-6">Best for inquiries that require documentation or screenshots.</p>
                    <a href="mailto:support@kmj.co.tz" class="btn btn-brand-solid rounded-pill fw-bold px-8 py-3 shadow-sm">
                        Send Email
                    </a>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card contact-option p-9 rounded-4 h-100">
                    <div class="icon-square">
                        <i class="bi bi-telephone-fill fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Phone Support</h4>
                    <p class="text-muted small mb-6">Available Mon-Fri, 08:00 - 17:00 for immediate help.</p>
                    <a href="tel:+25512345678" class="btn btn-brand-outline rounded-pill fw-bold px-8 py-3">
                        Call Now
                    </a>
                </div>
            </div>
        </div>

        <div class="border-top pt-8">
            <a href="{{ route('insuarer.dashboard') }}" class="btn btn-link text-muted text-decoration-none fw-semibold">
                <i class="bi bi-arrow-left-short fs-4 align-middle"></i>
                <span class="align-middle">Back to Dashboard</span>
            </a>
        </div>
    </div>
</div>
@endsection
