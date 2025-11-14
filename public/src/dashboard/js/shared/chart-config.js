/**
 * Shared Chart Configuration
 * Common chart configurations for all dashboards
 */

// Income Chart Configuration (12 months)
export const incomeChartConfig = {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Pendapatan',
            data: [], // Will be populated from API: {{monthly_income}}
            borderColor: '#009ef7',
            backgroundColor: 'rgba(0, 158, 247, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                mode: 'index',
                intersect: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
};

// KPI Summary Chart Configuration
export const kpiChartConfig = {
    type: 'doughnut',
    data: {
        labels: ['Aktif', 'Pending', 'Kadaluarsa'],
        datasets: [{
            data: [], // Will be populated: [{{active_count}}, {{pending_count}}, {{expired_count}}]
            backgroundColor: ['#50cd89', '#ffc700', '#f1416c'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                display: false
            }
        }
    }
};

// Session Chart Configuration
export const sessionChartConfig = {
    type: 'bar',
    data: {
        labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
        datasets: [{
            label: 'Sesi',
            data: [], // Will be populated: {{weekly_sessions}}
            backgroundColor: '#7239ea',
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
};

// Progress Chart Configuration
export const progressChartConfig = {
    type: 'line',
    data: {
        labels: [], // Will be populated with dates
        datasets: [{
            label: 'Progress',
            data: [], // Will be populated: {{progress_data}}
            borderColor: '#50cd89',
            backgroundColor: 'rgba(80, 205, 137, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        }
    }
};

// Helper function to format currency
export const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
};

// Helper function to format date
export const formatDate = (dateString) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(date);
};

// Helper function to format time
export const formatTime = (dateString) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
};

