# Admin Dashboard Visual Guide & Workflows

**Quick visual reference for the new single-page dashboard**

---

## Dashboard Layout

```
┌─────────────────────────────────────────────────────────────────┐
│ ☰                                                    [Username] │ ← Mobile Menu + User Info
├─────────────┬───────────────────────────────────────────────────┤
│             │  📊 Admin Dashboard              [↻ Refresh]      │
│   SIDEBAR   │                                                    │
│   (260px)   │  ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐    │
│             │  │ Total  │ │ Active │ │Archive │ │Group A │    │ ← Statistics Cards
│ 📊 Applicants│ │  150   │ │  120   │ │  30    │ │  50    │    │
│    [5 badge]│  └────────┘ └────────┘ └────────┘ └────────┘    │
│             │  ┌────────┐ ┌────────┐                           │
│ 📄 Drafts   │  │Group B │ │Group C │                           │
│             │  │  45    │ │  25    │                           │
│ 🏠 Return   │  └────────┘ └────────┘                           │
│             │                                                    │
│             │  [Active Applicants] [Archived]  ← Tabs          │
│             │                                                    │
│ ─────────── │  [Search...] [All Groups ▼] [🔍 Search]         │
│             │                                                    │
│ 👤 Admin    │  ┌──────────────────────────────────────────┐   │
│   John Doe  │  │ ID │ Name    │ Position │ Group │ Actions│   │
│   [Logout]  │  ├──────────────────────────────────────────┤   │
│             │  │ 1  │ Juan... │ Teacher  │  A    │[Archive]│   │
│             │  │ 2  │ Maria...│ Principal│  B    │[Archive]│   │
└─────────────┤  │ 3  │ Pedro...│ Staff    │  C    │[Archive]│   │
              │  └──────────────────────────────────────────┘   │
              │  [< First] [< Prev] [1] [2] [3] [Next >] [Last >]│
              └────────────────────────────────────────────────────┘
```

---

## Mobile Layout (≤768px)

```
┌─────────────────────────────────┐
│ ☰                    [Username] │ ← Hamburger Menu
├─────────────────────────────────┤
│  📊 Admin Dashboard   [↻]      │
│                                 │
│  ┌──────┐ ┌──────┐            │ ← 2 columns
│  │Total │ │Active│            │
│  │ 150  │ │ 120  │            │
│  └──────┘ └──────┘            │
│  ┌──────┐ ┌──────┐            │
│  │Archive│ │Grp A │            │
│  │  30  │ │  50  │            │
│  └──────┘ └──────┘            │
│                                 │
│  [Active] [Archived]           │
│                                 │
│  [Search...........]           │ ← Vertical
│  [All Groups ▼    ]           │   Stack
│  [🔍 Search        ]           │
│                                 │
│  ┌───────────────────────────┐│
│  │ Applicants Table          ││
│  │ (scroll horizontally →)   ││
│  └───────────────────────────┘│
└─────────────────────────────────┘

When ☰ clicked:
┌─────────────┬───────────────────┐
│  SIDEBAR    │                   │ ← Sidebar
│  (slides in)│   Main Content    │   Overlays
│             │   (dimmed)        │
│ 📊 Applicants│                   │
│ 📄 Drafts   │                   │
│ 🏠 Return   │                   │
│             │                   │
│ 👤 Admin    │                   │
│   [Logout]  │                   │
└─────────────┴───────────────────┘
```

---

## User Workflows

### Workflow 1: Archive an Applicant

```
Step 1: View Active Applicants
┌────────────────────────────────┐
│ [Active Applicants] [Archived] │ ← Active tab selected
│                                 │
│ 🔍 Search: "Juan"  [Search]    │
│                                 │
│ ┌─────────────────────────────┐│
│ │ #1 │ Juan Dela Cruz...     ││
│ │    │ [Archive] ←──────────┐││ ← Click Archive
│ └─────────────────────────────┘│
└────────────────────────────────┘
        ↓
Step 2: Confirmation Dialog
┌────────────────────────────────┐
│ ⚠ Confirm Archive              │
│                                 │
│ Archive "Juan Dela Cruz"?      │
│                                 │
│ [Cancel]  [Confirm] ←──────────┐ ← Click Confirm
└────────────────────────────────┘
        ↓
Step 3: Success Notification
┌────────────────────────────────┐
│          ┌──────────────────┐  │
│          │ ✅ Success!      │  │ ← Green Banner
│          │ Applicant        │  │   (top-right)
│          │ archived!        │  │
│          └──────────────────┘  │
│                                 │
│ Statistics Update:              │
│ Active: 120 → 119 ✓            │
│ Archived: 30 → 31 ✓            │
│                                 │
│ Table Updates:                  │
│ Juan removed from Active ✓     │
└────────────────────────────────┘
```

