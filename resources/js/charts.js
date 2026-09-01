import {
    BarController,
    BarElement,
    CategoryScale,
    Chart,
    Filler,
    LinearScale,
    LineController,
    LineElement,
    PointElement,
    Tooltip,
} from 'chart.js';

Chart.register(
    BarController,
    BarElement,
    CategoryScale,
    Filler,
    LinearScale,
    LineController,
    LineElement,
    PointElement,
    Tooltip,
);

const dashboardPalette = [
    'rgb(59, 130, 246)',
    'rgb(20, 184, 166)',
    'rgb(34, 197, 94)',
    'rgb(168, 85, 247)',
    'rgb(249, 115, 22)',
    'rgb(14, 165, 233)',
    'rgb(236, 72, 153)',
];

const chartColors = {
    accent: dashboardPalette[0],
    grid: 'rgb(228, 228, 231)',
    text: 'rgb(113, 113, 122)',
};

const sansFont = '"SomarSans", ui-sans-serif, system-ui, sans-serif';

Chart.defaults.font.family = sansFont;
Chart.defaults.plugins.tooltip.titleFont = { family: sansFont };
Chart.defaults.plugins.tooltip.bodyFont = { family: sansFont };

const baseOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            rtl: true,
            textDirection: 'rtl',
            backgroundColor: 'rgb(24, 24, 27)',
            padding: 12,
            cornerRadius: 8,
            titleFont: {
                family: sansFont,
            },
            bodyFont: {
                family: sansFont,
            },
        },
    },
};

function axisTicks(overrides = {}) {
    return {
        color: chartColors.text,
        font: {
            family: sansFont,
        },
        ...overrides,
    };
}

function createLineGradient(context) {
    const { chart } = context;
    const { ctx, chartArea } = chart;

    if (!chartArea) {
        return 'rgba(59, 130, 246, 0.1)';
    }

    const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.25)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.02)');

    return gradient;
}

function resolveBarColors(colors, dataLength) {
    if (Array.isArray(colors) && colors.length > 0) {
        return colors;
    }

    return Array.from({ length: dataLength }, (_, index) => dashboardPalette[index % dashboardPalette.length]);
}

function buildLineOptions() {
    return {
        ...baseOptions,
        scales: {
            x: {
                grid: {
                    display: false,
                },
                ticks: axisTicks({
                    maxTicksLimit: 8,
                }),
            },
            y: {
                beginAtZero: true,
                ticks: axisTicks({
                    precision: 0,
                }),
                grid: {
                    color: chartColors.grid,
                },
            },
        },
    };
}

function buildVerticalBarOptions(isRtl) {
    return {
        ...baseOptions,
        scales: {
            x: {
                reverse: isRtl,
                grid: {
                    display: false,
                },
                ticks: axisTicks({
                    textDirection: 'rtl',
                    maxRotation: 45,
                    autoSkip: true,
                }),
            },
            y: {
                beginAtZero: true,
                position: isRtl ? 'right' : 'left',
                ticks: axisTicks({
                    precision: 0,
                }),
                grid: {
                    color: chartColors.grid,
                },
            },
        },
    };
}

function buildHorizontalBarOptions() {
    return {
        ...baseOptions,
        indexAxis: 'y',
        scales: {
            x: {
                beginAtZero: true,
                ticks: axisTicks({
                    precision: 0,
                }),
                grid: {
                    color: chartColors.grid,
                },
            },
            y: {
                grid: {
                    display: false,
                },
                ticks: axisTicks(),
            },
        },
    };
}

function destroyChartOnCanvas(canvas) {
    if (!canvas) {
        return;
    }

    const existingChart = Chart.getChart(canvas);

    if (existingChart) {
        existingChart.destroy();
    }
}

function destroyAllDashboardCharts() {
    document.querySelectorAll('[data-chart-config] canvas').forEach((canvas) => {
        destroyChartOnCanvas(canvas);
    });
}

document.addEventListener('livewire:navigating', destroyAllDashboardCharts);

export function dashboardChart() {
    return {
        chart: null,
        resizeObserver: null,

        init() {
            this.scheduleRender();
        },

        scheduleRender() {
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    this.waitForVisibleCanvas(() => this.renderChart());
                });
            });
        },

        waitForVisibleCanvas(callback, attempts = 0) {
            const canvas = this.$refs.canvas;

            if (!canvas?.isConnected) {
                if (attempts < 60) {
                    requestAnimationFrame(() => this.waitForVisibleCanvas(callback, attempts + 1));
                }

                return;
            }

            const { width, height } = canvas.getBoundingClientRect();

            if (width === 0 || height === 0) {
                if (attempts < 60) {
                    requestAnimationFrame(() => this.waitForVisibleCanvas(callback, attempts + 1));
                }

                return;
            }

            callback();
        },

        renderChart() {
            const config = this.$el.dataset.chartConfig;

            if (!config) {
                return;
            }

            const canvas = this.$refs.canvas;

            if (!canvas?.isConnected) {
                return;
            }

            const context = canvas.getContext('2d');

            if (!context) {
                return;
            }

            const { type, labels, data, label, colors, orientation, rtl } = JSON.parse(config);

            destroyChartOnCanvas(canvas);
            this.chart = null;

            if (this.resizeObserver) {
                this.resizeObserver.disconnect();
                this.resizeObserver = null;
            }

            if (type === 'line') {
                this.chart = new Chart(context, {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [
                            {
                                label,
                                data,
                                borderColor: chartColors.accent,
                                backgroundColor: (ctx) => createLineGradient(ctx),
                                borderWidth: 2,
                                fill: true,
                                tension: 0.3,
                                pointRadius: 3,
                                pointHoverRadius: 5,
                                pointBackgroundColor: chartColors.accent,
                            },
                        ],
                    },
                    options: buildLineOptions(),
                });

                this.observeResize();

                return;
            }

            const isVertical = orientation === 'vertical';

            this.chart = new Chart(context, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        {
                            label,
                            data,
                            backgroundColor: resolveBarColors(colors, data.length),
                            borderRadius: 4,
                            borderSkipped: false,
                        },
                    ],
                },
                options: isVertical
                    ? buildVerticalBarOptions(rtl === true)
                    : buildHorizontalBarOptions(),
            });

            this.observeResize();
        },

        observeResize() {
            this.resizeObserver = new ResizeObserver(() => {
                if (this.chart) {
                    this.chart.resize();
                }
            });

            this.resizeObserver.observe(this.$el);
        },

        destroy() {
            if (this.resizeObserver) {
                this.resizeObserver.disconnect();
                this.resizeObserver = null;
            }

            if (this.chart) {
                this.chart.destroy();
                this.chart = null;
            }
        },
    };
}
