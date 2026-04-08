{{-- Modal: New Insurance --}}
<div class="modal fade" id="dashmodal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h4 class="modal-title">New Insurance</h4>
                    <div class="text-muted fs-6">{{ now()->format('d M Y') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4 fs-5">
                    <div class="col-md-4">
                        <div class="card-box">Motor</div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-box">Marine</div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-box">Fire</div>
                    </div>
                    {{-- ongeza zingine hapa --}}
                </div>
            </div>
        </div>
    </div>
</div>


<!--modal 1-->
<div class="modal fade" id="dashmodal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Optional: modal-lg for larger width -->
        <div class="modal-content">

            <!-- Header -->
            <div class="modal-header">
                <div>
                    <h4 class="modal-title">New Insurance</h4>
                    <div class="text-muted fs-6">23 June 2025</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body">
                <!-- Buttons -->
                <div class="row g-4 fs-2">
                    <div class="col-md-4">
                        <div class="card-box  ">Motor</div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-box  ">Miscellaneous & Accidents</div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-box  ">Marine</div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-box  ">Goods In Transit</div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-box  ">Fire</div>
                    </div>
                    <div class="col-md-4">
                        <div class="card-box  ">Engineering</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!--end of modal 1-->

<!--modal2-->
<div class=" modal fade" id="transmodal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">



            <!--begin::Table widget 14-->
            <div class="card card-flush h-md-100">
                <!--begin::Header-->
                <div class="card-header pt-7">
                    <!--begin::Title-->
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Transaction</span>

                        <span class="text-gray-500 mt-1 fw-semibold fs-6">The list of all transaction</span>
                    </h3>
                    <!--end::Title-->

                    <!--begin::Toolbar-->
                    <div class="card-toolbar ms-4 d-flex align-items-center gap-1">
                        <a href="#" class="btn btn-sm btn-danger">New</a>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!--end::Toolbar-->
                </div>
                <div class="card-body pt-6">
                    <!--begin::Table container-->
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table align-items-center ">
                            <!--begin::Table head-->
                            <thead>
                                <tr class="fs-4 fw-bold text-dark border-bottom-2 ">
                                    <th class=" min-w-100px text-center">Type </th>
                                    <th class=" min-w-100px text-center">Created</th>
                                    <th class=" min-w-100px text-center">Start</th>
                                    <th class=" min-w-100px text-center ">Expired</th>
                                    <th class=" min-w-100px text-center ">Agent</th>
                                    <th class=" min-w-100px text-center ">Region</th>
                                    <th class=" min-w-100px text-center ">Payment</th>
                                    <th class=" min-w-100px text-center ">Vehicle</th>
                                    <th class=" min-w-100px text-center ">Premium</th>
                                    <th class=" min-w-100px text-center ">Status</th>
                                    <th class=" min-w-100px text-center "></th>
                                    <th class=" min-w-100px text-center "></th>

                                </tr>
                            </thead>
                            <!--end::Table head-->

                            <!--begin::Table body-->
                            <tbody>

                                <tr class="fs-4 fw-bold text-dark border-bottom-2 ">
                                    <td class="text-gray-600 fw-bold fs-6 text-center">Motor</td>
                                    <td class="text-gray-600 fw-bold fs-6">2021-06-08 | 13:44:39</td>
                                    <td class="text-gray-600 fw-bold fs-6">08 jun 2021</td>
                                    <td class="text-gray-600 fw-bold fs-6">07 jun 2022</td>
                                    <td class="text-gray-600 fw-bold fs-6">Sidra Insurance Agency</td>
                                    <td class="text-gray-600 fw-bold fs-6">Dar es Salaam</td>
                                    <td class="text-gray-600 fw-bold fs-6">mobile</td>
                                    <td class="text-gray-600 fw-bold fs-6">MC358CBA</td>
                                    <td class="text-gray-600 fw-bold fs-6">59,000.00</td>
                                    <td><button class="btn btn-success btn-sm disabled">Success</button></td>
                                    <td class="text-gray-600 fw-bold fs-6"><i
                                            class="fas fa-info fs-2 text-warning ms-8"></i></td>
                                    <td class="text-gray-600 fw-bold fs-3"><i
                                            class="bi bi-exclamation-triangle-fill fs-2 text-danger"></i></td>
                                </tr>

                            </tbody>
                            <!--end::Table body-->
                        </table>

                    </div>
                    <!--end::Table-->
                </div>
                <!--end: Card Body-->
            </div>
            <!--end::Table widget 14-->
        </div>
    </div>
</div>
<!--modal 02-->
<div class="modal fade " id="more" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md-custom">
        <!-- Optional: modal-lg for larger width -->
        <div class="modal-content">

            <div class="card card-flush h-md-100">
                <!--begin::Header-->
                <div class="card-header pt-7">
                    <div class="card-toolbar ms-4 d-flex align-items-center gap-1">
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="w-100 text-center mb-2 text-success">
                        <h2>Details</h2>
                    </div>
                </div>
                <div class="card-body pt-6">
                    <div class="row align-items-start ms-4">
                        <!-- Details Column -->
                        <div class="col-md-8">
                            <p><strong>Name:</strong> Mousa Doumba</p>
                            <p><strong>Expired Date:</strong> 10 July 2025</p>
                            <p><strong>Vehicle:</strong> Motor</p>
                            <p><strong>Agent:</strong> Sidra Insurance Agency</p>
                            <p><strong>Region:</strong> Dar es Salaam</p>
                            <p><strong>Payment:</strong> Mobile</p>
                            <p><strong>Premium:</strong> 59,000.00</p>
                            <p><strong>Start Date:</strong> 09 June 2025</p>
                            <p><strong>Created At:</strong> 2021-06-08 | 13:44:39</p>
                        </div>

                        <!-- QR and Button Column -->
                        <div class="col-md-4 d-flex flex-column align-items-end" style="margin-top: -3%;">
                            <img src="board_files/qr.png" alt="QRCode" class="img-fluid mb-3"
                                style="max-width: 200px; height: auto;">

                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset('assets/dash/board_files/TIRAlogo.png') }}" alt="TIRA Logo"
                                    style="width: 50px; height: auto;margin-left: -45%;">
                                <button class="btn btn-warning btn-sm px-4 d-flex" style="color: white;">Check
                                    Status</button>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    </div>
</div>
<!--modal 02 end-->
<!--end of modal 2-->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function(el) {
            return new bootstrap.Popover(el, {
                container: 'body', // Ensures proper positioning
                template: `
                <div class="popover" role="tooltip">
                    <div class="popover-arrow"></div>
                    <div class="popover-body p-3"></div>
                </div>
            `
            });
        });
    });
</script>
