
<!-- Vendor JS Files -->
<script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{asset('assets/vendor/libs/popper/popper.js')}}"></script>
<script src="{{asset('assets/vendor/js/bootstrap.js')}}"></script>
<script src="{{asset('assets/vendor/libs/node-waves/node-waves.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@algolia/autocomplete-js.js')}}"></script>
<script src="{{asset('assets/vendor/libs/pickr/pickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
<script src="{{asset('assets/vendor/libs/hammer/hammer.js')}}"></script>
<script src="{{asset('assets/vendor/libs/i18n/i18n.js')}}"></script>
<script src="{{asset('assets/vendor/js/menu.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/popular.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/@form-validation/auto-focus.js')}}"></script>
<script src="{{asset('assets/js/main.js')}}"></script>
<script src="{{asset('assets/js/pages-auth.js')}}"></script>
{{-- <script src="{{asset('assets/js/tables-datatables-basic.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script> --}}
<script src="{{asset('assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/tagify/tagify.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
<script src="{{asset('assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bloodhound/bloodhound.js')}}"></script>
<script src="{{asset('assets/js/forms-selects.js')}}"></script>
<script src="{{asset('assets/js/forms-tagify.js')}}"></script>
<script src="{{asset('assets/js/forms-typeahead.js')}}"></script>
<script src="{{asset('assets/vendor/libs/cleave-zen/cleave-zen.js')}}"></script>
<script src="{{asset('assets/vendor/libs/bs-stepper/bs-stepper.js')}}"></script>
<script src="{{asset('assets/js/form-wizard-numbered.js')}}"></script>
<script src="{{asset('assets/js/form-wizard-validation.js')}}"></script>

@include('layout.select2')
@include('layout.dataTable')
<div id="forFlash"></div>
<script>

      var mousePositionX=0;
      var mousePositionY=0;

      $(document).ready(function() {
            //check report body to hide or show
            checkReportBody()

            setTimeout(() => {
                 // getRealTimeAssetsWithTask()
            }, 5000);

            // initialize datetime
            // flatpickr(".datetimepicker", {
            //       enableTime: true,
            //       dateFormat: "Y-m-d H:i:s ", // 'K' for AM/PM format
            //       time_24hr: true,
            //       minTime: "00:01", // Minimum time allowed (1 minute past midnight)
            //       maxTime: "23:59", // Maximum time allowed (just before midnight)
            // });

            // set mouse position
            document.addEventListener('mousemove', function(event) {
                  mousePositionX = event.pageX;
                  mousePositionY = event.pageY;
            });

            // onclick document
            document.addEventListener('click', function(event) {
                  removeHoverAll()
            });



           initializeDataTable()
           initializeSelector()
           initializeMultipleSelector()
      });

      function initializeNormalSelector() {
        $('.select3').select2('destroy').select2({ theme: 'bootstrap-4' });

      }

      function initializeSelector() {
            $(".select2").select2({
              theme: 'bootstrap-5',
              dropdownParent: $('#largeModal'),
               width: '100%'
            })
      }

      function initializeMultipleSelector() {
            $(".select2-multi").select2({
              multiple: true,
              theme: 'bootstrap4',
            })
      }

      function initializeDataTable() {
            var tables = [];

            $('.dataTable').each(function (index) {
                  // Unique key for each table instance, based on its index
                  const tableKey = `tableVisibility_${index}`;

                  if (!$.fn.DataTable.isDataTable(this)) {
                        const tableInstance = $(this).DataTable({
                              "lengthMenu": [
                                    [10, 50, 100, 1000, -1],
                                    [10, 50, 100, 1000, "All"]
                              ],

                              dom: 'Bfrtip',
                              buttons: [
                                    {
                                          extend: 'pdf',
                                          exportOptions: {
                                          columns: ':visible' // Export only visible columns
                                          }
                                    },
                                    {
                                          extend: 'excel',
                                          exportOptions: {
                                          columns: ':visible' // Export only visible columns
                                          }
                                    },
                                    {
                                          extend: 'csv',
                                          exportOptions: {
                                          columns: ':visible' // Export only visible columns
                                          }
                                    }
                              ],
                              ordering: false,
                        });
                  }
            });

            document.querySelectorAll('a.toggle-vis').forEach((el) => {
                  el.addEventListener('click', function (e) {
                        e.preventDefault();

                        // Get column index from data attribute
                        const columnIdx = $(this).data('column');

                        // Toggle column visibility on each table instance
                        tables.forEach((tableInstance, index) => {
                        const column = tableInstance.column(columnIdx);
                        const isVisible = !column.visible();
                        column.visible(isVisible);

                        // Save updated visibility status to localStorage
                        const tableKey = `tableVisibility_${index}`;
                        const visibility = tables[index].columns().visible().toArray();
                        localStorage.setItem(tableKey, JSON.stringify(visibility));
                        });
                  });
            });
      }


      function initializeHideShowDataTable() {
            const tables = [];

            $('.dataTable').each(function () {
                  // Use provided data-table-id or fallback to a consistent default key
                  const tableId = $(this).attr('data-table-id');
                  const tableKey = `tableVisibility_${tableId}`;

                  if (!$.fn.DataTable.isDataTable(this)) {
                        const tableInstance = $(this).DataTable({
                        "lengthMenu": [
                              [50, 50, 100, 1000, -1],
                              [50, 50, 100, 1000, "All"]
                        ],
                        dom: 'Bfrtip',
                        buttons: [
                              {
                                    extend: 'pdf',
                                    exportOptions: { columns: ':visible' }
                              },
                              {
                                    extend: 'excel',
                                    exportOptions: { columns: ':visible' }
                              },
                              {
                                    extend: 'csv',
                                    exportOptions: { columns: ':visible' }
                              }
                        ],
                        ordering: false,
                        });

                        tables.push({ instance: tableInstance, tableKey });

                        // Retrieve and apply saved visibility state
                        const savedVisibility = JSON.parse(localStorage.getItem(tableKey)) || [];
                        if (savedVisibility.length) {
                        savedVisibility.forEach((isVisible, columnIdx) => {
                              tableInstance.column(columnIdx).visible(isVisible);
                        });
                        }

                        // Create toggle checkboxes for each column
                        const columnToggleMenu = document.getElementById('columnToggleMenu');
                        let selectAllCheckbox;

                        // Create "Select All" checkbox
                        selectAllCheckbox = document.createElement('div');
                        selectAllCheckbox.className = 'dropdown-item';
                        selectAllCheckbox.innerHTML = `<input type="checkbox" id="select-all-checkbox-${tableId}"> Select All`;
                        columnToggleMenu.appendChild(selectAllCheckbox);

                        tableInstance.columns().every(function (colIdx) {
                        const column = this;
                        const colTitle = $(column.header()).text();

                        const checkbox = document.createElement('div');
                        checkbox.className = 'dropdown-item';
                        checkbox.innerHTML = `<input type="checkbox" class="column-toggle-checkbox" data-table-id="${tableId}" data-column="${colIdx}" ${column.visible() ? 'checked' : ''}> ${colTitle}`;
                        columnToggleMenu.appendChild(checkbox);
                        });

                        // Handle "Select All" checkbox change
                        $(document).on('change', `#select-all-checkbox-${tableId}`, function () {
                        const isChecked = $(this).prop('checked');
                        tableInstance.columns().every(function (colIdx) {
                              const column = this;
                              column.visible(isChecked);

                              // Save visibility state
                              const visibilityState = tableInstance.columns().visible().toArray();
                              localStorage.setItem(tableKey, JSON.stringify(visibilityState));
                        });

                        // Update individual column checkboxes state
                        $(`.column-toggle-checkbox[data-table-id="${tableId}"]`).prop('checked', isChecked);
                        });

                  }
            });

            // Event delegation for individual column checkbox change
            $(document).on('change', '.column-toggle-checkbox', function () {
                  const tableId = $(this).data('table-id');
                  const columnIdx = $(this).data('column');
                  const isVisible = $(this).is(':checked');

                  // Find the table instance and toggle column visibility
                  tables.forEach(({ instance, tableKey }) => {
                        if (tableKey === `tableVisibility_${tableId}`) {
                        const column = instance.column(columnIdx);
                        column.visible(isVisible);

                        // Update and save visibility state for this table
                        const visibilityState = instance.columns().visible().toArray();
                        localStorage.setItem(tableKey, JSON.stringify(visibilityState));
                        }
                  });

                  // Check or uncheck "Select All" checkbox based on column visibility
                  const allColumnsVisible = tables.every(({ instance, tableKey }) => {
                        if (tableKey === `tableVisibility_${tableId}`) {
                        return instance.columns().visible().every((visible) => visible); // Check if all columns are visible
                        }
                        return true;
                  });

                  // Update the "Select All" checkbox state based on the visibility of all columns
                  $(`#select-all-checkbox-${tableId}`).prop('checked', allColumnsVisible);
            });
      }



      function removeClassByPrefix(node, prefix) {
            for (let i = 0; i < node.classList.length; i++) {
                  let value = node.classList[i];
                  if (value.startsWith(prefix)) {
                  node.classList.remove(value);
                  }
            }
      }



      function disableBtn(btn,access){

            if(access){
                  $('#'+btn).prop("disabled", true);
                  // add spinner to button
                  $('#'+btn).html(
                  `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`
                  );
            }else{
                  $('#'+btn).prop("disabled", false);
                  // add spinner to button
                  $('#'+btn).html("Save")
            }
      }

      function showLoader(btn,access){
            if(access){
                  $('#'+btn).html(
                  `<div class="loaderContainer">
                        <div class="row">
                        <div class="col-sm-6 text-center">
                              <div class="loader5"></div></div>
                        </div>
                  </div> `
                  );
            }else{
                  $('#'+btn).html("");
            }

      }

      function removeSpecialCharacter(text){
            var sanitizedValue = text.replace(/[!@#$%^&*()\-_=+\[\]{}|\\;:'",.<script>\/?]/g, "");
            return sanitizedValue
      }

      function showFlashMessage(indicator,message) {

            if(indicator=="danger"){
                  $("#forFlash").html('<div id="flash-message"><span class="flashmessage"></span></div>')
                  var flashMessage = document.getElementById('flash-message');
            }
            if(indicator=="success"){
                  $("#forFlash").html('<div id="flash-message-success"><span class="flashmessage"></span></div>')
                  var flashMessage = document.getElementById('flash-message-success');
            }
            if(indicator=="warning"){
                  $("#forFlash").html('<div id="flash-message-warning"><span class="flashmessage"></span></div>')
                  var flashMessage = document.getElementById('flash-message-warning');
            }

            var messageElement = flashMessage.querySelector('.flashmessage');

            messageElement.innerText = message;
            flashMessage.style.display = 'block';

            var secondsLeft = 10;
            var countdown = setInterval(function() {
            secondsLeft--;

            if (secondsLeft >= 0) {
                  messageElement.innerText = message + ' (' + secondsLeft + 's)';
            } else {
                  clearInterval(countdown);
                  flashMessage.style.display = 'none';
                  $("#forFlash").html('')
            }
            }, 1000);
      }

      function searchText(value,input_id,output_id,inputResult,view){

            $("#"+output_id).val("")
            jQuery.ajax({
                  type: "GET",
                  url: "/logistic/searchText",
                  data:{value:value,input_id:input_id,output_id:output_id,inputResult:inputResult,view:view},
                  dataType:'html',
                  success: function(data) {
                        $("#"+inputResult).html(data)
                  }

            });
      }

      function searchMultipleText(value,input_id,output_id,inputResult,view){

            $("#"+output_id).val("")
            jQuery.ajax({
                  type: "GET",
                  url: "/logistic/searchMultipleText",
                  data:{value:value,input_id:input_id,output_id:output_id,inputResult:inputResult,view:view},
                  dataType:'html',
                  success: function(data) {
                        $("#"+inputResult).html(data)
                  }

            });
      }


      function showFile(src) {
            var iframeHtml = '<iframe src="' + src + '" style="width: 100%; height:500px;" frameborder="1"></iframe>';
            $("#externalLargeModalBody").html(iframeHtml);
            $("#externalLargeModal").modal("show");
      }

      function addOutsideCategory(title,src) {

            $.ajax({
                  url: '/sales/addOutsideCategory',
                  type: 'GET',
                  data: {
                        'src': src
                  },
                  cache: false,
                  success: function (data) {
                        $("#commonModalLabel").html(title);
                        $("#commonModalOverBodyDiv").html(data);
                        $("#commonModalOver").modal("show");
                  },

            });

      }

      function copyText(text) {
            var name = text.trim(); // Remove any leading or trailing whitespace

            // Create a temporary input element to copy the text
            var tempInput = document.createElement("input");
            tempInput.value = name;
            document.body.appendChild(tempInput);

            // Select the text and copy it to the clipboard
            tempInput.select();
            document.execCommand("copy");

            // Remove the temporary input
            document.body.removeChild(tempInput);

            // Optionally, provide feedback to the user
            showFlashMessage("success", "Text copied: " + name);
      }

      function showHover(id,type) {
            var x = mousePositionX + 10;
            var y = mousePositionY + 10;


            var contentCallback = function(content) {
                  removeHoverAll();
                  // Show the content at the specified position
                  var tooltip = document.createElement('div');
                  tooltip.innerHTML = content;
                  tooltip.className = 'card hoverDiv';
                  tooltip.style.position = 'fixed';
                  tooltip.style.top = y + 'px';
                  tooltip.style.left = x + 'px';
                  tooltip.style.padding = '5px';
                  tooltip.style.zIndex = '9999';
                  document.body.appendChild(tooltip);

                  // Calculate tooltip position to ensure it's fully visible
                  var tooltipRect = tooltip.getBoundingClientRect();
                  var viewportWidth = window.innerWidth || document.documentElement.clientWidth;
                  var viewportHeight = window.innerHeight || document.documentElement.clientHeight;
                  var newX = x + 20; // Offset to the right of the cursor
                  var newY = y - tooltipRect.height - 20; // Offset above the cursor

                  // Adjust tooltip position if it goes out of the viewport
                  if (newX + tooltipRect.width > viewportWidth) {
                        newX = viewportWidth - tooltipRect.width - 20; // Move to the left if out of viewport
                  }
                  if (newY < 0) {
                        newY = y + 20; // Move below the cursor if out of viewport
                  }

                  // Set the adjusted position
                  tooltip.style.left = newX + 'px';
                  tooltip.style.top = newY + 'px';

                  // Add event listener to remove the tooltip when mouse moves out
                  tooltip.addEventListener('mouseleave', function(e) {
                        if (tooltip.parentNode) {
                        tooltip.parentNode.removeChild(tooltip);
                        }

                        removeHoverAll();
                  });
            };

            removeHoverAll()
            if (type == "asset") {
                  getAssetContent(id, contentCallback);
            } else if (type == "task") {
                  getTaskContent(id, contentCallback);
            } else if (type == "leads") {
                  getLeadsStatusFromPipeline(id, contentCallback);
            } else if (type == "deals") {
                  getDealsStatusFromPipeline(id, contentCallback);
            }

      }

      function toggleSecondView(view1,view2) {
            var getView = $('#'+view1);
            var getView2 = $('#'+view2);
            var icon =$("#toggleIcon")

            if (getView.hasClass('col-md-5')) {
                  // If getView is 5 columns, switch it to 12 columns
                  getView.removeClass('col-md-5').addClass('col-md-12');
                  getView2.hide();
                  $(".hide").show();
                  icon.html('<i class="fa fa-angle-double-left"></i>')
            } else {
                  // If getView is 12 columns, switch it back to 5 columns
                  getView.removeClass('col-md-12').addClass('col-md-5');
                  getView2.show();
                  $(".hide").hide();
                  icon.html('<i class="fa fa-angle-double-right"></i>')
            }
      }

      function removeHoverAll(){
            var tooltips = document.querySelectorAll('.hoverDiv');
            if(tooltips){
                  tooltips.forEach(function(tooltipElement) {
                        tooltipElement.parentNode.removeChild(tooltipElement);
                  });
            }
      }

      function getRealTimeAssetsWithTask() {
            // Perform the fetch operation to retrieve data from the server
            fetch('/logistic/afritrack/getRealTimeAssetsWithTask')
            .then(response => response.json())
            .then(data => {
                  // Process the retrieved data here
                  console.log('Data fetched:', data);
            })
            .catch(error => {
                  // Handle any errors that occur during the fetch operation
                  console.error('Error fetching data:', error);
            })
            .finally(() => {
                  // Schedule the next fetch operation after 1 minute (60000 milliseconds)
                  setTimeout(getRealTimeAssetsWithTask, 120000);
            });
      }

      function showHideReportBody() {
            var showReportBody = localStorage.getItem('showReportBody');
            if (showReportBody === 'true') { // Compare with string 'true'
                  localStorage.setItem('showReportBody', 'false'); // Set as string 'false'
            } else {
                  localStorage.setItem('showReportBody', 'true'); // Set as string 'true'
            }
            checkReportBody();
      }

      function checkReportBody() {
            var showReportBody = localStorage.getItem('showReportBody');
            if (showReportBody === 'true') { // Compare with string 'true'
                  $(".showReportBody").fadeIn();
                  $("#showReportBody").html('<i class="bi bi-eye-slash"></i> Hide Summary');
            } else {
                  $(".showReportBody").fadeOut();
                  $("#showReportBody").html('<i class="bi bi-eye"></i> Show Summary');
            }
      }

      function generateUniqueNumber(table,column,id){
            jQuery.ajax({
                  type: "GET",
                  url: "/generateUniqueNumber/",
                  data:{table:table,column:column},
                  dataType: 'json',
                  success: function (data) {
                        $("#"+id).val(data);
                  }
            });
      }

      // Function to update the hidden input associated with the contenteditable div
      function updateHiddenField(event) {
            // Get the current contenteditable div
            var contentEditableDiv = event.target;

            console.log(contentEditableDiv.id)

            // Find the associated hidden input based on the div's id
            var hiddenInputId = contentEditableDiv.id + "_hidden";
            var hiddenInput = document.getElementById(hiddenInputId);

            // Update the hidden input's value with the contenteditable div's HTML
            hiddenInput.value = contentEditableDiv.innerHTML;
      }

      attachEventListeners()
      function attachEventListeners() {
            // Add event listeners to all contenteditable divs with the 'custom_textarea' class
            document.querySelectorAll('.custom_textarea').forEach(function(textarea) {
                  textarea.addEventListener('input', updateHiddenField);
                  textarea.addEventListener('keyup', updateHiddenField);
                  textarea.addEventListener('keydown', updateHiddenField);
                  textarea.addEventListener('blur', updateHiddenField);
                  textarea.addEventListener('paste', updateHiddenField);
            });
      }

      function setDateRange(tab,firstDateId,lastDateId) {
            const today = new Date();
            let startDate, endDate;

            switch (tab) {
                  case 'daily':
                        // Daily case: Set both start and end dates to today
                        startDate = new Date(today);
                        endDate = new Date(today);
                        break;
                  case 'weekly':
                        // Get the start and end of this week (Sunday to Saturday)
                        const dayOfWeek = today.getDay();
                        const firstDayOfWeek = today.getDate() - (dayOfWeek === 0 ? 6 : dayOfWeek - 1); // Monday
                        const lastDayOfWeek = firstDayOfWeek + 6; // Sunday
                        startDate = new Date(today.setDate(firstDayOfWeek));
                        endDate = new Date(today.setDate(lastDayOfWeek));
                        break;
                  case 'monthly':
                        // Get the start and end of this month (1st to last day of the month)
                        startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                        endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0); // Last day of the month
                        break;
                  case 'quarterly':
                        // Get the start and end of this quarter (Jan-Mar, Apr-Jun, etc.)
                        const quarterStartMonth = Math.floor(today.getMonth() / 3) * 3;
                        startDate = new Date(today.getFullYear(), quarterStartMonth, 1); // First day of the quarter
                        endDate = new Date(today.getFullYear(), quarterStartMonth + 3, 0); // Last day of the quarter
                        break;
                  case 'annual':
                        // Get the start and end of this year (Jan 1st to Dec 31st)
                        startDate = new Date(today.getFullYear(), 0, 1); // January 1st
                        endDate = new Date(today.getFullYear(), 11, 31); // December 31st
                        break;
            }

            // Set the values for both start and end date fields
            document.getElementById(firstDateId).value = formatDate(startDate);
            document.getElementById(lastDateId).value = formatDate(endDate);
      }

      // Function to format the date as yyyy-mm-dd
      function formatDate(date) {
            const dd = String(date.getDate()).padStart(2, '0');
            const mm = String(date.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
            const yyyy = date.getFullYear();
            return `${yyyy}-${mm}-${dd}`;
      }

      function formatDateRange(date) {
            var options = { year: '2-digit', month: 'short', day: '2-digit' };
            var formattedDate = new Date(date).toLocaleDateString('en-GB', options);
            return formattedDate.replace(',', ''); // Removing any commas
      }

</script>
