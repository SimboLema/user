<div class="card">
      <div class="card-header">
</div>
      <div class="card-body">
            <form class="row g-3 needs-validation" novalidate id="form" onsubmit="save(event)" enctype="multipart/form-data">
            @csrf
            <input type="hidden" class="form-control" id="hidden_id" name="hidden_id" value="{{ $settings && $settings->id ? Crypt::encrypt($settings->id) : '' }}">

            <div class="modal-body">
                  <div class="row">

                        <!-- System Name -->
                        <div class="col-md-6 form-group">
                        <label for="system_name" class="form-label">System Name<span style="color:red">*</span></label>
                        <input type="text" class="form-control" id="system_name" name="system_name" value="{{ $settings->system_name ?? '' }}" placeholder="{{ __('Enter System Name') }}" required>
                        </div>




                        <!-- Currency -->
                        <div class="col-md-6 form-group">
                        <label for="currency" class="form-label">Currency<span style="color:red">*</span></label>
                        <select class="form-control select2" id="currency" name="currency" required>
                              <option value="">Select Currency</option>
                              <option value="USD" {{$settings && $settings->currency == 'USD' ? 'selected' : '' }}>USD</option>
                              <option value="EUR" {{ $settings && $settings->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                              <option value="TZS" {{$settings &&  $settings->currency == 'TZS' ? 'selected' : '' }}>TZS</option>
                        </select>
                        </div>

                        <div class="col-md-6 form-control-validation">
                            <label for="unit" class="form-label">{{ __('Base Unit (Default)') }}<span style="color:red">*</span></label>
                            <select class="form-control select3" id="unit_id" name="unit_id" required >
                                @foreach($units as $unit)
                                    <option value="{{$unit->id}}" >{{$unit->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Email Notifications -->
                        <div class="col-md-6 p-3">
                            <label class="form-label">{{ __('Enable Email Notifications') }}</label>
                            <div>
                                <label>
                                        <input type="radio" name="email_notifications" value="1" {{ $settings && $settings->email_notifications == 1 ? 'checked' : '' }}> Yes &nbsp;&nbsp;&nbsp;
                                </label>
                                <label>
                                        <input type="radio" name="email_notifications" value="0" {{ $settings && $settings->email_notifications == 0 ? 'checked' : '' }}> No
                                </label>
                            </div>
                            </div>
                        <br>

                        <!-- SMS Notifications -->
                        <div class="col-md-6 p-3">
                            <label class="form-label">{{ __('Enable SMS Notifications') }}</label>
                            <div>
                                <label>
                                        <input type="radio" name="sms_notifications" value="1" {{ $settings && $settings->sms_notifications == 1 ? 'checked' : '' }}> Yes &nbsp;&nbsp;&nbsp;
                                </label>
                                <label>
                                        <input type="radio" name="sms_notifications" value="0" {{ $settings && $settings->sms_notifications == 0 ? 'checked' : '' }}> No
                                </label>
                            </div>
                        </div>
                        <br>

                        <!-- Two Factor Authentication -->
                        <div class="col-md-6 p-3">
                              <label class="form-label">{{ __('Enable Two Factor Authentication') }}</label>
                              <div>
                                          <input type="radio" name="two_factor_auth" value="1" {{ $settings && $settings->two_factor_auth == 1 ? 'checked' : '' }}> Yes &nbsp;&nbsp;&nbsp;
                                          <input type="radio" name="two_factor_auth" value="0" {{ $settings && $settings->two_factor_auth == 0 ? 'checked' : '' }}> No

                              </div>
                        </div>
                        <br>


                  </div>


                  <div class="row">
                        <!-- System Logo -->
                        <div class="col-md-6 form-group">
                              <label for="system_favicon" class="form-label">{{ __('Favicon') }}</label>
                              <input type="file" class="form-control" id="system_favicon" name="system_favicon" accept="image/*" onchange="previewFavicon()">
                        </div>

                        <div class="col-md-6 form-group">
                              <label for="system_logo" class="form-label">{{ __('System Logo') }}</label>
                              <input type="file" class="form-control" id="system_logo" name="system_logo" accept="image/*" onchange="previewLogo()">
                        </div>

                              <!-- Preview Image -->
                        <!-- Preview Image -->
                        <div class="col-md-6 form-group">
                              <label for="system_favicon" class="form-label">{{ __('Favicon Preview') }}</label>
                              <img id="faviconPreview" src="{{ $settings && $settings->system_favicon ? asset('storage/'.$settings->system_favicon) : 'https://via.placeholder.com/100' }}"  style="width: 100px; height: 100px; object-fit: cover;">
                        </div>

                        <div class="col-md-6 form-group">
                              <label for="system_logo" class="form-label">{{ __('Logo Preview') }}</label>
                              <img id="logoPreview" src="{{ $settings && $settings->system_logo ? asset('storage/'.$settings->system_logo) : 'https://via.placeholder.com/100' }}"  style="width: 100px; height: 100px; object-fit: cover;">
                        </div>

                  </div>

                  @can('manage database backup')
                  <div class="row">
                        <div class="col-md-6 ">
                              <label class="form-label">Backup Database </label><br>
                              <a href="/setting/downloadDatabaseBackup" target="_blank" class="btn btn-primary">
                                    <i class="bi bi-cloud-arrow-down"></i> Download
                              </a>

                        </div>
                  </div>
                  @endcan
            </div>

            <div class="modal-footer">
                  <button type="submit" id="submitBtn" class="btn btn-primary  m-1">{{ __('Save') }}</button>
            </div>
            </form>
      </div>
</div>

<script>
      function previewLogo() {
            const file = document.getElementById('system_logo').files[0];
            const preview = document.getElementById('logoPreview');

            if (file) {
                  const reader = new FileReader();
                  reader.onload = function (e) {
                  preview.src = e.target.result;
                  };
                  reader.readAsDataURL(file);
            }
      }

      function previewFavicon() {
            const file = document.getElementById('system_favicon').files[0];
            const preview = document.getElementById('faviconPreview');

            if (file) {
                  const reader = new FileReader();
                  reader.onload = function (e) {
                  preview.src = e.target.result;
                  };
                  reader.readAsDataURL(file);
            }
      }
</script>
