@props([
    'type' => 'submit',
    'class' => '',
    'disabled' => false,
    'loading' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'href' => null,
    'target' => null,
])

@if ($href)
    <a href="{{ $href }}" @if ($target) target="{{ $target }}" @endif
        class="{{ $class }}" @if ($disabled) aria-disabled="true" tabindex="-1" @endif
        {{ $attributes }}>
        @if ($icon && $iconPosition === 'left')
            <i class="{{ $icon }} me-2"></i>
        @endif
        <span class="btn-label">{{ $slot }}</span>
        @if ($icon && $iconPosition === 'right')
            <i class="{{ $icon }} ms-2"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" class="{{ $class }}" @if ($disabled) disabled @endif
        {{ $attributes }}>

        @if ($icon && $iconPosition === 'left')
            <i class="{{ $icon }} me-2"></i>
        @endif

        <span class="btn-label">{{ $slot }}</span>

        @if ($icon && $iconPosition === 'right')
            <i class="{{ $icon }} ms-2"></i>
        @endif

        <span class="btn-progress d-none">
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            <span class="visually-hidden">Loading...</span>
        </span>
    </button>
@endif

@once
    @push('styles')
        <style>
            .btn {
                position: relative;
                transition: all 0.3s ease;
            }

            .btn-progress {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .btn:disabled .btn-label,
            .btn.disabled .btn-label {
                opacity: 0.65;
            }

            .btn-progress .spinner-border {
                width: 1rem;
                height: 1rem;
            }

            .btn-sm .btn-progress .spinner-border {
                width: 0.875rem;
                height: 0.875rem;
            }

            .btn-lg .btn-progress .spinner-border {
                width: 1.125rem;
                height: 1.125rem;
            }

            /* Delete button specific styles */
            .delete {
                cursor: pointer;
            }

            .delete:hover {
                transform: translateY(-1px);
            }
        </style>
    @endpush
@endonce
