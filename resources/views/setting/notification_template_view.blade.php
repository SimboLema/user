<div class="card">
    <div class="card-header">
        Notification Client
    </div>
    <div class="card-body">
        <form class="row g-3 needs-validation" novalidate id="form" onsubmit="save(event)" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <!-- Dynamic Nav Tabs -->
                    <ul class="nav nav-tabs" id="notificationTabs" role="tablist">
                        @foreach($notificationTemplates as $index => $template)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $index == 0 ? 'active' : '' }}" id="{{ $template->slug }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $template->slug }}" type="button" role="tab" aria-controls="{{ $template->slug }}" aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                                    {{ $template->name }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Dynamic Tab Content -->
                    <div class="tab-content p-3 border">
                        @foreach($notificationTemplates as $index => $template)
                            <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="{{ $template->slug }}" role="tabpanel" aria-labelledby="{{ $template->slug }}-tab">
                                <strong class="form-label">Available Tags:</strong>
                                <p>{{ implode(',', $template->variables) }}</p>

                                <!-- Active / Inactive Radio Buttons -->
                                <div class="mb-3">
                                    <strong class="form-label">Notification Status:</strong><br>
                                    <input type="radio" id="active_{{ $template->slug }}" name="is_active_{{ $template->slug }}" value="1" {{ $template->is_active ? 'checked' : '' }}>
                                    <label for="active_{{ $template->slug }}">Active</label><br>
                                    <input type="radio" id="inactive_{{ $template->slug }}" name="is_active_{{ $template->slug }}" value="0" {{ !$template->is_active ? 'checked' : '' }}>
                                    <label for="inactive_{{ $template->slug }}">Inactive</label>
                                </div>

                                <!-- Multi-select User Dropdown -->
                                <div class="mb-3">
                                    <label for="users_{{ $template->slug }}" class="form-label">Select Users to Notify</label><br>

                                    @foreach($template->users as $temp_user)
                                      <span class="bg-success badge">{{$temp_user->user ? ($temp_user->user->name ?? "") : ""}}</span>
                                    @endforeach
                                    <select class="form-control select3" id="users_{{ $template->slug }}" name="users_{{ $template->slug }}[]" multiple>
                                        @foreach($users as $user)
                                        <?php
                                            $isSelected = $template->users->contains('user_id', $user->id);
                                        ?>
                                        <option value="{{ $user->id }}" {{ $isSelected ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <strong for="smsBody_{{ $template->slug }}" class="form-label">SMS Body</strong>
                                    <textarea class="form-control" id="smsBody_{{ $template->slug }}" name="sms_body_{{ $template->slug }}" rows="4" placeholder="SMS body">{{ $template->content }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @can('create email template')
            <div class="modal-footer">
                <button type="submit" id="submitBtn" class="btn btn-primary btn-sm m-1">Submit</button>
            </div>
            @endcan
        </form>
    </div>
</div>
<script>
    $(document).ready(function(){
        initializeNormalSelector()
    })
</script>
