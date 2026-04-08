<div class="card">
    <div class="card-header">
        SMTP Settings
    </div>
    <div class="card-body">
        <form class="row g-3 needs-validation" novalidate id="form" onsubmit="save(event)" enctype="multipart/form-data">
            @csrf
            <input type="hidden" class="form-control" id="hidden_id" name="hidden_id" value="{{ $settings && $settings->id ? Crypt::encrypt($settings->id) : '' }}">

            <div class="modal-body">
                <div class="row g-6">

                    <!-- Sender Name -->
                    <div class="col-md-6 form-group">
                        <label for="sender_name" class="form-label">Sender Name<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="sender_name" name="sender_name" value="{{ $settings->sender_name ?? '' }}" placeholder="{{ __('Enter Sender Name') }}" required>
                    </div>

                    <!-- Sender Email -->
                    <div class="col-md-6 form-group">
                        <label for="sender_email" class="form-label">Sender Email<span style="color:red">*</span></label>
                        <input type="email" class="form-control" id="sender_email" name="sender_email" value="{{ $settings->sender_email ?? '' }}" placeholder="{{ __('Enter Sender Email') }}" required>
                    </div>

                    <!-- SMTP Driver -->
                    <div class="col-md-6 form-group">
                        <label for="smtp_driver" class="form-label">SMTP Driver<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="smtp_driver" name="smtp_driver" value="{{ $settings->smtp_driver ?? '' }}" placeholder="{{ __('Enter SMTP Driver') }}" required>
                    </div>

                    <!-- SMTP Host -->
                    <div class="col-md-6 form-group">
                        <label for="smtp_host" class="form-label">SMTP Host<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="smtp_host" name="smtp_host" value="{{ $settings->smtp_host ?? '' }}" placeholder="{{ __('Enter SMTP Host') }}" required>
                    </div>

                    <!-- SMTP Username -->
                    <div class="col-md-6 form-group">
                        <label for="smtp_username" class="form-label">SMTP Username<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="smtp_username" name="smtp_username" value="{{ $settings->smtp_username ?? '' }}" placeholder="{{ __('Enter SMTP Username') }}" required>
                    </div>

                    <!-- SMTP Password -->
                    <div class="col-md-6 form-group">
                        <label for="smtp_password" class="form-label">SMTP Password<span style="color:red">*</span></label>
                        <input type="password" class="form-control" id="smtp_password" name="smtp_password" value="{{ $settings->smtp_password ?? '' }}" placeholder="{{ __('Enter SMTP Password') }}" required>
                    </div>

                    <!-- SMTP Encryption -->
                    <div class="col-md-6 form-group">
                        <label for="smtp_encryption" class="form-label">SMTP Encryption<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="smtp_encryption" name="smtp_encryption" value="{{ $settings->smtp_encryption ?? '' }}" placeholder="{{ __('Enter SMTP Encryption') }}" required>
                    </div>

                    <!-- SMTP Port -->
                    <div class="col-md-6 form-group">
                        <label for="smtp_port" class="form-label">SMTP Port<span style="color:red">*</span></label>
                        <input type="number" class="form-control" id="smtp_port" name="smtp_port" value="{{ $settings->smtp_port ?? '' }}" placeholder="{{ __('Enter SMTP Port') }}" required>
                    </div>

                </div>
            </div>

            @can('create smtp setting')
            <div class="modal-footer">
                <button type="submit" id="submitBtn" class="btn btn-primary btn-sm m-1">{{ __('Save') }}</button>
            </div>
            @endcan
        </form>
    </div>
</div>
