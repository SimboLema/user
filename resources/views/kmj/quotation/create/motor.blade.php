<div class="step-content d-none">
    <h5 class="step-title"><i class="bi bi-box-seam me-2"></i>Motor
        Information</h5>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Motor Category</label>
            <select class="form-select" name="motor_category_id"
                    id="motor_category_id">
                <option value="">Select Motor Category</option>
                @foreach ($motorCategories as $motorCategory)
                    <option value="{{ $motorCategory->id }}">
                        {{ $motorCategory->name }}</option>
                @endforeach
            </select>
        </div>


        <div class="col-md-6">
            <label class="form-label">Motor Type</label>
            <select class="form-select" name="motor_type_id"
                    id="motor_type_id">
                <option value="">Select Motor Type</option>
                @foreach ($motorTypes as $motorType)
                    <option
                        value="{{ $motorType->id }}">{{ $motorType->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <div class="col-md-6">
            <label class="form-label">Registration Number</label>
            <input type="text" class="form-control"
                   name="registration_number">
        </div>

        <div class="col-md-6">
            <label class="form-label">Chassis Number</label>
            <input type="text" class="form-control" name="chassis_number">
        </div>

        <div class="col-md-6">
            <label class="form-label">Make</label>
            <input type="text" class="form-control" name="make">
        </div>

        <div class="col-md-6">
            <label class="form-label">Model</label>
            <input type="text" class="form-control" name="model">
        </div>

        <div class="col-md-6">
            <label class="form-label">Model Number</label>
            <input type="text" class="form-control" name="model_number">
        </div>

        <div class="col-md-6">
            <label class="form-label">Body Type</label>
            <input type="text" class="form-control" name="body_type">
        </div>


        <div class="col-md-6">
            <label class="form-label">Color</label>
            <input class="form-control" type="text" name="color"
                   id="color">
        </div>

        <div class="col-md-6">
            <label class="form-label">Engine Number</label>
            <input class="form-control" type="text" name="engine_number"
                   id="engine_number">
        </div>

        <div class="col-md-6">
            <label class="form-label">Engine Capacity</label>
            <input class="form-control" type="text" name="engine_capacity"
                   id="engine_capacity">
        </div>

        <div class="col-md-6">
            <label class="form-label">Fuel Used</label>
            <input class="form-control" type="text" name="fuel_used"
                   id="fuel_used">
        </div>

        <div class="col-md-6">
            <label class="form-label">Number of Axles</label>
            <input class="form-control" type="number" name="number_of_axles"
                   id="number_of_axles">
        </div>
        <div class="col-md-6">
            <label class="form-label">Axle Distance</label>
            <input class="form-control" type="text" name="axle_distance"
                   id="axle_distance">
        </div>
        <div class="col-md-6">
            <label class="form-label">Sitting Capacity</label>
            <input class="form-control" type="number"
                   name="sitting_capacity"
                   id="sitting_capacity">
        </div>
        <div class="col-md-6">
            <label class="form-label">Year of Manufacture</label>
            <input class="form-control" type="number"
                   name="year_of_manufacture"
                   id="year_of_manufacture">
        </div>
        <div class="col-md-6">
            <label class="form-label">Tare Weight</label>
            <input class="form-control" type="number" name="tare_weight"
                   id="tare_weight">
        </div>
        <div class="col-md-6">
            <label class="form-label">Gross Weight</label>
            <input class="form-control" type="number" name="gross_weight"
                   id="gross_weight">
        </div>

        <div class="col-md-6">
            <label class="form-label">Motor Usage</label>
            <select class="form-select" name="motor_usage_id">
                <option>Select Motor Usage</option>
                @foreach ($motorUsages as $motorUsage)
                    <option
                        value="{{ $motorUsage->id }}">{{ $motorUsage->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Owner Category</label>
            <select class="form-select" name="owner_category_id">
                <option>Select Owner Category</option>
                @foreach ($ownerCategories as $ownerCategory)
                    <option value="{{ $ownerCategory->id }}">
                        {{ $ownerCategory->name }}</option>
                @endforeach
            </select>
        </div>

    </div>

    <div class="d-flex justify-content-between mt-4">
        <button type="button" class="btn text-white" style="background-color: #9aa89b"
                onclick="changeStep(-1)">
            <i class="bi bi-arrow-left me-2 text-white"></i> Back
        </button>
        <button type="button" class="btn btn text-white" style="background-color: #003153"
                onclick="changeStep(1)">
            Next <i class="bi bi-arrow-right ms-2 text-white"></i>
        </button>
    </div>
</div>
