# Admin Applicants Dashboard - Quick Reference Card

## 🚀 Quick Start

### Access the Dashboard
- URL: `http://yoursite.com/admin/index.php`
- Then click: **"Applicants Management" → "Open Dashboard"**
- Or direct: `http://yoursite.com/admin/applicants.php`

---

## 📋 Main Tasks at a Glance

### Task 1: Search for an Applicant
```
1. Enter name in search box (partial match OK)
2. Press Enter or click Search
3. Results show immediately
```

### Task 2: Archive an Applicant
```
1. Find applicant in Active tab
2. Click "📦 Archive" button
3. Enter reason (optional): "Hired", "Withdrew", etc.
4. Click "📦 Archive"
5. Applicant moves to Archived tab
```

### Task 3: Restore an Applicant
```
1. Go to "Archived Applicants" tab
2. Find applicant
3. Click "↩️ Restore" button
4. Confirm action
5. Applicant returns to Active tab
```

### Task 4: View Applicant Details
```
1. Click "👁️ View" button on any applicant
2. See all information in popup
3. See archive history
4. Close by clicking X
```

### Task 5: Bulk Archive Multiple Applicants
```
1. Select applicants using checkboxes
2. Click "📦 Bulk Archive" button
3. Enter common reason
4. Click "📦 Archive All"
5. All selected applicants archived
```

### Task 6: Bulk Restore Multiple Applicants
```
1. Go to Archived tab
2. Select applicants using checkboxes
3. Click "↩️ Bulk Restore" button
4. Confirm action
5. All selected applicants restored
```

### Task 7: Filter by Position Group
```
1. Open Group dropdown
2. Select: A, B, or C
3. See only that group
4. Can combine with search
```

---

## 📊 What the Dashboard Shows

### Statistics Cards (Top of Page)
- **Total Applicants** - All active + archived
- **Active Applicants** - Only active candidates
- **Archived Applicants** - Only archived candidates
- **Group A/B/C** - Count in each position group

### Tabs
| Tab | Shows | Can Do |
|-----|-------|--------|
| 📋 Active | Active applicants | Archive, View |
| 📦 Archived | Archived applicants | Restore, View |

### Table Columns
| Column | Shows |
|--------|-------|
| ☐ | Checkbox to select |
| Name | Applicant name (clickable) |
| Group | Position group (A, B, or C) |
| Position | Position applied for |
| Date | Created or Archived date |
| Reason* | Why archived (*archived tab only) |
| Actions | View, Archive/Restore buttons |

---

## 🔍 Search & Filter Tips

### Search Examples
```
✅ "John" → finds all Johns
✅ "Maria" → finds all Marias
✅ "Jo" → finds John, Jona, Jose, etc.
```

### Filter Examples
```
✅ Show only Group A applicants
✅ Show only Group B applicants
✅ Show only Group C applicants
✅ Show all groups (default)
```

### Combined Usage
```
✅ Search "John" + Filter "Group A"
   = Shows only John in Group A
✅ Search "Maria" + Filter "Group B"
   = Shows only Maria in Group B
```

---

## ⌨️ Keyboard Shortcuts

| Action | How |
|--------|-----|
| Search | Type name + Press Enter |
| Select all | Check top checkbox |
| Deselect all | Uncheck top checkbox |
| Open details | Click applicant name |
| Close modal | Press Escape |

---

## 💡 Common Workflows

### Workflow A: End of Day Processing
```
1. Search for all applicants not yet evaluated
2. Review each one (click View)
3. Archive completed evaluations
4. Mark as "Evaluated" in reason
5. Next day start fresh with remaining
```

### Workflow B: Batch Hiring
```
1. Get list of hired applicants
2. Select all hired applicants
3. Bulk archive them
4. Enter reason "Hired"
5. Clean active list for next batch
```

### Workflow C: Managing Large List
```
Current: 5000 applicants (too many!)
1. Archive old applicants → "Previous batch"
2. Archive non-matching → "Doesn't meet criteria"
3. Keep only current batch active
4. Now: 200 active (manageable!)
5. Can still find archived when needed
```

### Workflow D: Applicant Withdrawal
```
1. Get withdrawal notification email
2. Search applicant name
3. Click Archive
4. Reason: "Withdrew Application"
5. Applicant archived with reason
6. Full audit trail preserved
```

---

## 🎯 Button Reference

### Action Buttons

| Button | Location | Does |
|--------|----------|------|
| 👁️ View | Each row | Show applicant details |
| 📦 Archive | Active tab rows | Archive one applicant |
| ↩️ Restore | Archived tab rows | Restore one applicant |
| 📦 Bulk Archive | Top (if selected) | Archive multiple at once |
| ↩️ Bulk Restore | Top (if selected) | Restore multiple at once |
| ✕ Clear Selection | Top (if selected) | Deselect all applicants |
| 🔍 Search | Control bar | Execute search |
| ↻ Reset | Control bar | Clear search/filters |

---

## ⚙️ Settings & Controls

### Results Per Page
- Current setting: 25 applicants per page
- Change in: `admin/applicants.php` (line ~18)
- Larger number = slower page load
- Smaller number = more page clicks

### Archive Reason Examples
```
Hired - Applicant was hired for position
Rejected - Did not meet qualifications
Withdrew - Applicant withdrew application
Duplicate - Duplicate application
Completed - Evaluation completed
On hold - Temporarily archiving
Other - Custom reason
```

---

## ⚠️ Important Notes

### Before Using
- [ ] Database migration executed?
- [ ] Migration script run successfully?
- [ ] New tables visible in database?
- [ ] No error messages on dashboard?

