{!! ToastMagic::scripts() !!}

<script>
    const toastMagic = new ToastMagic();
    @session('success')
    toastMagic.success("Success!", "{{ session('success') }}");
    @endsession
    @session('error')
    toastMagic.error("Error!", "{{ session('error') }}");
    @endsession
    @session('warning')
    toastMagic.warning("Warning!", "{{ session('warning') }}", true);
    @endsession
    @session('info')
    toastMagic.info("Info!", "{{ session('info') }}");
    @endsession
</script>

@stack('scripts')
