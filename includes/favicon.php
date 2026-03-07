<?php
/**
 * Favicon Links - Global Include
 * DepEd HRMPSB Evaluation System
 * 
 * This file provides consistent favicon implementation across all pages.
 * Include this file in the <head> section of all HTML/PHP pages.
 * 
 * Usage in admin folder:
 *   <?php require_once(__DIR__ . '/../includes/favicon.php'); ?>
 * 
 * Usage in root folder:
 *   <?php require_once(__DIR__ . '/includes/favicon.php'); ?>
 */

// Simple path detection based on current directory
$currentFile = $_SERVER['SCRIPT_NAME'];
$inAdminFolder = strpos($currentFile, '/admin/') !== false || strpos($currentFile, '/reclassification_admin/') !== false;
$faviconPath = $inAdminFolder ? '../images/' : 'images/';
$manifestPath = $inAdminFolder ? '../images/' : 'images/';
// Cache busting - force browser to reload favicon
$cacheBuster = '?v=' . filemtime(__DIR__ . '/../images/favicon.ico');
?>
<!-- Favicon - DepEd HRMPSB Evaluation System -->
<link rel="shortcut icon" href="<?php echo $faviconPath; ?>favicon.ico<?php echo $cacheBuster; ?>" type="image/x-icon">
<link rel="icon" type="image/x-icon" href="<?php echo $faviconPath; ?>favicon.ico<?php echo $cacheBuster; ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo $faviconPath; ?>favicon-32x32.png<?php echo $cacheBuster; ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo $faviconPath; ?>favicon-16x16.png<?php echo $cacheBuster; ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo $faviconPath; ?>apple-touch-icon.png<?php echo $cacheBuster; ?>">
<link rel="manifest" href="<?php echo $manifestPath; ?>site.webmanifest<?php echo $cacheBuster; ?>">
<!-- Theme Colors -->
<meta name="theme-color" content="#E04040">
<meta name="msapplication-TileColor" content="#E04040">