### During Use
- ✅ Archive reason optional but recommended
- ✅ Archived applicants still searchable
- ✅ Can restore anytime without data loss
- ✅ All actions are logged with date/time
- ✅ No applicants are deleted (just archived)

### After Use
- ✅ Check statistics to verify counts
- ✅ Review archive history in details modal
- ✅ Confirm restoration if needed
- ✅ Monitor active list size

---

## 🔒 What Happens to Data

### When You Archive
```
Applicant Status: active → archived
Archive Date: Set to current date/time
Archive Reason: Saved for audit
Evaluation Data: Preserved
History: Logged in audit table
Can Restore: Yes, anytime
```

### When You Restore
```
Applicant Status: archived → active
Archive Date: Cleared
Archive Reason: Cleared
Evaluation Data: Unchanged
History: Logged as "restored"
Can Archive Again: Yes
```

### Data Never
```
❌ Deleted permanently
❌ Lost or corrupted
❌ Visible to public
❌ Shared externally
✅ Always recoverable
✅ Always audited
```

---

## 🆘 Quick Troubleshooting

### Problem: Can't find applicant
**Solution**: 
- Try partial name (e.g., "Jo" instead of "John")
- Check spelling carefully
- Applicant might be archived (check both tabs)
- Refresh page (Ctrl+F5)

### Problem: Archive button doesn't work
**Solution**:
- Check browser console (F12 → Console)
- Verify database migration was run
- Try refreshing the page
- Check for JavaScript errors

### Problem: Pagination shows wrong count
**Solution**:
- Try different search/filter
- Refresh page
- Check if search criteria are set
- Try resetting filters

### Problem: Bulk operations slow
**Solution**:
- Reduce number of selections
- Try smaller batches
- Wait for completion
- Larger datasets take more time

### Problem: Archive history not showing
**Solution**:
- Try different applicant
- Refresh page
- Archive something new first
- Check database migration status

---

## 📞 Getting Help

### For Feature Questions
- Read: `APPLICANTS_DASHBOARD_GUIDE.md`
- Section: "Usage Guide"

### For Setup Issues
- Read: `APPLICANTS_DASHBOARD_SETUP.md`
- Section: "Installation & Setup"

### For Technical Help
- Read: `APPLICANTS_DASHBOARD_GUIDE.md`
- Section: "Troubleshooting"

### For Custom Modifications
- Read: `IMPLEMENTATION_SUMMARY.md`
- Section: "Technical Architecture"

---

## 📱 Mobile Usage

### On Smartphone
- Tap to select applicants
- Scroll tables horizontally
- Modals optimized for small screens
- All features fully available
- Touch-friendly buttons

### On Tablet
- Same as desktop
- Landscape view recommended
- All features available
- Comfortable screen size

---

## ✅ Daily Checklist

```
Morning:
☐ Check statistics for new applicants
☐ Review archived applicants from yesterday
☐ Plan today's archiving needs

During Day:
☐ Archive completed evaluations
☐ Search for specific applicants as needed
☐ View details when questions arise
☐ Use bulk operations for batches

End of Day:
☐ Archive remaining processed applicants
☐ Note active applicants remaining
☐ Verify archive reasons entered
☐ Close browser securely
```

---

## 🎓 Training Time

- **First Use**: 5-10 minutes to explore
- **Basic Operations**: 15 minutes to learn
- **Advanced Usage**: 30 minutes total
- **Proficiency**: Same day with practice
- **Expert**: Few days with regular use

---

## 🔐 Security Reminders

- ✅ Log out when finished
- ✅ Don't share your login
- ✅ Archive reason important for audit trail
- ✅ Double-check before bulk operations
- ✅ Review archive history regularly

---

## 📊 Quick Stats Interpretation

```
Total: 1000
├─ Active: 750 (Good working set)
└─ Archived: 250 (Historical records)

Breakdown:
├─ Group A: 250 active
├─ Group B: 300 active
└─ Group C: 200 active
```

**Healthy Size**:
- Active: 500-1000 (manageable)
- Archived: Growing (normal)
- Groups: Roughly balanced

**If Too Large**:
- Archive more applicants
- Use aggressive filtering
- Archive by date ranges

---

## 🚀 Pro Tips

### Tip 1: Archive with Reason
Always enter a reason - helps remember why later

### Tip 2: Use Search Before Archive
Find specific applicants rather than scrolling

### Tip 3: Batch Similar Applicants
Archive groups with same status together

### Tip 4: Check Details First
View applicant info before archiving

### Tip 5: Keep Notes
Document major archiving decisions

### Tip 6: Regular Backups
Database backups protect your data

### Tip 7: Review Weekly
Check archived applicants weekly

### Tip 8: Use Filters
Narrow down before bulk operations

---

## 📋 Checklist for New Admins

```
Getting Started:
☐ Read this Quick Reference
☐ Read full User Guide
☐ Watch demo/training
☐ Practice on test data
☐ Ask questions
☐ Get supervisor approval

Daily Tasks:
☐ Check statistics
☐ Review new applicants
☐ Archive completed work
☐ Respond to inquiries
☐ Verify data integrity

Weekly Tasks:
☐ Review archived applicants
☐ Check database size
☐ Verify backup was done
☐ Generate report if needed
☐ Update team on status

Monthly Tasks:
☐ Deep review of archive
☐ Cleanup any issues
☐ Plan next month
☐ Report metrics
☐ Train if needed
```

---

**Last Updated**: February 2026 | **Version**: 1.0 | **System**: DepEd HRMPSB Evaluation System v2.0

For detailed information, refer to the full documentation files.
