<div class="card">
    <div class="card-header">
        Beam SMS Gateway Settings
    </div>
    <div class="card-body">
        <form class="row g-3 needs-validation" novalidate id="form" onsubmit="save(event)" enctype="multipart/form-data">
            @csrf
            <input type="hidden" class="form-control" id="hidden_id" name="hidden_id" value="{{ $settings && $settings->id ? Crypt::encrypt($settings->id) : '' }}">

            <div class="modal-body">
                <div class="row g-6">

                    <!-- API Key -->
                    <div class="col-md-6">
                        <label for="api_key" class="form-label">API Key<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="api_key" name="api_key" value="{{ $settings->api_key ?? '' }}" placeholder="{{ __('Enter API Key') }}" required>
                    </div>

                    <!-- API Secret -->
                    <div class="col-md-6">
                        <label for="secret_key" class="form-label">API Secret<span style="color:red">*</span></label>
                        <input type="password" class="form-control" id="secret_key" name="secret_key" value="{{ $settings->secret_key ?? '' }}" placeholder="{{ __('Enter API Secret') }}" required>
                    </div>

                    <!-- Base URL -->
                    <div class="col-md-6">
                        <label for="base_url" class="form-label">Base URL<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="base_url" name="base_url" value="{{ $settings->base_url ?? '' }}" placeholder="{{ __('Enter Base URL') }}" required>
                    </div>

                    <!-- Source Address -->
                    <div class="col-md-6 ">
                        <label for="source_address" class="form-label">Source Address<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="source_address" name="source_address" value="{{ $settings->source_address ?? '' }}" placeholder="{{ __('Enter Source Address') }}" required>
                    </div>

                </div>
            </div>

            @can('create sms setting')
            <div class="modal-footer">
                <button type="submit" id="submitBtn" class="btn btn-primary btn-sm m-1">{{ __('Save') }}</button>
            </div>
            @endcan
        </form>
    </div>
</div>
