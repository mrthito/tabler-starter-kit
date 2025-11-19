<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ $title }}</title>
    @include('admin.layouts.partials.header')
</head>

<body>
    <style>
        body {
            background: #f5f7fa;
        }

        .status-up {
            color: #28a745;
            font-weight: 700;
        }

        .status-down {
            color: #dc3545;
            font-weight: 700;
        }

        .card {
            border-radius: 18px;
        }

        .status-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }

        .dot-up {
            background: #28a745;
        }

        .dot-down {
            background: #dc3545;
        }
    </style>

    <div class="container py-5">
        <div class="min-vh-100 d-flex flex-column text-white font-sans">
            <main class="flex-grow-1 py-5">
                <div class="container" style="max-width: 800px;">

                    <div
                        class="card {{ $allOperational ? 'bg-success' : 'bg-danger' }} text-white border-0 mb-5 shadow-lg overflow-hidden">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <h1 class="h3 fw-bold mb-1">
                                    {{ $allOperational ? 'All Systems Operational' : 'Some Systems Experiencing Issues' }}
                                </h1>
                                <p class="mb-0 opacity-75">Last updated: {{ $lastUpdatedText ?? 'Never' }}</p>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                @if ($allOperational)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                        fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                        <path
                                            d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z" />
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                        fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h2 class="h5 fw-bold mb-4 border-bottom border-secondary border-opacity-25 pb-2">Uptime</h2>

                        <div class="d-flex flex-column gap-4">
                            @foreach ($services as $service)
                                <div class="card bg-transparent border border-secondary border-opacity-25">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h3 class="h6 fw-bold mb-0">{{ $service['name'] }}</h3>
                                            <span
                                                class="badge rounded-pill px-3 py-2 {{ $service['status'] === 'operational'
                                                    ? 'text-success bg-success bg-opacity-10 border border-success border-opacity-25'
                                                    : ($service['status'] === 'degraded'
                                                        ? 'text-warning bg-warning bg-opacity-10 border border-warning border-opacity-25'
                                                        : 'text-danger bg-danger bg-opacity-10 border border-danger border-opacity-25') }}">
                                                {{ $service['status_label'] ?? ($service['status'] === 'operational' ? 'Operational' : ($service['status'] === 'degraded' ? 'Degraded Performance' : 'Major Outage')) }}
                                            </span>
                                        </div>

                                        <div class="d-flex gap-1 justify-content-between" style="height: 32px;">
                                            @foreach ($service['history'] as $day)
                                                <div class="flex-grow-1 rounded-1 opacity-75 {{ $day['class'] }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ $day['label'] }} - {{ $day['date'] }}"
                                                    style="min-width: 4px;"></div>
                                            @endforeach
                                        </div>

                                        <div class="d-flex justify-content-between mt-2 text-secondary small">
                                            <span>60 days ago</span>
                                            <span
                                                class="bg-dark px-2 text-white opacity-75 rounded-pill">{{ $service['uptime'] ?? 100 }}%
                                                uptime</span>
                                            <span>Today</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h2 class="h5 fw-bold mb-4 border-bottom border-secondary border-opacity-25 pb-2">
                            Past Incidents
                        </h2>

                        @if (empty($incidents))
                            <div class="text-center py-4 text-secondary">
                                <p class="mb-0">No incidents reported in the past 100 days.</p>
                            </div>
                        @else
                            <div class="d-flex flex-column gap-4">
                                @foreach ($incidents as $incident)
                                    @php
                                        $borderColor =
                                            $incident['severity'] === 'down' ? 'border-danger' : 'border-warning';
                                        $statusColor =
                                            $incident['status'] === 'resolved'
                                                ? 'text-success'
                                                : ($incident['status'] === 'monitoring'
                                                    ? 'text-warning'
                                                    : 'text-secondary');
                                        $statusText =
                                            $incident['status'] === 'resolved'
                                                ? 'Resolved'
                                                : ($incident['status'] === 'monitoring'
                                                    ? 'Monitoring'
                                                    : 'Investigating');

                                        $dateText = $incident['start_date']->format('M d, Y');
                                        if (
                                            $incident['start_date']->format('Y-m-d') !==
                                            $incident['end_date']->format('Y-m-d')
                                        ) {
                                            $dateText .= ' - ' . $incident['end_date']->format('M d, Y');
                                        }
                                    @endphp
                                    <div class="ps-4 border-start {{ $borderColor }} text-dark">
                                        <div class="text-secondary small mb-1">{{ $dateText }}</div>
                                        <h4 class="h6 fw-bold text-dark">{{ $incident['title'] }}</h4>
                                        <p class="text-secondary small mb-2">
                                            {{ $incident['description'] }}
                                            @if (!empty($incident['error_message']))
                                                <br><span class="text-muted">Error:
                                                    {{ $incident['error_message'] }}</span>
                                            @endif
                                        </p>
                                        <div class="small {{ $statusColor }}">
                                            {{ $statusText }} - This incident has been
                                            {{ $incident['status'] === 'resolved' ? 'resolved' : 'addressed' }}.
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>
            </main>
        </div>
    </div>
</body>

</html>
