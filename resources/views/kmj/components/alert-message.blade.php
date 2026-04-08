{{-- @if (session('success') || session('error'))
    @php
        $type = session('success') ? 'success' : 'danger';
        $message = session('success') ?? session('error');
    @endphp

    <div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert" id="alert-message">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
    </div>

    <script>
        // Funga alert baada ya sekunde 4
        setTimeout(() => {
            let alertBox = document.getElementById('alert-message');
            if (alertBox) {
                let fade = new bootstrap.Alert(alertBox);
                fade.close();
            }
        }, 4000);
    </script>
@endif --}}

@php
    // Orodha ya aina zote za ujumbe tunazotaka kuonyesha
    $flash_types = ['success', 'error', 'warning', 'info'];
    $displayed_message = false;
@endphp

{{-- Angalia kama kuna ujumbe wowote kwenye session --}}
@foreach ($flash_types as $type)
    @if (session($type))
        @php
            $alert_type = $type === 'error' ? 'danger' : $type;
            $message = session($type);
            $displayed_message = true;
        @endphp

        <div class="alert alert-{{ $alert_type }} alert-dismissible fade show" role="alert"
            id="alert-message-{{ $type }}">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endforeach

{{-- Script ya kufunga alert baada ya sekunde 4, itatumika ikiwa ujumbe wowote umeonyeshwa --}}
@if ($displayed_message)
    <script>
        // Funga alert baada ya sekunde 4
        setTimeout(() => {
            @foreach ($flash_types as $type)
                @if (session($type))
                    let alertBox = document.getElementById('alert-message-{{ $type }}');
                    if (alertBox) {
                        // Hakikisha unatumia variable tofauti kwa kila alert ili kufunga zote kwa ufasaha
                        let fade = new bootstrap.Alert(alertBox);
                        fade.close();
                    }
                @endif
            @endforeach
        }, 5000);
    </script>
@endif
