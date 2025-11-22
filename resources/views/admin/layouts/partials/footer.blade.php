{!! ToastMagic::scripts() !!}

<script>
    function togglePassword(selector, button, iconClass = 'icon icon-1') {
        const passwordInput = document.querySelectorAll(selector);
        passwordInput.forEach(input => {
            input.type = input.type === 'password' ? 'text' : 'password';
            const icon = input.type === 'password' ? 'ti ti-eye' : 'ti ti-eye-off';
            const iTag = `<i class="${icon} ${iconClass}"></i>`;
            button.innerHTML = iTag;
        });
    }

    document.querySelectorAll('[data-per-page]').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const perPage = this.getAttribute('data-per-page');
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            window.location.href = url.toString();
        });
    });

    // Function to update delete button visibility
    function updateDeleteButtonVisibility() {
        const checkboxes = document.querySelectorAll('.table-selectable-check');
        const deleteSelectedBtn = document.querySelector('.delete-selected-btn');
        const selectedRows = document.querySelector('#selected-rows');

        if (!deleteSelectedBtn || !selectedRows) return; // Guard clause if button or input doesn't exist

        // Get only CHECKED checkboxes
        const checkedBoxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);

        if (checkedBoxes.length > 0) {
            deleteSelectedBtn.classList.remove('d-none');
            selectedRows.value = checkedBoxes.map(checkbox => checkbox.value).join(',');
        } else {
            deleteSelectedBtn.classList.add('d-none');
            selectedRows.value = '';
        }
    }

    // Add event listeners to all checkboxes
    document.querySelectorAll('.table-selectable-check').forEach(item => {
        item.addEventListener('change', updateDeleteButtonVisibility);
    });

    // Initial visibility check
    updateDeleteButtonVisibility();
</script>

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
