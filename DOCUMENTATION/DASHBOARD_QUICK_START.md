# Quick Start: New Single-Page Admin Dashboard

🎉 **Welcome to the Modern Admin Dashboard!**

## What's New?

The admin dashboard has been completely redesigned as a **single-page application** with:

- ✨ **No page reloads** - Everything happens instantly via AJAX
- 🎯 **Sidebar navigation** - Quick access to all sections
- 📊 **Live statistics** - Real-time applicant counts
- 📱 **Mobile responsive** - Works great on all devices
- 🚀 **Smooth animations** - Modern, app-like experience

---

## How to Access

### Method 1: From Admin Index
1. Login as admin
2. Go to `/admin/index.php`
3. Click the **"Open New Dashboard"** button in the green banner

### Method 2: Direct Link
- Navigate directly to `/admin/dashboard.php`

---

## Using the Dashboard

### Navigation (Sidebar)

The sidebar on the left has three main sections:

1. **📊 Applicants** - Manage active and archived applicants
2. **📄 Saved Drafts** - View and load application drafts
3. **🏠 Return to Main** - Go back to evaluation system

Click any section to switch instantly - no page reload!

---

## Managing Applicants

### View Active Applicants

1. Dashboard opens to "Applicants" section by default
2. You'll see:
   - **Statistics cards** at top (Total, Active, Archived, Groups A/B/C)
   - **Active Applicants tab** selected
   - **Search box** and group filter
   - **Table** with all active applicants

### Search for Applicants

1. Type name in search box
2. Optionally select group (A, B, or C) from dropdown
3. Press **Enter** or click **Search** button
4. Results update instantly

### Archive an Applicant

1. Find applicant in the Active table
2. Click **Archive** button (red) in Actions column
3. Confirm the action
4. ✅ Success message appears
5. Applicant is removed from active list
6. Statistics update automatically

### View Archived Applicants

1. Click the **"Archived"** tab at the top
2. Table switches to show all archived applicants
3. You'll see the archive reason for each applicant

### Restore an Applicant

1. Make sure you're on **Archived** tab
2. Find applicant in the table
3. Click **Restore** button (green) in Actions column
4. Confirm the action
5. ✅ Success message appears
6. Applicant returns to active list
7. Statistics update automatically

### Navigate Pages

If there are many applicants:
- Use **page numbers** at bottom of table
- Click **Next/Previous** to move between pages
- Click **First/Last** to jump to ends

---

## Managing Drafts

### View Saved Drafts

1. Click **"Saved Drafts"** in sidebar
2. Table shows all saved application drafts
3. Sorted by last updated (newest first)

### Load a Draft

1. Find the draft you want to restore
2. Click **Load** button (blue)
3. System redirects to main evaluation form
4. Draft data is automatically filled in
5. Continue with your application

### Delete a Draft

1. Find draft you want to remove
2. Click **Delete** button (red)
3. Confirm the action
4. ✅ Draft is permanently deleted
5. Success message appears

---

## Mobile Usage

### Accessing on Mobile/Tablet

When you open the dashboard on a mobile device:

1. **Hamburger menu** (☰) appears in top-left corner
2. Sidebar is hidden by default to save screen space
3. Click hamburger to **slide in** the sidebar
4. Click outside sidebar to **close** it

### Everything Works the Same

All features work identically on mobile:
- Search and filter
- Archive and restore
- Load and delete drafts
- Pagination

Tables scroll horizontally if needed to show all columns.

---

## Understanding the Interface

### Statistics Cards

Six cards at the top show real-time counts:

| Card | Meaning |
|------|---------|
| **Total Applicants** | All applicants in system |
| **Active Applicants** | Currently active applicants |
| **Archived** | Archived applicants |
| **Group A** | Active applicants in Group A |
| **Group B** | Active applicants in Group B |
| **Group C** | Active applicants in Group C |

These update automatically after every operation!

### Tab System

**Active Applicants Tab**:
- Shows applicants you can evaluate
- Archive button available

**Archived Tab**:
- Shows archived applicants (hidden from main list)
- Restore button available

