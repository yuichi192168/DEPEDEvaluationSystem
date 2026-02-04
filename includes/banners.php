<?php
/**
 * Banner System for User-Friendly Messages
 * Provides reusable functions for displaying success, error, warning, and info messages
 */

/**
 * Display a success banner message
 * @param string $message - The success message to display
 * @param bool $autoHide - Whether to auto-hide after 5 seconds (default: true)
 */
function showSuccessBanner($message, $autoHide = true) {
    $autoHideClass = $autoHide ? ' auto-hide' : '';
    echo <<<HTML
    <div class="banner banner-success{$autoHideClass}" role="status" aria-live="polite">
        <div class="banner-content">
            <span class="banner-icon"><i class="fas fa-check-circle"></i></span>
            <span class="banner-text">$message</span>
            <button class="banner-close" onclick="this.parentElement.parentElement.style.display='none';" aria-label="Close message">&times;</button>
        </div>
    </div>
    HTML;
}

/**
 * Display an error banner message
 * @param string $message - The error message to display
 */
function showErrorBanner($message) {
    echo <<<HTML
    <div class="banner banner-error" role="alert" aria-live="assertive" id="errorBanner">
        <div class="banner-content">
            <span class="banner-icon"><i class="fas fa-exclamation-circle"></i></span>
            <span class="banner-text">$message</span>
            <button class="banner-close" onclick="this.parentElement.parentElement.style.display='none';" aria-label="Close message">&times;</button>
        </div>
    </div>
    <script>
        // Scroll error banner into view
        document.getElementById('errorBanner').scrollIntoView({ behavior: 'smooth', block: 'start' });
    </script>
    HTML;
}

/**
 * Display a warning banner message
 * @param string $message - The warning message to display
 */
function showWarningBanner($message) {
    echo <<<HTML
    <div class="banner banner-warning" role="status" aria-live="polite">
        <div class="banner-content">
            <span class="banner-icon"><i class="fas fa-exclamation-triangle"></i></span>
            <span class="banner-text">$message</span>
            <button class="banner-close" onclick="this.parentElement.parentElement.style.display='none';" aria-label="Close message">&times;</button>
        </div>
    </div>
    HTML;
}

/**
 * Display an info banner message
 * @param string $message - The info message to display
 */
function showInfoBanner($message) {
    echo <<<HTML
    <div class="banner banner-info" role="status" aria-live="polite">
        <div class="banner-content">
            <span class="banner-icon"><i class="fas fa-info-circle"></i></span>
            <span class="banner-text">$message</span>
            <button class="banner-close" onclick="this.parentElement.parentElement.style.display='none';" aria-label="Close message">&times;</button>
        </div>
    </div>
    HTML;
}

/**
 * Display a processing/loading banner message
 * @param string $message - The processing message to display
 */
function showProcessingBanner($message) {
    echo <<<HTML
    <div class="banner banner-processing" role="status" aria-live="polite">
        <div class="banner-content">
            <span class="banner-icon loading-spinner"><i class="fas fa-spinner fa-spin"></i></span>
            <span class="banner-text">$message</span>
        </div>
    </div>
    HTML;
}

/**
 * Get banner from URL parameters and display it
 * Call this at the top of your page
 */
function displayBannerFromSession() {
    if (isset($_SESSION['banner_message'])) {
        $banner = $_SESSION['banner_message'];
        $type = $banner['type'] ?? 'info';
        $message = $banner['message'] ?? '';
        $autoHide = $banner['auto_hide'] ?? true;
        
        switch ($type) {
            case 'success':
                showSuccessBanner($message, $autoHide);
                break;
            case 'error':
                showErrorBanner($message);
                break;
            case 'warning':
                showWarningBanner($message);
                break;
            case 'info':
                showInfoBanner($message);
                break;
            case 'processing':
                showProcessingBanner($message);
                break;
        }
        
        unset($_SESSION['banner_message']);
    }
}

/**
 * Set a banner message to be displayed on page load
 * @param string $type - Type: 'success', 'error', 'warning', 'info', 'processing'
 * @param string $message - The message to display
 * @param bool $autoHide - Whether to auto-hide (only for success)
 */
function setBannerMessage($type, $message, $autoHide = true) {
    if (!session_id()) {
        session_start();
    }
    $_SESSION['banner_message'] = [
        'type' => $type,
        'message' => $message,
        'auto_hide' => $autoHide
    ];
}
?>
