<x-app-layout :page="__('Dashboard')" layout="admin">

    <x-slot name="pretitle">{{ __('Dashboard') }}</x-slot>
    <x-slot name="subtitle">{{ __('Manage your dashboard here') }}</x-slot>

    <div class="row row-deck row-cards">
        @foreach ($widgets as $widget)
            {{ (new $widget)->render() }}
        @endforeach
    </div>

    @push('scripts')
        <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}" defer></script>
        <script src="{{ asset('assets/libs/jsvectormap/dist/jsvectormap.min.js') }}" defer></script>
        <script src="{{ asset('assets/libs/jsvectormap/dist/maps/world.js') }}" defer></script>
        <script src="{{ asset('assets/libs/jsvectormap/dist/maps/world-merc.js') }}" defer></script>
    @endpush
</x-app-layout>
