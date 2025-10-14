@props(['type' => 'status', 'message' => null])

@php
    // Automatically pull message from session if not passed
    $message = $message ?? session($type);
@endphp

@if($message)
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Ensure Notyf is not re-initialized multiple times
            if (!window.notyf) {
                window.notyf = new Notyf({
                    duration: 5000,
                    position: { x: 'center', y: 'top' },
                    dismissible: true,
                });
            }

            const type = "{{ $type }}";
            const message = @js($message);

            if (type === 'error') {
                notyf.error(message);
            } else {
                notyf.success(message);
            }
        });
    </script>
@endif
