/**
 * Student Dashboard Scripts
 * Scripts for student dashboard components and interactions
 */

import { formatDate, formatTime, formatRelativeTime } from '../../../../shared/utils/formatters.js';

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    initializeLearningActions();
    initializeSessionActions();
    initializePaymentActions();
    initializeCertificateActions();
    initializeChatActions();
    updatePaymentCTA();
});

/**
 * Initialize Learning Actions
 */
function initializeLearningActions() {
    // Continue learning
    window.continueLearning = function(courseId, lessonId) {
        // Navigate to lesson page
        window.location.href = `/student/courses/${courseId}/lessons/${lessonId}`;
    };
}

/**
 * Initialize Session Actions
 */
function initializeSessionActions() {
    // View session
    window.viewSession = function(sessionId) {
        // Navigate to session details page
        window.location.href = `/student/sessions/${sessionId}`;
    };
}

/**
 * Initialize Payment Actions
 */
function initializePaymentActions() {
    // Pay now
    window.payNow = function(paymentId) {
        // Navigate to payment page
        window.location.href = `/student/payments/${paymentId}/pay`;
    };
}

/**
 * Initialize Certificate Actions
 */
function initializeCertificateActions() {
    // Certificate download is handled by browser directly via download attribute
    // Additional logic can be added here if needed
}

/**
 * Initialize Chat Actions
 */
function initializeChatActions() {
    // Chat with instructor
    window.chatInstructor = function(instructorId) {
        // Create form and submit to create or get conversation
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/student/chat/create';
        
        // Add CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        // Add user_id
        const userIdInput = document.createElement('input');
        userIdInput.type = 'hidden';
        userIdInput.name = 'user_id';
        userIdInput.value = instructorId;
        form.appendChild(userIdInput);
        
        document.body.appendChild(form);
        form.submit();
    };

    // Chat with admin
    window.chatAdmin = function(adminId) {
        if (adminId) {
            // Create form and submit to create or get conversation
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/student/chat/create';
            
            // Add CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
            
            // Add user_id
            const userIdInput = document.createElement('input');
            userIdInput.type = 'hidden';
            userIdInput.name = 'user_id';
            userIdInput.value = adminId;
            form.appendChild(userIdInput);
            
            document.body.appendChild(form);
            form.submit();
        } else {
            // Navigate to chat index if no admin ID provided
            window.location.href = '/student/chat';
        }
    };
}

/**
 * Update Payment CTA visibility
 */
function updatePaymentCTA() {
    const paymentStatus = document.querySelector('[data-payment-status]')?.getAttribute('data-payment-status');
    const paymentCTA = document.getElementById('payment_cta');
    
    if (paymentStatus === 'pending' && paymentCTA) {
        paymentCTA.classList.remove('d-none');
    }
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
    initializeLearningActions,
    initializeSessionActions,
    initializePaymentActions,
    initializeCertificateActions,
    initializeChatActions
};