### Workflow 2: Load a Draft

```
Step 1: Navigate to Drafts
┌────────────────────────────────┐
│ SIDEBAR                         │
│                                 │
│ 📊 Applicants                  │
│ 📄 Drafts ←────────────────────┐ ← Click Drafts
│ 🏠 Return                       │
└────────────────────────────────┘
        ↓
Step 2: View Drafts Table
┌────────────────────────────────┐
│ 📄 Saved Drafts                │
│                                 │
│ ┌─────────────────────────────┐│
│ │ #1 │ APP-2025-001  │ Jan 30 ││
│ │    │ [Load] [Delete]        ││
│ │    │   ↑                    ││ ← Click Load
│ └─────────────────────────────┘│
└────────────────────────────────┘
        ↓
Step 3: Redirect to Main Form
┌────────────────────────────────┐
│ 🏠 DepEd Evaluation System     │ ← Main System
│                                 │
│ ✅ Draft loaded successfully!  │
│                                 │
│ ┌─────────────────────────────┐│
│ │ Name: [Pre-filled from draft]│ ← Form filled
│ │ Position: [Pre-filled...]    │   with draft
│ │ ...                          │   data
│ └─────────────────────────────┘│
└────────────────────────────────┘
```

### Workflow 3: Search and Filter

```
Step 1: Enter Search Criteria
┌────────────────────────────────┐
│ [Active Applicants] [Archived] │
│                                 │
│ Search: [Maria     ] ←─────────┐ ← Type name
│ Group:  [Group B ▼] ←─────────┐ ← Select group
│         [🔍 Search] ←─────────┐ ← Click search
└────────────────────────────────┘
        ↓
Step 2: Loading State
┌────────────────────────────────┐
│         ⏳ Loading...          │ ← Spinner
│                                 │   Overlay
│         ╭──────────╮           │
│         │    ⟳     │           │
│         ╰──────────╯           │
└────────────────────────────────┘
        ↓
Step 3: Filtered Results
┌────────────────────────────────┐
│ Showing 3 results              │
│                                 │
│ ┌─────────────────────────────┐│
│ │ #5  │ Maria Santos  │ Grp B ││ ← Only matches
│ │ #12 │ Maria Lopez   │ Grp B ││   for "Maria"
│ │ #18 │ Maria Garcia  │ Grp B ││   in Group B
│ └─────────────────────────────┘│
└────────────────────────────────┘
```

---

## State Changes Visualization

### Section Switching

```
Initial State (Applicants):
┌─────────────┬───────────────────┐
│ 📊 Applicants│ Applicants Content│ ← Visible
│    [ACTIVE] │ (opacity: 1)      │
│             │                   │
│ 📄 Drafts   │ Drafts Content    │ ← Hidden
│             │ (display: none)   │
└─────────────┴───────────────────┘
        ↓ Click "Drafts"
        ↓ (0.3s fade transition)
        ↓
Final State (Drafts):
┌─────────────┬───────────────────┐
│ 📊 Applicants│ Applicants Content│ ← Hidden
│             │ (display: none)   │
│             │                   │
│ 📄 Drafts   │ Drafts Content    │ ← Visible
│    [ACTIVE] │ (opacity: 1)      │
└─────────────┴───────────────────┘
```

### Tab Switching

```
Active Tab:
┌────────────────────────────────┐
│ [Active Applicants] [Archived] │
│  ─────────────────             │ ← Underline
│                                 │
│ Showing: Active applicants ✓   │
│ Actions: [Archive] buttons     │
└────────────────────────────────┘
        ↓ Click "Archived"
        ↓ AJAX: load_applicants?tab=archived
        ↓
Archived Tab:
┌────────────────────────────────┐
│ [Active Applicants] [Archived] │
│                     ──────────  │ ← Underline moves
│                                 │
│ Showing: Archived applicants ✓ │
│ Actions: [Restore] buttons     │
└────────────────────────────────┘
```

---

## Interactive Elements

### Stat Cards (Hover Effect)

```
Normal State:
┌────────────┐
│ Total      │
│            │ ← 0px elevation
│    150     │
│            │
└────────────┘

Hover State:
┌────────────┐
│ Total      │ ← -2px transform
│            │   (lifted)
│    150     │   Enhanced shadow
│            │
└────────────┘
```

### Buttons

```
Types:
┌──────────┐  Primary (Red background)
│ Archive  │  Used for: Search, Refresh
└──────────┘

┌──────────┐  Success (Green)
│ Restore  │  Used for: Restore operation
└──────────┘

┌──────────┐  Danger (Red)
│ Delete   │  Used for: Archive, Delete
└──────────┘

┌──────────┐  Secondary (Gray)
│ Cancel   │  Used for: Cancel actions
└──────────┘
```

