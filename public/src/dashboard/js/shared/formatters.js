/**
 * Shared Formatters
 * Common formatting functions for all dashboards
 */

/**
 * Format currency in Indonesian Rupiah
 * @param {number} value - Amount to format
 * @returns {string} Formatted currency string
 */
export const formatCurrency = (value) => {
    if (!value && value !== 0) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(value);
};

/**
 * Format date in Indonesian format
 * @param {string|Date} dateString - Date to format
 * @returns {string} Formatted date string
 */
export const formatDate = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(date);
};

/**
 * Format date and time in Indonesian format
 * @param {string|Date} dateString - Date to format
 * @returns {string} Formatted date and time string
 */
export const formatDateTime = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
};

/**
 * Format time only
 * @param {string|Date} dateString - Date to format
 * @returns {string} Formatted time string
 */
export const formatTime = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
};

/**
 * Format relative time (e.g., "2 hours ago")
 * @param {string|Date} dateString - Date to format
 * @returns {string} Relative time string
 */
export const formatRelativeTime = (dateString) => {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);

    if (diffInSeconds < 60) return 'baru saja';
    if (diffInSeconds < 3600) {
        const minutes = Math.floor(diffInSeconds / 60);
        return `${minutes} menit yang lalu`;
    }
    if (diffInSeconds < 86400) {
        const hours = Math.floor(diffInSeconds / 3600);
        return `${hours} jam yang lalu`;
    }
    if (diffInSeconds < 604800) {
        const days = Math.floor(diffInSeconds / 86400);
        return `${days} hari yang lalu`;
    }
    return formatDate(dateString);
};

/**
 * Format percentage
 * @param {number} value - Value to format
 * @param {number} total - Total value
 * @returns {string} Formatted percentage string
 */
export const formatPercentage = (value, total) => {
    if (!total || total === 0) return '0%';
    const percentage = (value / total) * 100;
    return `${percentage.toFixed(1)}%`;
};

/**
 * Format number with thousand separator
 * @param {number} value - Number to format
 * @returns {string} Formatted number string
 */
export const formatNumber = (value) => {
    if (!value && value !== 0) return '0';
    return new Intl.NumberFormat('id-ID').format(value);
};

/**
 * Get status badge class based on status
 * @param {string} status - Status value
 * @returns {string} Badge class
 */
export const getStatusBadgeClass = (status) => {
    const statusMap = {
        'active': 'badge-light-success',
        'pending': 'badge-light-warning',
        'expired': 'badge-light-danger',
        'completed': 'badge-light-success',
        'cancelled': 'badge-light-danger',
        'approved': 'badge-light-success',
        'rejected': 'badge-light-danger',
        'paid': 'badge-light-success',
        'unpaid': 'badge-light-warning'
    };
    return statusMap[status.toLowerCase()] || 'badge-light-secondary';
};

/**
 * Get status text in Indonesian
 * @param {string} status - Status value
 * @returns {string} Status text in Indonesian
 */
export const getStatusText = (status) => {
    const statusMap = {
        'active': 'Aktif',
        'pending': 'Pending',
        'expired': 'Kadaluarsa',
        'completed': 'Selesai',
        'cancelled': 'Dibatalkan',
        'approved': 'Disetujui',
        'rejected': 'Ditolak',
        'paid': 'Lunas',
        'unpaid': 'Belum Lunas'
    };
    return statusMap[status.toLowerCase()] || status;
};

