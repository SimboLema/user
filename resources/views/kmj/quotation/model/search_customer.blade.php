<!-- Existing Customer Modal -->
<div class="modal fade" id="existingCustomerModal" tabindex="-1" aria-labelledby="existingCustomerLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title" id="existingCustomerLabel text-white"><i
                        class="bi bi-person-lines-fill me-2"></i>Existing
                    Customer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search Input -->
                <div class="mb-3 position-relative">
                    <input type="text" id="customerSearch" class="form-control"
                           placeholder="Search by Name, Phone or Email">
                    <div id="spinner"
                         class="spinner-border position-absolute top-50 end-0 translate-middle-y me-2"
                         role="status"
                         style="display: none; width: 2rem; height: 2rem; border-width: 0.25em; border-top-color: #9aa89b; border-right-color: transparent; border-bottom-color: transparent; border-left-color: transparent;">
                    </div>
                </div>


                <!-- Table for results -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="customerResults">
                        <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Results loaded via AJAX -->
                        </tbody>
                    </table>
                </div>

                <div id="noResults" class="text-center text-muted mt-3" style="display: none;">
                    No customers found.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        function fetchCustomers(query = '') {
            // Show spinner
            $('#spinner').show();

            $.ajax({
                url: '/dash/customers/search',
                method: 'GET',
                data: {q: query},
                success: function (data) {
                    const tbody = $('#customerResults tbody');
                    tbody.empty();

                    if (data.length === 0) {
                        $('#noResults').show();
                    } else {
                        $('#noResults').hide();
                        data.forEach(customer => {
                            const row = `<tr>
                            <td>${customer.name}</td>
                            <td>${customer.phone}</td>
                            <td>${customer.email_address}</td>
                            <td>
                                <button class="btn btn-sm select-customer" style="background-color: #003153;color:white"  data-customer-id="${customer.id}" data-customer-name="${customer.name}" data-customer-phone="${customer.phone}" data-customer-email="${customer.email_address}">
                                    Select
                                </button>
                            </td>
                        </tr>`;
                            tbody.append(row);
                        });
                    }
                },
                error: function () {
                    $('#noResults').show();
                },
                complete: function () {
                    // Hide spinner after request completes
                    $('#spinner').hide();
                }
            });
        }

        // Fetch initial empty or all customers
        fetchCustomers();

        // Search input event
        $('#customerSearch').on('input', function () {
            const query = $(this).val();
            fetchCustomers(query);
        });

        // Select customer
        $(document).on('click', '.select-customer', function () {
            const customerId = $(this).data('customer-id');
            const customerName = $(this).data('customer-name');
            const customerPhone = $(this).data('customer-phone');
            const customerEmail = $(this).data('customer-email');

            console.log('Customer selected from modal:');
            console.log('ID:', customerId);
            console.log('Name:', customerName);
            console.log('Phone:', customerPhone);
            console.log('Email:', customerEmail);

            // Bootstrap 5 modal instance
            const modalEl = document.getElementById('existingCustomerModal');
            const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);


            // Wait until modal is fully hidden
            modalEl.addEventListener('hidden.bs.modal', function handler() {
                modalEl.removeEventListener('hidden.bs.modal', handler);

                // insert value in input form
                $('#customer_id').val(customerId);
                $('#customer_name').val(customerName);
                $('#customer_phone').val(customerPhone);
                $('#customer_email').val(customerEmail);


                // Console to verify values populated in fields
                console.log('After modal hide, input values:');
                console.log('id:', $('#customer_id').val());
                console.log('Name:', $('#customer_name').val());
                console.log('Phone:', $('#customer_phone').val());
                console.log('Email:', $('#customer_email').val());

                // if ($('#customer_id').length === 0) {
                //     $('<input>').attr({
                //         type: 'hidden',
                //         id: 'customer_id',
                //         name: 'customer_id',
                //         value: customerId
                //     }).appendTo('form');
                // } else {
                //     $('#customer_id').val(customerId);
                // }

                // ✅ Show customer info summary box
                if (customerId && parseInt(customerId) > 0) {
                    $('#selectedCustomerName').text(customerName);
                    $('#selectedCustomerPhone').text(customerPhone);
                    $('#selectedCustomerEmail').text(customerEmail);
                    $('#selectedCustomerInfo').removeClass('d-none').hide().fadeIn(300);
                } else {
                    $('#selectedCustomerInfo').addClass('d-none');
                }

                // Clear previous validation
                const step1Fields = document.querySelectorAll('#step1 [required]');
                step1Fields.forEach(field => {
                    field.style.borderColor = '';
                    const errorEl = field.nextElementSibling;
                    if (errorEl && errorEl.classList.contains('field-error')) {
                        errorEl.remove();
                    }
                });


                // Optional: Move to next step after values are populated
                changeStep(1);

            });
            // Close modal first
            modalInstance.hide();

        });

    });


</script>
