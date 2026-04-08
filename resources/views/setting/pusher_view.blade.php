<div class="card">
    <div class="card-header">
        Pusher Settings
    </div>
    <div class="card-body">
        <form class="row g-3 needs-validation" novalidate id="form" onsubmit="save(event)" enctype="multipart/form-data">
            @csrf
            <input type="hidden" class="form-control" id="hidden_id" name="hidden_id" value="{{ $settings && $settings->id ? Crypt::encrypt($settings->id) : '' }}">

            <div class="modal-body">
                <div class="row g-6">

                    <!-- API ID -->
                    <div class="col-md-6 form-group">
                        <label for="api_id" class="form-label">API ID<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="api_id" name="api_id" value="{{ $settings->api_id ?? '' }}" placeholder="{{ __('Enter API ID') }}" required>
                    </div>

                    <!-- Key -->
                    <div class="col-md-6 form-group">
                        <label for="key" class="form-label">Key<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="key" name="key" value="{{ $settings->key ?? '' }}" placeholder="{{ __('Enter Pusher Key') }}" required>
                    </div>

                    <!-- Secret -->
                    <div class="col-md-6 form-group">
                        <label for="secret" class="form-label">Secret<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="secret" name="secret" value="{{ $settings->secret ?? '' }}" placeholder="{{ __('Enter Pusher Secret') }}" required>
                    </div>

                    <!-- Cluster -->
                    <div class="col-md-6 form-group">
                        <label for="cluster" class="form-label">Cluster<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="cluster" name="cluster" value="{{ $settings->cluster ?? '' }}" placeholder="{{ __('Enter Pusher Cluster') }}" required>
                    </div>

                    <!-- Channel -->
                    <div class="col-md-6 form-group">
                        <label for="channel" class="form-label">Channel<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="channel" name="channel" value="{{ $settings->channel ?? '' }}" placeholder="{{ __('Enter Pusher Channel') }}" required>
                    </div>

                </div>
            </div>

            @can('create pusher setting')
            <div class="modal-footer">
                <button type="submit" id="submitBtn" class="btn btn-primary btn-sm m-1">{{ __('Save') }}</button>
            </div>
            @endcan
        </form>
    </div>
</div>