### Loading Spinner

```
┌─────────────────────────────────┐
│                                 │
│         Full-screen overlay     │
│         (semi-transparent)      │
│                                 │
│            ╭──────╮             │
│            │  ⟳   │ ← Rotating  │
│            ╰──────╯   spinner   │
│                                 │
│         "Loading..."            │
│                                 │
└─────────────────────────────────┘
```

### Banner Notifications

```
Success (Green):
┌──────────────────────────────┐
│ ✅ Success!                  │ ← Fixed position
│ Applicant archived           │   top-right
│                        [✕]   │   Auto-hide: 5s
└──────────────────────────────┘

Error (Red):
┌──────────────────────────────┐
│ ❌ Error!                    │
│ Operation failed             │
│                        [✕]   │
└──────────────────────────────┘

Warning (Yellow):
┌──────────────────────────────┐
│ ⚠ Warning!                   │
│ Please check...              │
│                        [✕]   │
└──────────────────────────────┘
```

---

## Data Flow Diagrams

### AJAX Request Flow

```
Frontend (dashboard.php)
        │
        │ 1. User clicks "Archive"
        ↓
   JavaScript Function
   archiveApplicant(id, name)
        │
        │ 2. Show confirmation
        ↓
   User confirms
        │
        │ 3. Show loading spinner
        │ 4. Create FormData
        │ 5. POST request
        ↓
   fetch('dashboard_ajax.php')
        │
        │ 6. Send to server
        ↓
Backend (dashboard_ajax.php)
        │
        │ 7. Authenticate admin
        │ 8. Validate input
        │ 9. Call ApplicantManager
        ↓
   Database Operation
        │
        │ 10. UPDATE applicants
        │ 11. INSERT audit log
        ↓
   Return JSON Response
        │
        │ 12. {success: true, message: "..."}
        ↓
Frontend receives response
        │
        │ 13. Hide loading spinner
        │ 14. Show success banner
        │ 15. Reload applicants table
        │ 16. Update statistics
        ↓
   User sees updated UI
```

### Statistics Update Flow

```
loadApplicants() called
        │
        ↓
AJAX GET: dashboard_ajax.php
   ?action=load_applicants
   &tab=active
   &search=...
   &group=...
        │
        ↓
Backend processes:
   1. Fetch applicants from DB
   2. Generate HTML table
   3. Calculate statistics:
      - Total count
      - Active count
      - Archived count
      - Group A/B/C counts
        │
        ↓
Return JSON:
   {
     success: true,
     html: "<table>...</table>",
     stats: {
       total: 150,
       active: 120,
       archived: 30,
       group_a: 50,
       group_b: 45,
       group_c: 25
     }
   }
        │
        ↓
Frontend updateStats(stats):
   1. Find each stat card by ID
   2. Update .stat-value text
   3. Animate number change
        │
        ↓
User sees updated counts
```

---

## Responsive Behavior

### Breakpoint Changes (768px)

```
Desktop (>768px):
├── Sidebar: Fixed, Always Visible
├── Main Content: margin-left: 260px
├── Stats Grid: 3 columns (auto-fit)
├── Search: Horizontal layout
└── Tables: Full width

Mobile (≤768px):
├── Sidebar: Transform off-screen
├── Hamburger Menu: Visible
├── Main Content: Full width
├── Stats Grid: 2 columns
├── Search: Vertical stack
└── Tables: Horizontal scroll
```

### Touch Interactions (Mobile)

```
Gesture              Action
─────────────────────────────────
Tap sidebar item  →  Navigate section
Tap outside       →  Close sidebar
Tap hamburger     →  Toggle sidebar
Swipe table       →  Scroll horizontally
Tap button        →  Execute action
Double-tap        →  (No special action)
Long-press        →  (No special action)
```

---

## Animation Timeline

### Section Switch Animation

```
Time    Event
────────────────────────────────
0ms     User clicks sidebar item
10ms    Remove 'active' from all nav items
20ms    Add 'active' to clicked item
30ms    Start fade-out (current section)
        opacity: 1 → 0 (300ms)
330ms   Hide current section (display: none)
340ms   Show new section (display: block)
350ms   Start fade-in (new section)
        opacity: 0 → 1 (300ms)
650ms   Animation complete
```

### Loading Spinner Animation

