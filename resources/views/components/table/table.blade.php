@props([
    'data' => null,
    'loop' => null,
])

<table class="table table-vcenter table-selectable">
    <thead>
        {{ $thead }}
    </thead>
    <tbody class="table-tbody">
        {{ $tbody }}
    </tbody>
</table>
