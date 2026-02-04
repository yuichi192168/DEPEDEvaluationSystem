# Admin Applicants Management Dashboard - Visual Guide

## [DASHBOARD] Dashboard Layout

```
┌─────────────────────────────────────────────────────────────┐
│  DepEd HRMPSB Applicants Management Dashboard              │
│  Manage all applicants efficiently with archiving features  │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ Statistics                                                   │
├─────────────┬─────────────┬──────────────┬──────┬──────┬────┤
│ Total: 1500 │ Active: 1000│ Archived: 500│ Grp A│ Grp B│Grp C│
│             │             │              │ 300  │ 350  │ 350 │
└─────────────┴─────────────┴──────────────┴──────┴──────┴────┘

┌─────────────────────────────────────────────────────────────┐
│ TABS:  [Active Applicants (1000)]  [Archived (500)]        │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ CONTROLS:  [Search Box] [Group Filter ▼] [Search] [Reset]  │
│            [Bulk Archive] [Bulk Restore]                    │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ Bulk Selection: 0 selected  [Archive] [Restore] [Clear]    │
└─────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│ TABLE:                                                       │
│ ┌─┬──────────────┬──────┬────────────┬──────────┬──────────┐
│ │☐│ Name         │Group │ Position   │  Date    │ Actions  │
│ ├─┼──────────────┼──────┼────────────┼──────────┼──────────┤
│ │☐│John Doe      │ Grp A│ Admin Off. │ 2/1/2026 │View|Arch │
│ │☐│Maria Santos  │ Grp B│ Principal  │ 2/2/2026 │View|Arch │
│ │☐│Jose Garcia   │ Grp C│ Specialist │ 2/3/2026 │View|Arch │
│ └─┴──────────────┴──────┴────────────┴──────────┴──────────┘
│ Showing 1-25 of 1000 | Pages: [1] 2 3 4 ...                │
└─────────────────────────────────────────────────────────────┘
```

## [WORKFLOW] User Flow Diagrams

### Archive Workflow
```
┌─────────────────┐
│ Active List     │
│ Select Applicant│
└────────┬────────┘
         │
         ▼
    ┌─────────┐
    │ Archive?│
    │ [Modal] │
    └────┬────┘
         │
    Enter Reason
    (Optional)
         │
         ▼
    ┌──────────────┐
    │ Confirm      │
    │ Archive Now  │
    └────┬─────────┘
         │
         ▼
    ┌──────────────────┐
    │ [SUCCESS]        │
    │ Applicant        │
    │ Archived         │
    └────┬─────────────┘
         │
         ▼
    ┌──────────────────┐
    │ Archived List    │
    │ Applicant Now    │
    │ Visible Here     │
    └──────────────────┘
```

### Restore Workflow
```
┌──────────────────┐
│ Archived List    │
│ Select Applicant │
└────────┬─────────┘
         │
         ▼
    ┌──────────────┐
    │ Restore?     │
    │ [Modal]      │
    └────┬─────────┘
         │
         ▼
    ┌──────────────┐
    │ Confirm      │
    │ Restore Now  │
    └────┬─────────┘
         │
         ▼
    ┌──────────────────┐
    │ ✓ Success        │
    │ Applicant        │
    │ Restored         │
    └────┬─────────────┘
         │
         ▼
    ┌──────────────────┐
    │ Active List      │
    │ Applicant Now    │
    │ Visible Here     │
    └──────────────────┘
```

## 📱 Responsive Design Breakpoints

### Desktop View (1024px+)
```
Full featured interface
Grid layout for statistics
Wide tables with all columns
Side-by-side layouts
Full feature set
```

### Tablet View (768px - 1023px)
```
Optimized grid
Stacked on smaller items
Touch-friendly buttons
Full functionality
Readable text
```

### Mobile View (<768px)
```
Single column layout
Stacked statistics
Scrollable tables
Large buttons
Optimized for touch
Full feature access
```

## 🎨 Color Coding

### Status Colors
```
🟢 Green (Group A)    - #d4edda background, #155724 text
🔵 Blue (Group B)     - #d1ecf1 background, #0c5460 text
🟡 Yellow (Group C)   - #fff3cd background, #856404 text
⚫ Gray (Archived)     - #e2e3e5 background, #383d41 text
🔴 Red (Archive)      - #E04040 color for primary actions
```

### UI Elements
```
Header: Linear gradient (#E04040 → #E06060)
Cards: White with left border (#E04040)
Buttons: 
  - Primary (Archive): Red (#E04040)
  - Secondary: Gray (#f0f0f0)
  - Success (Restore): Green (#28a745)
  - Danger: Red (#dc3545)
```

