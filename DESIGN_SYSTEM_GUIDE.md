# Design System Guide - DepEd HRMPSB Evaluation System

## Overview

This document describes the comprehensive design system implemented across the DepEd Evaluation System to ensure consistent, professional, and accessible user interfaces throughout the application.

## Design System Components

### 1. Color Palette

#### Primary Colors
- **Primary Red**: `#E04040` - Main brand color for buttons, headers, and accents
- **Primary Red Dark**: `#c83030` - Hover state for primary red elements
- **Primary Red Light**: `#E06060` - Secondary shade for gradients

#### Neutral Colors
- **Text Primary**: `#333333` - Main text color
- **Text Secondary**: `#666666` - Secondary text, descriptions
- **Text Muted**: `#999999` - Disabled or inactive text
- **Text Light**: `#f8f9fa` - Light text on dark backgrounds
- **Border Color**: `#e0e0e0` - Standard borders
- **Border Color Dark**: `#d0d0d0` - Emphasis borders

#### Background Colors
- **BG Primary**: `#ffffff` - Main background
- **BG Secondary**: `#f8f9fa` - Secondary/subtle background
- **BG Tertiary**: `#f0f0f0` - Tertiary/alternate background

#### Status Colors
- **Success**: `#28a745` - Success states, confirmations
- **Warning**: `#ffc107` - Warning messages
- **Danger**: `#dc3545` - Danger, delete, error states
- **Info**: `#17a2b8` - Information messages

### 2. Typography

#### Font Family
- **Primary**: Segoe UI, Tahoma, Geneva, Verdana, sans-serif

#### Font Sizes
- **xs**: 12px - Small text, labels
- **sm**: 13px - Form text, small descriptions
- **base**: 14px - Body text, default
- **lg**: 16px - Larger body text
- **xl**: 18px - Section headers
- **2xl**: 20px - Page subtitle headers
- **3xl**: 28px - Main page headers

#### Font Weights
- **Regular**: 400 - Normal text
- **Medium**: 500 - Medium emphasis
- **Semi-bold**: 600 - Headers, strong text
- **Bold**: 700 - Bold text, emphasis

### 3. Spacing Scale

All spacing values follow a consistent 4px baseline:

- **xs**: 4px
- **sm**: 8px
- **md**: 12px
- **lg**: 16px
- **xl**: 20px
- **2xl**: 24px
- **3xl**: 32px
- **4xl**: 40px
- **5xl**: 48px

Usage:
- Padding/margin in components: `var(--spacing-md)`, `var(--spacing-lg)`
- Gap between flex items: `var(--spacing-md)`, `var(--spacing-lg)`
- Element spacing: `var(--spacing-xl)`, `var(--spacing-2xl)`

### 4. Icon System

#### Font Awesome 6.4.0 Integration
- **CDN Link**: `https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css`
- **Usage**: `<i class="fas fa-icon-name"></i>`

#### Icon Sizing Classes
```css
.icon-xs  { font-size: 16px; }
.icon-sm  { font-size: 20px; }
.icon-md  { font-size: 24px; }
.icon-lg  { font-size: 28px; }
.icon-xl  { font-size: 36px; }
.icon-2xl { font-size: 40px; }
```

#### Icon Color Variants
```css
.icon-primary { color: #E04040; }
.icon-success { color: #28a745; }
.icon-warning { color: #ffc107; }
.icon-danger  { color: #dc3545; }
.icon-info    { color: #17a2b8; }
.icon-muted   { color: #999999; }
```

#### Common Font Awesome Icons
| Icon | Class | Usage |
|------|-------|-------|
| User | `fas fa-user` | User profiles, accounts |
| Users | `fas fa-users` | Groups, teams |
| Lock | `fas fa-lock` | Security, authentication |
| Key | `fas fa-key` | Passwords, security |
| Archive | `fas fa-archive` | Archive, storage |
| Trash | `fas fa-trash` | Delete, remove |
| Download | `fas fa-download` | Download, restore |
| Upload | `fas fa-upload` | Upload, import |
| Search | `fas fa-search` | Search, find |
| Filter | `fas fa-filter` | Filter, refine |
| Sync | `fas fa-sync` | Refresh, reset |
| Tasks | `fas fa-tasks` | Tasks, checklist |
| File | `fas fa-file-alt` | Documents, files |
| Eye | `fas fa-eye` | View, visible |
| Undo | `fas fa-undo` | Restore, undo |
| Arrow Left | `fas fa-arrow-left` | Back, previous |
| Chart Bar | `fas fa-chart-bar` | Statistics, data |
| History | `fas fa-history` | Audit trail, logs |

