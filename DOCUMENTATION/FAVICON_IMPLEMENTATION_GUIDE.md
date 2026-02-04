# Favicon Implementation Guide
**DepEd HRMPSB Evaluation System**

## Overview
The favicon system provides consistent branding across all pages including desktop browsers, mobile devices, and different tab states (active, inactive, pinned).

## Assets

All favicon assets are located in the `images/` folder:

- **favicon.ico** - Standard ICO format (multi-resolution: 16x16, 32x32, 48x48)
- **favicon-16x16.png** - 16×16 PNG for smaller displays
- **favicon-32x32.png** - 32×32 PNG for standard displays
- **apple-touch-icon.png** - 180×180 PNG for iOS devices (home screen)
- **android-chrome-192x192.png** - 192×192 PNG for Android
- **android-chrome-512x512.png** - 512×512 PNG for Android (high-res)

## Implementation Methods

### Method 1: PHP Include (Recommended for PHP files)

For PHP files, use the centralized include file:

```php
<?php require_once(__DIR__ . '/includes/favicon.php'); ?>
```

**Benefits:**
- Automatic path resolution based on file location
- Consistent implementation
- Easy maintenance (update once, applies everywhere)
- Includes PWA manifest reference

**Path Resolution:**
The include automatically detects directory depth and adjusts paths:
- Root level: `images/favicon.ico`
- Admin folder: `../images/favicon.ico`
- Nested folders: Calculated automatically

### Method 2: Direct HTML Links (For static HTML files)

For HTML files that can't execute PHP, add these links in the `<head>` section:

**Root Level Pages:**
```html
<link rel="icon" type="image/x-icon" href="images/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
<link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
<link rel="manifest" href="site.webmanifest">
<meta name="theme-color" content="#E04040">
```

**Admin Folder Pages:**
```html
<link rel="icon" type="image/x-icon" href="../images/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="../images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../images/favicon-16x16.png">
<link rel="apple-touch-icon" sizes="180x180" href="../images/apple-touch-icon.png">
<link rel="manifest" href="../site.webmanifest">
<meta name="theme-color" content="#E04040">
```

## Files Updated

### Admin Dashboard (✅ Complete)
- `admin/login.php` - PHP include added
- `admin/index.php` - PHP include added
- `admin/applicants.php` - PHP include added
- `admin/drafts.php` - PHP include added

### Main System Pages (✅ Complete)
- `index.php` - Already had favicons (verified)
- `view_evaluation_report.php` - PHP include added
- `view_car.php` - Already had favicons (verified)
- `comparative_assessment_results.php` - Already had favicons (verified)
- `generate_car_g2.php` - Already had favicons (verified)

### Testing/Diagnostic Pages (✅ Complete)
- `test_input_colors.html` - Direct HTML links added

### Generated Reports (✅ Complete)
- `classes/IESReportGenerator.php` - Already had favicons with absolute paths

## Browser Compatibility

The implementation supports:

### Desktop Browsers
- **Chrome/Edge**: Uses favicon.ico and PNG variants
- **Firefox**: Uses favicon.ico and PNG variants
- **Safari**: Uses apple-touch-icon.png and PNG variants

### Mobile Browsers
- **iOS Safari**: Uses apple-touch-icon.png (180×180)
- **Android Chrome**: Uses android-chrome icons from manifest (192×192, 512×512)
- **Mobile Firefox**: Uses standard PNG variants

### Tab States
- **Active tabs**: Standard favicon display
- **Inactive tabs**: Grayscale or dimmed (browser-dependent)
- **Pinned tabs**: Uses smallest available icon (16×16)

## PWA Support

The `site.webmanifest` file enables Progressive Web App features:

```json
{
  "name": "DepEd HRMPSB Evaluation System",
  "short_name": "DepEd HRMPSB",
  "icons": [
    {
      "src": "/DEPEDEvaluationSystemV2/images/android-chrome-192x192.png",
      "sizes": "192x192",
      "type": "image/png"
    },
    {
      "src": "/DEPEDEvaluationSystemV2/images/android-chrome-512x512.png",
      "sizes": "512x512",
      "type": "image/png"
    }
  ],
  "theme_color": "#E04040",
  "background_color": "#ffffff",
  "display": "standalone"
}
```

**Benefits:**
- Mobile users can add to home screen
- App-like experience on mobile devices
- Custom splash screen with branding
- Consistent theme color in mobile browsers

## Theme Color

The system uses **#E04040** (DepEd red) as the primary theme color:
- Applied in `<meta name="theme-color">` tag
- Used in PWA manifest
- Displayed in:
  - Android Chrome address bar
  - iOS Safari status bar (when added to home screen)
  - Windows taskbar (when pinned)

## Testing Checklist

### Desktop Testing
- [ ] Open any page in Chrome - verify favicon displays in tab
- [ ] Open any page in Firefox - verify favicon displays in tab
- [ ] Open any page in Safari - verify favicon displays in tab
- [ ] Open any page in Edge - verify favicon displays in tab
- [ ] Pin a tab - verify smaller icon displays correctly
- [ ] Bookmark a page - verify icon appears in bookmarks

### Mobile Testing (Android)
- [ ] Open site in Chrome - verify address bar shows theme color
- [ ] Add to home screen - verify 192×192 icon displays
- [ ] Launch from home screen - verify 512×512 splash screen
- [ ] Check icon quality on different screen densities

### Mobile Testing (iOS)
- [ ] Open site in Safari - verify status bar theme color
- [ ] Add to home screen - verify 180×180 icon displays
- [ ] Launch from home screen - verify standalone mode
- [ ] Check icon quality on Retina displays

## Troubleshooting

### Favicon Not Displaying
1. **Clear browser cache** - Favicons are aggressively cached
2. **Hard refresh** - Ctrl+Shift+R (Windows) or Cmd+Shift+R (Mac)
3. **Check file paths** - Verify relative paths are correct
4. **Check file permissions** - Ensure images folder is readable

### Wrong Favicon Displays
1. **Clear favicon cache** - Delete browser cache completely
2. **Check multiple files** - Browser may use cached version from different page
3. **Verify file format** - Ensure ICO and PNG files are valid

### Mobile Issues
1. **Delete from home screen** - Re-add to get fresh icon
2. **Check manifest.json** - Verify paths are absolute
3. **Check icon sizes** - Ensure PNG files are correct dimensions

## Maintenance

### Updating Favicons
1. Replace files in `images/` folder
2. Maintain same filenames and formats
3. Clear browser caches after update
4. Test across all devices

### Adding New Pages
For new PHP pages:
```php
<?php require_once(__DIR__ . '/includes/favicon.php'); ?>
```

For new HTML pages, copy the appropriate link set based on folder location.

## References

- **Favicon Generator**: https://realfavicongenerator.net/
- **PWA Manifest**: https://web.dev/add-manifest/
- **Apple Touch Icons**: https://developer.apple.com/library/archive/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html

---

**Implementation Date**: January 31, 2025  
**System Version**: DepEd HRMPSB Evaluation System V2  
**Status**: ✅ Complete
