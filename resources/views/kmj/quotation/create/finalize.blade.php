<div class="step-content d-none">
    <h5 class="step-title"><i class="bi bi-cloud-arrow-up me-2"></i>Finalize
        Quotation
    </h5>
    <div class="row g-3">
        <div class="col-12 mb-4">
            <label for="fileUpload" class="form-label fw-semibold mb-2">Supporting
                Documents</label>
            <div class="file-upload-card border rounded-3 p-4 position-relative" id="dropZone">
                <input type="file" class="file-upload-input d-none" id="fileUpload" accept=".pdf,.jpg,.jpeg,.png"
                    name="uploads[]" multiple>
                <label for="fileUpload"
                    class="file-upload-label d-flex flex-column align-items-center justify-content-center text-center cursor-pointer py-4">
                    <div class="file-upload-icon bg-light rounded-circle p-3 mb-3">
                        <i class="bi bi-cloud-arrow-up fs-4 text-primary" id="uploadIcon"></i>
                    </div>
                    <h6 class="mb-1" id="mainText">Drag and drop files here</h6>
                    <p class="text-muted mb-2" id="secondaryText">or click to
                        browse</p>
                    <small class="text-muted">Supports: PDF, JPG, PNG (Max. 5MB
                        each)</small>
                </label>
                <div class="file-previews mt-3" id="filePreviews"></div>
            </div>
        </div>

        <div class="col-12">
            <label class="form-label">Additional Notes</label>
            <textarea class="form-control" rows="3" placeholder="Any special instructions or comments..." name="description"></textarea>
        </div>
        <div class="col-12">
            <div class="form-check custom-checkbox">
                <input type="hidden" name="confirmCheck" value="0">
                <input class="form-check-input" type="checkbox" name="confirmCheck" id="confirmCheck" value="1"
                    required>
                <label class="form-check-label" for="confirmCheck">
                    I confirm that all information provided is accurate and
                    complete.
                </label>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between mt-4">
        <button type="button" class="btn text-white" style="background-color: #9aa89b" onclick="changeStep(-1)">
            <i class="bi bi-arrow-left me-2 text-white"></i> Back
        </button>
        <div>
            {{--            <button type="button" class="btn me-2 text-white" style="background-color: green" --}}
            {{--                    onclick="previewQuotation()"> --}}
            {{--                <i class="bi bi-eye me-2 text-white"></i>Preview --}}
            {{--            </button> --}}
            <button type="submit" class="btn text-white" style="background-color: #003153" id="submitBtn">
                <i class="bi bi-check-circle me-2 text-white"></i>

                <span id="submitText">Submit Quotation</span>
                <span id="submitSpinner" class="spinner-border spinner-border-sm text-light ms-2"
                    style="display: none;"></span>
            </button>
        </div>
    </div>
</div>

<style>
    /* Custom checkbox style */
    .custom-checkbox .form-check-input {
        width: 20px;
        height: 20px;
        background-color: #fff;
        /* default background */
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        transition: all 0.2s;
    }

    /* Checked state */
    .custom-checkbox .form-check-input:checked {
        background-color: #003153;
        /* background ya checked */
        border-color: #003153;
    }

    /* Optional: check mark color (default white) */
    .custom-checkbox .form-check-input:checked::after {
        color: #fff;
    }

    /* Adjust the label spacing a bit */
    .custom-checkbox .form-check-label {
        margin-left: 8px;
    }
</style>

<style>
    /* Container ya previews */
    .file-previews {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    /* Kila file preview card */
    .file-preview-card {
        position: relative;
        width: 120px;
        height: 120px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        flex-direction: column;
        font-size: 12px;
        text-align: center;
        padding: 5px;
        transition: transform 0.2s;
    }

    .file-preview-card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Picha au icon ya file */
    .file-preview-card img,
    .file-preview-card i {
        max-width: 60px;
        max-height: 60px;
        margin-bottom: 5px;
    }

    /* Remove button */
    .file-preview-card button.remove-file {
        position: absolute;
        top: 3px;
        right: 3px;
        background: rgba(255, 0, 0, 0.8);
        border: none;
        color: white;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 12px;
        line-height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .file-preview-card span.file-name {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        width: 100%;
    }
</style>

<script>
    const fileInput = document.getElementById('fileUpload');
    const filePreviews = document.getElementById('filePreviews');

    let filesArray = [];

    fileInput.addEventListener('change', (e) => {
        for (let i = 0; i < fileInput.files.length; i++) {
            filesArray.push(fileInput.files[i]);
        }
        renderPreviews();
    });

    function renderPreviews() {
        filePreviews.innerHTML = '';

        filesArray.forEach((file, index) => {
            const fileDiv = document.createElement('div');
            fileDiv.classList.add('file-preview-card');

            // Remove button
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.classList.add('remove-file');
            removeBtn.innerHTML = '&times;';
            removeBtn.addEventListener('click', () => {
                filesArray.splice(index, 1);
                renderPreviews();
            });

            // File preview content
            let previewElement;
            if (file.type.startsWith('image/')) {
                previewElement = document.createElement('img');
                previewElement.src = URL.createObjectURL(file);
            } else {
                // PDF or others, show icon
                previewElement = document.createElement('i');
                previewElement.classList.add('bi', 'bi-file-earmark-text');
                previewElement.style.fontSize = '40px';
                previewElement.style.color = '#003153';
            }

            const fileName = document.createElement('span');
            fileName.classList.add('file-name');
            fileName.textContent = file.name;

            fileDiv.appendChild(removeBtn);
            fileDiv.appendChild(previewElement);
            fileDiv.appendChild(fileName);

            filePreviews.appendChild(fileDiv);
        });

        // Refresh input files
        const dataTransfer = new DataTransfer();
        filesArray.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
    }
</script>

<script>
    document.querySelector('form[action="{{ route('kmj.quotation.store') }}"]').addEventListener('submit', function(e) {

        e.preventDefault();
        console.log('✅ Submit event captured!');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const submitSpinner = document.getElementById('submitSpinner');
        const confirmCheck = document.getElementById('confirmCheck');
        const customerId = document.getElementById('customer_id') ? document.getElementById('customer_id')
            .value : null;

        if (!confirmCheck.checked) {
            alert('Please confirm that all information provided is accurate before continuing.');
            return false;
        }

        // Disable step1 required fields if customer exists
        if (customerId && parseInt(customerId) > 0) {
            const step1Fields = document.querySelectorAll('#step1 [required]');
            step1Fields.forEach(field => field.removeAttribute('required'));
        }

        // Prevent double submit
        if (this.dataset.submitted) {
            e.preventDefault();
            return false;
        }

        this.dataset.submitted = true;

        // Show spinner before actual submit
        submitBtn.disabled = true;
        submitText.textContent = 'Submitting...';
        submitSpinner.style.display = 'inline-block';

        // Allow spinner to render
        setTimeout(() => {
            this.submit(); // submit form after spinner is visible
        }, 50);

    });
</script>
