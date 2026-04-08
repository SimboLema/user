@extends('kmj.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex flex-column flex-lg-row">
    <div id="kt_sidebar_menu" class="bg-white shadow-sm rounded-4 me-lg-10 mb-10 mb-lg-0 border border-gray-200" style="width: 100%; max-width: 350px; min-width: 300px; height: fit-content;">
        <div class="p-8"> <div class="mb-10 px-3">
                <h3 class="fw-bolder fs-2 text-dark mb-2">Management</h3>
                <p class="text-muted fs-7">Overview & Actions</p>
            </div>

            <ul class="nav flex-column">
                <li class="nav-item mb-4">
                    <a href="{{ route('dashboard') }}" class="nav-link py-4 px-5 text-gray-700 text-hover-primary d-flex align-items-center rounded-3 shadow-sm-hover transition-all">
                        <i class="bi bi-grid-1x2 me-4 fs-3"></i>
                        <span class="fw-bold fs-5">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item mb-4">
                    <a href="#" class="nav-link py-4 px-5 d-flex align-items-center active bg-light-primary rounded-3">
                        <i class="bi bi-shield-check me-4 fs-3 text-primary"></i>
                        <span class="fw-bolder fs-5 text-primary">Insurers List</span>
                    </a>
                </li>
                <li class="nav-item mb-4">
                    <a href="#" class="nav-link py-4 px-5 text-gray-700 text-hover-primary d-flex align-items-center rounded-3">
                        <i class="bi bi-graph-up me-4 fs-3"></i>
                        <span class="fw-bold fs-5">Reports</span>
                    </a>
                </li>

                <div class="separator separator-dashed my-8 border-gray-300"></div>

                <li class="nav-item">
                    <a href="#" class="nav-link py-4 px-5 text-gray-700 text-hover-primary d-flex align-items-center rounded-3">
                        <i class="bi bi-gear me-4 fs-3"></i>
                        <span class="fw-bold fs-5">Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="flex-row-fluid">
        <div class="d-flex justify-content-between align-items-center mb-8">
            <h1 class="fw-bold text-dark m-0">Insurers Directory</h1>
            <button type="button" class="btn btn-primary btn-sm px-6 py-3 shadow-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#kt_modal_add_insuarer">
                <i class="bi bi-plus-lg me-2"></i> Add New Insurer
            </button>
        </div>

        <div class="row g-5">
            @foreach($insuarers as $insuarer)
            <div class="col-xl-4 col-md-6">
                <div class="card border-0 shadow-sm h-100 hover-elevate-up" style="background-color: rgba(245, 248, 250, 0.7);">
                    <div class="card-body p-8 text-center">
                        <div class="symbol symbol-60px symbol-circle mb-5 shadow-sm">
                            <span class="symbol-label bg-white">
                                <i class="bi bi-building fs-1 text-primary"></i>
                            </span>
                        </div>
                        <div class="mb-2">
                            <span class="text-gray-800 fw-bolder fs-5 d-block">{{ $insuarer->name }}</span>
                            <span class="text-muted fw-bold fs-7">{{ $insuarer->email }}</span>
                        </div>
                        <div class="mt-5">
                            <button class="btn btn-sm btn-light-primary fw-bold">View Profile</button>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    /* New Sidebar Enhancements */
    .nav-link {
        transition: all 0.2s ease-in-out;
    }

    .nav-link:hover:not(.active) {
        background-color:
        transform: translateX(5px);
    }

    .transition-all {
        transition: all 0.3s ease;
    }


    .hover-elevate-up {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-elevate-up:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
    }
    .bg-light-primary {
        background-color:
    }
</style>
<div class="modal fade" id="kt_modal_add_insuarer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content rounded-4">
            <div class="modal-header pb-0 border-0 justify-content-end">
                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg fs-2"></i>
                </div>
            </div>

            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                    
                <form action="{{ route('admin.insuarers.store') }}" method="POST">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @csrf
                    <div class="mb-13 text-center">
                        <h1 class="mb-3 text-dark fw-bold">Add New Insurer</h1>
                        <div class="text-muted fw-bold fs-5">Create a new account for the insurance provider</div>
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-bold mb-2">Full Name</label>
                        <input type="text" class="form-control form-control-solid bg-gray-100 border-0" name="name" placeholder="e.g. Jubilee Insurance" required />
                    </div>

                   

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-bold mb-2">Company Code</label>
                        <input type="text" class="form-control form-control-solid bg-gray-100 border-0" name="company_code"  required />
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-bold mb-2">Insuarer Code</label>
                        <input type="text" class="form-control form-control-solid bg-gray-100 border-0" name="insuarer_code" required />
                    </div>

                    <div class="fv-row mb-7">
                        <label class="required fs-6 fw-bold mb-2">Email Address</label>
                        <input type="email" class="form-control form-control-solid bg-gray-100 border-0" name="email" placeholder="contact@insurer.com" required />
                    </div>

                    <div class="fv-row mb-10">
                        <label class="required fs-6 fw-bold mb-2">Password</label>
                        <div class="position-relative">
                            <input type="password" class="form-control form-control-solid bg-gray-100 border-0" name="password" id="password_input" required />
                            <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2" onclick="togglePassword()">
                                <i class="bi bi-eye-slash fs-2"></i>
                            </span>
                        </div>
                        <div class="text-muted fs-8 mt-2">Use 6 or more characters.</div>
                    </div>

                   

                    <div class="text-center">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-10">
                            Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password_input');
        const icon = event.currentTarget.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            input.type = "password";
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        }
    }
</script>
@endsection