### 5. Button System

#### Button Styles

**Primary Button**
```html
<button class="btn btn-primary">
    <i class="fas fa-icon-name" style="margin-right: 8px;"></i>Action
</button>
```
- Background: `#E04040`
- Hover: `#c83030`
- Text: white
- Used for: Main actions, calls-to-action

**Secondary Button**
```html
<button class="btn btn-secondary">
    <i class="fas fa-icon-name" style="margin-right: 8px;"></i>Action
</button>
```
- Background: `#f0f0f0`
- Hover: `#e0e0e0`
- Text: `#333`
- Used for: Secondary actions, filters

**Success Button**
```html
<button class="btn btn-success">
    <i class="fas fa-icon-name" style="margin-right: 8px;"></i>Confirm
</button>
```
- Background: `#28a745`
- Hover: `#218838`
- Text: white
- Used for: Confirmations, positive actions

**Danger Button**
```html
<button class="btn btn-danger">
    <i class="fas fa-icon-name" style="margin-right: 8px;"></i>Delete
</button>
```
- Background: `#dc3545`
- Hover: `#c82333`
- Text: white
- Used for: Delete, archive, warnings

#### Button Sizes

- **Default**: `padding: 10px 20px; font-size: 13px;`
- **Small**: `padding: 8px 15px; font-size: 12px;` (`.btn-small`)
- **Large**: `padding: 12px 24px; font-size: 14px;` (`.btn-large`)

### 6. Form Elements

#### Input Fields
```html
<input type="text" class="form-input" placeholder="Enter text...">
```

**Styles:**
- Padding: `10px`
- Border: `1px solid #e0e0e0`
- Border Radius: `4px`
- Font Size: `13px`
- Focus: Blue outline (`outline: 2px solid #0066cc`)
- Placeholder: `#999999`

#### Textareas
```html
<textarea class="form-textarea" placeholder="Enter text..."></textarea>
```

**Styles:**
- Padding: `10px`
- Border: `1px solid #e0e0e0`
- Border Radius: `4px`
- Font Size: `13px`
- Rows: Default 4, adjustable
- Focus: Same as inputs

#### Select Dropdowns
```html
<select class="form-select">
    <option>Select option...</option>
</select>
```

**Styles:**
- Padding: `10px`
- Border: `1px solid #e0e0e0`
- Border Radius: `4px`
- Font Size: `13px`
- Background: white
- Focus: Blue outline

### 7. Card Components

```html
<div class="card">
    <h2>Card Title</h2>
    <p>Card content goes here.</p>
    <button class="btn btn-primary">Action</button>
</div>
```

**Styles:**
- Padding: `25px`
- Background: white
- Border: `4px solid #E04040` (top border)
- Border Radius: `8px`
- Box Shadow: `0 2px 10px rgba(0, 0, 0, 0.1)`
- Hover: Lift effect (`translateY(-5px)`)

### 8. Tables

#### Header Row
```html
<thead>
    <tr>
        <th>Column 1</th>
        <th>Column 2</th>
    </tr>
</thead>
```

**Styles:**
- Background: `#f8f9fa`
- Border Bottom: `2px solid #e0e0e0`
- Padding: `15px`
- Font Weight: 600

#### Body Rows
```html
<tbody>
    <tr>
        <td>Data 1</td>
        <td>Data 2</td>
    </tr>
</tbody>
```

**Styles:**
- Padding: `15px`
- Border Bottom: `1px solid #e0e0e0`
- Hover: Background `#f8f9fa`

### 9. Alerts/Messages

#### Success Alert
```html
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i>
    Success message here
</div>
```

#### Warning Alert
```html
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle"></i>
    Warning message here
</div>
```

#### Danger Alert
```html
<div class="alert alert-danger">
    <i class="fas fa-times-circle"></i>
    Error message here
</div>
```

#### Info Alert
```html
<div class="alert alert-info">
    <i class="fas fa-info-circle"></i>
    Information here
</div>
```

### 10. Responsive Design

#### Breakpoints

| Breakpoint | Width | Usage |
|-----------|-------|-------|
| Desktop | > 1024px | Full layout, 2-4 columns |
| Tablet | 768px - 1024px | Adjusted columns, 1-2 columns |
| Mobile | < 768px | Single column, full width |

#### Responsive Grid
```css
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
}

@media (max-width: 768px) {
    .grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
}
```

### 11. Accessibility Features