## 📋 Modal Dialogs

### Archive Modal
```
┌─────────────────────────────────┐
│ ✕ Archive Applicant             │
├─────────────────────────────────┤
│                                 │
│ Archive "John Doe"?             │
│                                 │
│ [Reason for archiving (optional)]
│ [__________________________]     │
│                                 │
├─────────────────────────────────┤
│ [Cancel] [📦 Archive Now]        │
└─────────────────────────────────┘
```

### Restore Modal
```
┌─────────────────────────────────┐
│ ✕ Restore Applicant             │
├─────────────────────────────────┤
│                                 │
│ Restore "Maria Santos"?         │
│                                 │
│ This applicant will be moved    │
│ back to the active list.        │
│                                 │
├─────────────────────────────────┤
│ [Cancel] [↩️ Restore]            │
└─────────────────────────────────┘
```

### Details Modal
```
┌──────────────────────────────────────┐
│ ✕ Applicant Details                  │
├──────────────────────────────────────┤
│                                      │
│ Name: John Doe                       │
│ Group: A  | Position: Admin Officer │
│ Status: Active                       │
│ Created: Jan 22, 2026                │
│                                      │
│ Archive History:                     │
│ ┌────────────────────────────────┐  │
│ │📦 Archived - Feb 2, 2026      │  │
│ │   Reason: Hired               │  │
│ │                               │  │
│ │↩️ Restored - Feb 3, 2026      │  │
│ │   Reason: Reapplication       │  │
│ └────────────────────────────────┘  │
│                                      │
└──────────────────────────────────────┘
```

## 🔄 Data Flow Architecture

```
┌──────────────────┐
│ Admin Interface  │
│ (admin/app.php)  │
└────────┬─────────┘
         │
         │ AJAX Requests
         │ (JSON)
         ▼
┌──────────────────┐
│ API Endpoints    │
│ (/api/*.php)     │
└────────┬─────────┘
         │
         │ Database Operations
         │ (Prepared Statements)
         ▼
┌──────────────────┐
│ MySQL Database   │
│ (applicants +    │
│  audit tables)   │
└──────────────────┘
```

## 📊 Statistics Update Flow

```
┌─────────────────┐
│ Admin Loads     │
│ Dashboard       │
└────────┬────────┘
         │
         │ Fetch Stats
         ▼
    /api/get_applicant_stats.php
         │
    SELECT COUNT(*)
    FROM applicants
    WHERE archive_status = 'active'
    GROUP BY position_group
         │
         ▼
    Calculate:
    - Total Active: 1000
    - Total Archived: 500
    - Group A: 300
    - Group B: 350
    - Group C: 350
         │
         ▼
    Update Cards:
    [1000]  [500]  [300] [350] [350]
```

## 🗂️ File Hierarchy

```
Admin System
├── admin/
│   ├── index.php                 (Dashboard home)
│   ├── applicants.php            (Main dashboard)
│   ├── drafts.php                (Drafts management)
│   └── README.md
├── classes/
│   └── ApplicantManager.php      (Business logic)
├── api/
│   ├── archive_applicant.php
│   ├── restore_applicant.php
│   ├── get_applicants.php
│   ├── get_applicant_details.php
│   ├── get_applicant_stats.php
│   └── bulk_archive_applicants.php
├── database/
│   └── migration_add_archiving.sql
└── Documentation/
    ├── APPLICANTS_DASHBOARD_GUIDE.md
    ├── APPLICANTS_DASHBOARD_SETUP.md
    ├── QUICK_REFERENCE_CARD.md
    ├── IMPLEMENTATION_SUMMARY.md
    └── VISUAL_GUIDE.md (this file)
```

## 🧩 Component Breakdown

```
┌─────────────────────────────────────────┐
│ HEADER                                  │
│ [Title] [Subtitle]                      │
└─────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│ STATISTICS SECTION                      │
│ [Stat1] [Stat2] [Stat3] [Stat4]        │
└─────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│ TABS                                    │
│ [Active Tab]     [Archived Tab]        │
└─────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│ CONTROLS                                │
│ [Search] [Filter] [Buttons]            │
└─────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│ BULK ACTION BAR (if selected)           │
│ [Count] [Archive] [Clear]               │
└─────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│ DATA TABLE                              │
│ [Checkbox] [Name] [Group] [Action]     │
│ ├─ Row 1 ────────────────────────      │
│ ├─ Row 2 ────────────────────────      │
│ └─ Row 3 ────────────────────────      │
└─────────────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│ PAGINATION                              │
│ [<] [1] [2] [3] [>]                     │
└─────────────────────────────────────────┘
```

