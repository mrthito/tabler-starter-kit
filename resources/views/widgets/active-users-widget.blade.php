<div class="subheader">Active Users</div>
<div class="d-flex align-items-baseline mb-2">
    <div class="h1 mb-0 me-2">25,782</div>
    <div class="me-auto">
        <span class="text-red d-inline-flex align-items-center lh-1">
            -1%
            <i class="icon ms-1 icon-2 ti ti-trending-down"></i>
        </span>
    </div>
</div>
<div id="chart-active-users-3" class="position-relative"></div>

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.ApexCharts &&
                new ApexCharts(document.getElementById("chart-active-users-3"), {
                    chart: {
                        type: "radialBar",
                        fontFamily: "inherit",
                        height: 192,
                        sparkline: {
                            enabled: true,
                        },
                        animations: {
                            enabled: false,
                        },
                    },
                    plotOptions: {
                        radialBar: {
                            startAngle: -120,
                            endAngle: 120,
                            hollow: {
                                margin: 16,
                                size: "50%",
                            },
                            dataLabels: {
                                show: true,
                                value: {
                                    offsetY: -8,
                                    fontSize: "24px",
                                },
                            },
                        },
                    },
                    series: [78],
                    labels: [""],
                    tooltip: {
                        theme: "dark",
                    },
                    grid: {
                        strokeDashArray: 4,
                    },
                    colors: ["color-mix(in srgb, transparent, var(--tblr-primary) 100%)"],
                    legend: {
                        show: false,
                    },
                }).render();
        });
    </script>
@endpush
