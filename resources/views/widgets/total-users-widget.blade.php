    <div class="subheader">Total Users</div>
    <div class="d-flex align-items-baseline">
        <div class="h1 mb-0 me-2">75,782</div>
        <div class="me-auto">
            <span class="text-green d-inline-flex align-items-center lh-1">
                2%
                <i class="icon ms-1 icon-2 ti ti-trending-up"></i>
            </span>
        </div>
    </div>
    <div class="text-secondary mt-2">24,635 users increased from last month</div>
    <div id="chart-visitors" class="position-relative"></div>

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                window.ApexCharts &&
                    new ApexCharts(document.getElementById("chart-visitors"), {
                        chart: {
                            type: "line",
                            fontFamily: "inherit",
                            height: 96,
                            sparkline: {
                                enabled: true,
                            },
                            animations: {
                                enabled: false,
                            },
                        },
                        stroke: {
                            width: [2, 1],
                            dashArray: [0, 3],
                            lineCap: "round",
                            curve: "smooth",
                        },
                        series: [{
                                name: "Visitors",
                                data: [
                                    7687, 7543, 7545, 7543, 7635, 8140, 7810, 8315, 8379, 8441, 8485, 8227,
                                    8906, 8561, 8333, 8551, 9305, 9647, 9359, 9840, 9805, 8612, 8970,
                                    8097, 8070, 9829, 10545, 10754, 10270, 9282,
                                ],
                            },
                            {
                                name: "Visitors last month",
                                data: [
                                    8630, 9389, 8427, 9669, 8736, 8261, 8037, 8922, 9758, 8592, 8976, 9459,
                                    8125, 8528, 8027, 8256, 8670, 9384, 9813, 8425, 8162, 8024, 8897,
                                    9284, 8972, 8776, 8121, 9476, 8281, 9065,
                                ],
                            },
                        ],
                        tooltip: {
                            theme: "dark",
                        },
                        grid: {
                            strokeDashArray: 4,
                        },
                        xaxis: {
                            labels: {
                                padding: 0,
                            },
                            tooltip: {
                                enabled: false,
                            },
                            type: "datetime",
                        },
                        yaxis: {
                            labels: {
                                padding: 4,
                            },
                        },
                        labels: [
                            "2020-06-21",
                            "2020-06-22",
                            "2020-06-23",
                            "2020-06-24",
                            "2020-06-25",
                            "2020-06-26",
                            "2020-06-27",
                            "2020-06-28",
                            "2020-06-29",
                            "2020-06-30",
                            "2020-07-01",
                            "2020-07-02",
                            "2020-07-03",
                            "2020-07-04",
                            "2020-07-05",
                            "2020-07-06",
                            "2020-07-07",
                            "2020-07-08",
                            "2020-07-09",
                            "2020-07-10",
                            "2020-07-11",
                            "2020-07-12",
                            "2020-07-13",
                            "2020-07-14",
                            "2020-07-15",
                            "2020-07-16",
                            "2020-07-17",
                            "2020-07-18",
                            "2020-07-19",
                            "2020-07-20",
                        ],
                        colors: ["color-mix(in srgb, transparent, var(--tblr-primary) 100%)",
                            "color-mix(in srgb, transparent, var(--tblr-gray-400) 100%)"
                        ],
                        legend: {
                            show: false,
                        },
                    }).render();
            });
        </script>
    @endpush
