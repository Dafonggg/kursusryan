/**
 * Instructor Dashboard Scripts
 * Scripts for instructor dashboard components and interactions
 */

import { formatDate, formatTime, formatRelativeTime } from '../../../../shared/utils/formatters.js';

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    initializeRescheduleActions();
    initializeAttendanceActions();
    initializeMessageActions();
    initializeCourseActions();
});

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
 * Initialize Attendance Actions
 */
function initializeAttendanceActions() {
    // Input attendance
    window.inputAttendance = function(sessionId) {
        // Navigate to attendance input page
        window.location.href = `/instructor/sessions/${sessionId}/attendance`;
    };
}

/**
 * Initialize Message Actions
 */
function initializeMessageActions() {
    // View message
    window.viewMessage = function(messageId) {
        // Navigate to message details page or open modal
        window.location.href = `/instructor/messages/${messageId}`;
    };
}

/**
 * Initialize Course Actions
 */
function initializeCourseActions() {
    // Manage course
    window.manageCourse = function(courseId) {
        // Navigate to course management page
        window.location.href = `/instructor/courses/${courseId}`;
    };
}

/**
 * Get CSRF token (placeholder)
 * Replace with actual CSRF token retrieval
 */
function getCsrfToken() {
    // Placeholder - replace with actual CSRF token retrieval
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

// Export functions for use in other modules
export {
    initializeRescheduleActions,
    initializeAttendanceActions,
    initializeMessageActions,
    initializeCourseActions
};