### Success/Error Messages

After every operation, you'll see a colored banner:

- 🟢 **Green banner** = Success! Operation completed
- 🔴 **Red banner** = Error! Operation failed
- 🟡 **Yellow banner** = Warning or information

Banners auto-hide after 5 seconds.

### Loading Spinner

When fetching data, you'll see a spinning circle overlay. This means:
- System is loading data from server
- Please wait a moment
- Operations are in progress

---

## Common Tasks

### Task: Find and Archive Multiple Applicants

1. Use search box to filter by name or group
2. Click Archive on each applicant you want to archive
3. Confirm each action
4. Statistics update after each archive

### Task: Check How Many Applicants in Group B

1. Look at the **"Group B"** stat card at the top
2. Number shows current count of active Group B applicants
3. OR: Select "Group B" from dropdown to filter table

### Task: Restore All Recently Archived Applicants

1. Switch to **Archived** tab
2. Recent archives appear at top (sorted by archive date)
3. Click **Restore** on each one
4. Confirm each action
5. Switch back to **Active** tab to see them

### Task: Load an Old Draft to Continue Application

1. Click **Saved Drafts** in sidebar
2. Find your draft by application code or date
3. Click **Load** button
4. System takes you to main form
5. Continue filling out application

---

## Tips & Tricks

### ⚡ Keyboard Shortcuts
- Press **Enter** in search box to search (no need to click button)

### 🎯 Quick Navigation
- Sidebar stays open - click between sections instantly
- No need to go back to index page

### 📊 Watch the Stats
- Stats update in real-time after operations
- Great way to verify actions completed

### 🔄 Refresh Data
- Click the **Refresh** button (↻) in top header
- Reloads current section with latest data

### 📱 Mobile Tip
- Tap anywhere outside sidebar to close it quickly
- Swipe tables left/right to see all columns

---

## Troubleshooting

### Problem: Tables Not Loading

**What to check**:
1. Are you logged in as admin?
2. Check browser console (F12) for errors
3. Verify database connection
4. Try refreshing the page (F5)

### Problem: Sidebar Stuck on Mobile

**Solution**:
1. Tap outside the sidebar to close it
2. Or refresh the page

### Problem: Search Not Working

**What to check**:
1. Make sure you're in the right tab (Active vs Archived)
2. Try clearing search and filtering again
3. Check if any applicants match your search term

### Problem: Operation Failed

**What to do**:
1. Note the error message in the red banner
2. Try the operation again
3. Check if applicant still exists
4. Verify your admin permissions

---

## FAQ

**Q: Can I use both the old applicants.php and new dashboard.php?**  
A: Yes! Both work simultaneously. The new dashboard is recommended for better experience.

**Q: Will my data be lost when switching sections?**  
A: No! The dashboard saves your search and filter settings.

**Q: Can multiple admins use the dashboard at the same time?**  
A: Yes, but changes won't sync in real-time. Click Refresh to see other admins' changes.

**Q: How do I get back to the old interface?**  
A: Visit `/admin/index.php` and click "Applicants Management" or "Saved Drafts" cards.

**Q: Is the dashboard mobile-friendly?**  
A: Absolutely! It's fully responsive and optimized for mobile devices.

---

## Getting Help

If you encounter issues:

1. Check the **error message** in the banner
2. Review the **troubleshooting section** above
3. Check **browser console** (F12 → Console tab)
4. Verify you're logged in as **admin**
5. Try **refreshing** the page

For technical documentation, see:
- `DOCUMENTATION/SINGLE_PAGE_DASHBOARD_IMPLEMENTATION.md`

---

## Summary

The new single-page dashboard makes admin tasks **faster and easier**:

✅ **No page reloads** - Everything happens instantly  
✅ **One-click navigation** - Switch between sections smoothly  
✅ **Real-time stats** - See counts update live  
✅ **Mobile friendly** - Works great on all devices  
✅ **Modern design** - Clean, professional interface  

**Ready to get started?** Visit `/admin/dashboard.php` now!

---

**Last Updated**: January 31, 2025  
**Version**: 1.0