```
Time    Event
────────────────────────────────
0ms     AJAX request initiated
10ms    Add 'active' class to overlay
20ms    Overlay opacity: 0 → 1 (300ms)
320ms   Spinner visible
        Infinite rotation animation
        360° every 1 second
...     Request processing
[n]ms   Response received
+10ms   Remove 'active' class
+20ms   Overlay opacity: 1 → 0 (300ms)
+320ms  Overlay hidden
```

---

## Color Palette

```
Primary Colors:
┌──────────┐  #E04040  Admin Primary (Red)
│          │  rgb(224, 64, 64)
│  Primary │  Used for: Buttons, accents, sidebar
│          │
└──────────┘

┌──────────┐  #c83030  Darker Red
│          │  rgb(200, 48, 48)
│  Dark    │  Used for: Sidebar gradient end
│          │
└──────────┘

UI Colors:
┌──────────┐  #333333  Text Primary
┌──────────┐  #666666  Text Secondary
┌──────────┐  #999999  Text Muted
┌──────────┐  #FFFFFF  Background (cards)
┌──────────┐  #F5F5F5  Background (page)
┌──────────┐  #F8F9FA  Background (table header)

Status Colors:
┌──────────┐  #28a745  Success (Green)
┌──────────┐  #dc3545  Error (Red)
┌──────────┐  #ffc107  Warning (Yellow)
┌──────────┐  #17a2b8  Info (Blue)

Group Badge Colors:
┌──────────┐  #e3f2fd  Group A Background
┌──────────┐  #1976d2  Group A Text
┌──────────┐  #fff3e0  Group B Background
┌──────────┐  #f57c00  Group B Text
┌──────────┐  #f3e5f5  Group C Background
┌──────────┐  #7b1fa2  Group C Text
```

---

## Typography

```
Font Family:
-apple-system, BlinkMacSystemFont, 
'Segoe UI', Roboto, Oxygen, Ubuntu, 
Cantarell, sans-serif

Size Scale:
┌────────────────────────────────┐
│ Page Title       28px  bold    │
│ Section Title    22px  bold    │
│ Card Title       16px  600     │
│ Stat Value       26px  bold    │
│ Stat Label       12px  600     │
│ Body Text        14px  normal  │
│ Table Header     13px  600     │
│ Button Text      14px  500     │
│ Small Text       12px  normal  │
└────────────────────────────────┘
```

---

## Quick Reference

### Key CSS Classes

```css
.dashboard-layout     Main container (flexbox)
.sidebar              Fixed navigation panel
.sidebar-nav-item     Navigation button
.active               Currently selected state
.main-content         Scrollable content area
.top-header           Section title bar
.stats-grid           Statistics cards container
.stat-card            Individual stat card
.tabs                 Tab button container
.tab-btn              Tab button
.table-container      Table wrapper with shadow
.btn                  Base button class
.btn-primary          Red button
.btn-success          Green button
.btn-danger           Red danger button
.badge                Position group badge
.loading-overlay      Fullscreen spinner
.banner               Notification banner
.empty-state          No results message
.pagination           Page navigation
```

### Key JavaScript Functions

```javascript
switchSection(section)        Navigate sections
switchTab(tab)                Toggle active/archived
loadApplicants()              Fetch applicants table
loadDrafts()                  Fetch drafts table
archiveApplicant(id, name)    Archive operation
restoreApplicant(id, name)    Restore operation
deleteDraft(id)               Delete draft
loadDraft(id)                 Load draft to session
searchApplicants()            Apply filters
goToPage(page)                Pagination
updateStats(stats)            Update stat cards
showBanner(type, msg)         Show notification
showLoading()                 Show spinner
hideLoading()                 Hide spinner
toggleMobileMenu()            Mobile menu toggle
```

### AJAX Actions

```
Action              Method  Parameters
─────────────────────────────────────────
load_applicants     GET     tab, search, group, page
load_drafts         GET     (none)
archive             POST    id, reason
restore             POST    id
delete_draft        POST    id
load_draft          GET     id (redirects)
```

---

## Common Patterns

### Error Handling Pattern

```javascript
fetch(url)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Success path
            showBanner('success', data.message);
            // Update UI
        } else {
            // Error from server
            showBanner('error', data.message);
        }
    })
    .catch(error => {
        // Network error
        console.error('Error:', error);
        showBanner('error', 'Network error occurred');
    })
    .finally(() => {
        // Cleanup (always runs)
        hideLoading();
    });
```

### Confirmation Pattern

```javascript
function dangerousOperation(id, name) {
    if (confirm(`Are you sure you want to delete "${name}"?`)) {
        // User confirmed
        proceedWithOperation(id);
    } else {
        // User cancelled
        return;
    }
}
```

---

**Visual Guide Version**: 1.0  
**Last Updated**: January 31, 2025  
**For**: Single-Page Admin Dashboard