#### Focus States
```css
button:focus-visible,
input:focus-visible,
select:focus-visible {
    outline: 2px solid #0066cc;
    outline-offset: 2px;
}
```

#### High Contrast Mode Support
```css
@media (prefers-contrast: more) {
    body {
        /* Enhanced contrast colors */
    }
}
```

#### Reduced Motion Support
```css
@media (prefers-reduced-motion: reduce) {
    * {
        animation: none !important;
        transition: none !important;
    }
}
```

#### Dark Mode Support
```css
@media (prefers-color-scheme: dark) {
    body {
        background: #1e1e1e;
        color: #f0f0f0;
    }
}
```

## Implementation Files

### CSS Variables File
**Location**: `css/design-system.css`
**Size**: 740+ lines
**Contains**:
- Root CSS variables for all design system values
- Base element styles
- Component styling (buttons, forms, cards, tables, etc.)
- Layout utilities
- Responsive design rules
- Accessibility utilities

## Usage Examples

### Using Design System in HTML

#### Page Header with Icon
```html
<div class="header">
    <h1>
        <i class="fas fa-sliders-h" style="margin-right: 12px;"></i>
        Admin Dashboard
    </h1>
    <p>Manage applicants and system settings</p>
</div>
```

#### Button with Icon
```html
<button class="btn btn-primary">
    <i class="fas fa-download" style="margin-right: 8px;"></i>
    Load Draft
</button>
```

#### Card Layout
```html
<div class="card">
    <h2>
        <i class="fas fa-users" style="margin-right: 8px;"></i>
        Applicants Management
    </h2>
    <p>View, search, and manage all applicants</p>
    <div class="card-actions">
        <a href="applicants.php" class="btn btn-primary">Open Dashboard</a>
    </div>
</div>
```

#### Table with Icons
```html
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>John Doe</td>
            <td>
                <button class="btn btn-secondary">
                    <i class="fas fa-eye"></i> View
                </button>
                <button class="btn btn-danger">
                    <i class="fas fa-archive"></i> Archive
                </button>
            </td>
        </tr>
    </tbody>
</table>
```

## Implementation Checklist

### Phase 1: Setup (COMPLETED)
- [x] Create design-system.css with all variables and components
- [x] Add Font Awesome 6.4.0 CDN links to all pages
- [x] Import design-system.css in all HTML files

### Phase 2: Admin Pages (COMPLETED)
- [x] Update admin/login.php with new icons and styling
- [x] Update admin/index.php with new icons and styling
- [x] Update admin/applicants.php with new icons and styling
- [x] Update admin/drafts.php with new icons and styling

### Phase 3: Documentation (IN PROGRESS)
- [x] Remove emojis from APPLICANTS_DASHBOARD_SETUP.md
- [x] Remove emojis from VISUAL_GUIDE.md
- [ ] Remove emojis from IMPLEMENTATION_SUMMARY.md
- [ ] Remove emojis from other documentation files

### Phase 4: Testing
- [ ] Cross-browser icon rendering verification
- [ ] Accessibility compliance testing
- [ ] Mobile responsiveness testing
- [ ] Performance testing

## Maintenance Guidelines

### Adding New Components
1. Define new CSS classes in design-system.css
2. Use existing CSS variables for colors, spacing, sizing
3. Follow naming conventions (e.g., `.component-name`, `.component-name--variant`)
4. Include hover/focus states for interactive elements
5. Ensure mobile responsiveness with media queries

### Updating Colors
1. Modify CSS variables in `design-system.css` `:root` section
2. All elements automatically use new colors
3. No need to update individual files

### Adding Icons
1. Visit Font Awesome docs to find icon class name
2. Use consistent spacing around icons: `margin-right: 6px` to `12px`
3. Wrap icons in `<i>` tags: `<i class="fas fa-icon-name"></i>`
4. Keep icon sizing consistent with design system

## References

### Font Awesome 6.4.0
- **Documentation**: https://fontawesome.com/docs
- **Icon Search**: https://fontawesome.com/icons
- **Classes**: Use `fas` prefix for solid icons

### Design Best Practices
- Maintain consistent spacing throughout
- Use color intentionally for information hierarchy
- Ensure sufficient color contrast for accessibility
- Test interfaces at multiple screen sizes
- Provide keyboard navigation support

## Support

For questions or issues regarding the design system, refer to:
- `DESIGN_SYSTEM_GUIDE.md` (this file)
- `css/design-system.css` (implementation details)
- Font Awesome documentation (icon usage)
