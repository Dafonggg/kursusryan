/**
 * Admin Dashboard Scripts
 * Scripts for admin dashboard components and interactions
 */

import { incomeChartConfig, formatCurrency, formatDate } from '../../../../shared/utils/chart-config.js';
import { formatCurrency as formatCurrencyUtil, formatDate as formatDateUtil, formatRelativeTime } from '../../../../shared/utils/formatters.js';

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    initializeIncomeChart();
    initializeDataTables();
    initializeRescheduleActions();
    initializeQuickActions();
});

/**
 * Initialize Income Chart (12 months)
 */
function initializeIncomeChart() {
    const ctx = document.getElementById('kt_income_chart');
    if (!ctx) return;

    // Get data from API placeholder
    // In production, replace with actual API call
    const monthlyIncome = getMonthlyIncomeData(); // {{monthly_income}}

    const config = {
        ...incomeChartConfig,
        data: {
            ...incomeChartConfig.data,
            datasets: [{
                ...incomeChartConfig.data.datasets[0],
                data: monthlyIncome
            }]
        }
    };

    new Chart(ctx, config);
}

/**
 * Get monthly income data (placeholder)
 * Replace with actual API call
 */
function getMonthlyIncomeData() {
    // Placeholder data - replace with API call
    return [
        15000000, // Jan
        18000000, // Feb
        20000000, // Mar
        22000000, // Apr
        25000000, // May
        28000000, // Jun
        30000000, // Jul
        32000000, // Aug
        35000000, // Sep
        38000000, // Oct
        40000000, // Nov
        42000000  // Dec
    ];
}

/**
 * Initialize DataTables
 */
function initializeDataTables() {
    // Initialize DataTables for registrations table
    const registrationsTable = document.querySelector('#kt_registrations_table');
    if (registrationsTable) {
        // DataTable initialization code
        // $(registrationsTable).DataTable({ ... });
    }

    // Initialize DataTables for reschedule requests table
    const rescheduleTable = document.querySelector('#kt_reschedule_table');
    if (rescheduleTable) {
        // DataTable initialization code
    }

    // Initialize DataTables for today sessions table
    const sessionsTable = document.querySelector('#kt_sessions_table');
    if (sessionsTable) {
        // DataTable initialization code
    }

    // Initialize DataTables for tickets table
    const ticketsTable = document.querySelector('#kt_tickets_table');
    if (ticketsTable) {
        // DataTable initialization code
    }
}

/**
 * Initialize Reschedule Actions
 */
function initializeRescheduleActions() {
    // Approve reschedule request
    window.approveReschedule = function(requestId) {
        if (confirm('Apakah Anda yakin ingin menyetujui permintaan reschedule ini?')) {
            // API call to approve reschedule
            fetch(`/api/reschedule/${requestId}/approve`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal menyetujui permintaan reschedule');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyetujui permintaan reschedule');
            });
        }
    };

    // Reject reschedule request
    window.rejectReschedule = function(requestId) {
        const reason = prompt('Alasan penolakan:');
        if (reason) {
            // API call to reject reschedule
            fetch(`/api/reschedule/${requestId}/reject`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                body: JSON.stringify({ reason: reason })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal menolak permintaan reschedule');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menolak permintaan reschedule');
            });
        }
    };
}

/**
 * Initialize Quick Actions
 */
function initializeQuickActions() {
    // Export financial data to CSV
    window.exportFinancialData = function() {
        // API call to export financial data
        fetch('/api/financial/export', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': getCsrfToken()
            }
        })
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'financial_data.csv';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengekspor data keuangan');
        });
    };

    // Verify pending payment
    window.verifyPayment = function(paymentId) {
        if (confirm('Apakah Anda yakin ingin memverifikasi pembayaran ini?')) {
            // API call to verify payment
            fetch(`/api/payments/${paymentId}/verify`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken()
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal memverifikasi pembayaran');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memverifikasi pembayaran');
            });
        }
    };
}

/**
 * View ticket details
 */
window.viewTicket = function(ticketId) {
    // Navigate to ticket details page or open modal
    window.location.href = `/admin/tickets/${ticketId}`;
};

/**
 * Get CSRF token (placeholder)
 * Replace with actual CSRF token retrieval
 */
function getCsrfToken() {
    // Placeholder - replace with actual CSRF token retrieval
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

/**
 * Format currency helper
 */
window.formatCurrency = formatCurrencyUtil;

/**
 * Format date helper
 */
window.formatDate = formatDateUtil;

/**
 * Format relative time helper
 */
window.formatRelativeTime = formatRelativeTime;

// Export functions for use in other modules
export {
    initializeIncomeChart,
    initializeDataTables,
    initializeRescheduleActions,
    initializeQuickActions
};