## 🎯 User Journey Map

```
┌─────────────────────────────────────────────────────────────┐
│                    USER JOURNEY                              │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ENTRY POINT                    ACTIVE TASKS                 │
│  /admin/index.php      ────►    Search                       │
│         │                       Filter                       │
│         └─ Applicants   ────►   View Details                 │
│            Dashboard             Archive                      │
│                                 Bulk Operations              │
│                                        │                      │
│                                        ▼                      │
│                         ┌──────────────────────┐             │
│                         │ ARCHIVED APPLICANTS  │             │
│                         │ Tab                  │             │
│                         ├──────────────────────┤             │
│                         │ - View History       │             │
│                         │ - Restore            │             │
│                         │ - Bulk Restore       │             │
│                         └──────────────────────┘             │
│                                                               │
│  ADMIN TASKS FLOW:                                           │
│  1. Check Statistics   ────────────────────────┐             │
│  2. Search Applicant   ◄─────────────────────┐ │             │
│  3. Review Details     ┌─────────────────┐   │ │             │
│  4. Archive (if done)  │  Final Decision │ ──┤ │             │
│  5. Log Reason         └─────────────────┘   │ │             │
│  6. Confirm Action     ◄─────────────────────┘ │             │
│  7. View Updated Stats ◄─────────────────────── │             │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

## 📈 Scaling Visualization

```
Number of Applicants vs Performance

│
│ 5000+  ●  (Archive half, keep 2500 active)
│        │
│        │
│ 2500+  ●  (Active list getting large)
│        │\ 
│        │ \ (Archive to maintain performance)
│ 1500+  ●  \
│        │   \
│        │    ●  (Optimal range, good UI responsiveness)
│ 1000+  ├────●─────────────────── (Sweet spot)
│        │         \
│ 500+   ●          \
│        │           ●  (Small, new system)
│        │
│ 0      └────────────────────────────────
│        Time ──────────────────►

Strategy:
- Keep 500-1500 active
- Archive older/completed
- Search archived when needed
- Bulk operations for batches
```

## 🔐 Security Flow

```
┌────────────┐
│ User Input │
└────┬───────┘
     │
     ▼
┌──────────────────┐
│ Input Validation │
└────┬─────────────┘
     │
     ▼
┌──────────────────────┐
│ Prepared Statement   │
│ (Parameterized Query)│
└────┬─────────────────┘
     │
     ▼
┌──────────────────┐
│ Database Execute │
└────┬─────────────┘
     │
     ▼
┌──────────────────┐
│ Audit Log Entry  │
└────┬─────────────┘
     │
     ▼
┌──────────────────┐
│ JSON Response    │
└────┬─────────────┘
     │
     ▼
┌──────────────────┐
│ UI Update        │
└──────────────────┘
```

## 📊 Database Schema Visualization

### Applicants Table Changes
```
applicants
├── id [PK]
├── name
├── position_applied_id [FK]
├── position_group
├── created_at
├── updated_at
├── archive_status    ← NEW (active/archived)
├── archived_at       ← NEW (timestamp)
└── archive_reason    ← NEW (varchar)
```

### New Audit Table
```
archived_applicants_audit
├── id [PK]
├── applicant_id [FK] ─────► applicants.id
├── applicant_name
├── action (archived/restored)
├── reason
├── archived_by
├── archived_at
└── notes
```

---

## 🎓 Quick Visual Reference

### Icons Used in Dashboard
```
📋  - Active/Lists
📦  - Archive/Store
↩️   - Restore/Return
👁️  - View/Details
🔍  - Search
↻   - Refresh/Reset
✕   - Close/Cancel
☐   - Checkbox/Select
🔧  - Admin/Settings
```

### Button Combinations
```
[View] + [Archive]     = Manage active
[View] + [Restore]     = Manage archived
[Bulk] + [Archive]     = Quick archiving
[Bulk] + [Restore]     = Quick restoring
[Search] + [Filter]    = Find specific
[Reset]                = Clear all
```

### Status Indicators
```
Active    = Green indicator, "📋 Active" tab
Archived  = Gray indicator, "📦 Archived" tab
Group A   = Green badge
Group B   = Blue badge
Group C   = Yellow badge
```

---

This visual guide helps understand the applicants dashboard at a glance!

For detailed information, see the full documentation files.

**Last Updated**: February 2026 | **Version**: 1.0
