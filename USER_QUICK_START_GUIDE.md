# DTR Generator - Quick Start Guide (For Users)

## Welcome! 👋

This is a simple step-by-step guide to help you generate Daily Time Record (DTR) files quickly and easily.

---

## 🚀 5-Minute Quick Start

### What You'll Do
1. ✓ Pick which Excel files to process
2. ✓ Click one button
3. ✓ Download your DTR files
4. Done! ✨

### Time Required
- **Easy task**: 1-2 minutes
- **Many files**: 5-10 minutes
- **Troubleshooting**: 2-5 minutes

---

## Step 1: Open the System

**Go to:**
```
http://localhost/DEPEDEvaluationSystem/dtr_generator_redesigned.php
```

You'll see:
- **Top**: System name and branding
- **Below**: "Getting Started" guide with 4 steps
- **Middle**: File selection area
- **Bottom**: Your generated files

---

## Step 2: See What Files Are Available

### Look for This Section:
```
┌─────────────────────────────────────────┐
│ 1. SELECT FILES TO PROCESS              │
│ ✓ 5 available                           │
│                                          │
│ Book1.xlsx                              │
│ ✓ Auto-converted | 4 employees | 300KB  │
│                                          │
│ Data.xlsx                               │
│ 8 employees | 500KB                     │
│                                          │
│ [Search box]                            │
└─────────────────────────────────────────┘
```

### What It Shows
- **✓ Auto-converted**: File was automatically updated to new format
- **XX employees**: How many people in this file
- **Size**: File size in KB or MB

### Nothing Selected Yet?
That's OK! All files are unchecked by default.

---

## Step 3: Choose Your Files

### Option A: Process ALL Files
1. Click **"Select All"** button
2. See all checkboxes get checked ✓
3. Skip to Step 4

### Option B: Choose Specific Files
1. **Click the checkbox** ☑️ next to each file you want
2. Or **search first** to find files by name:
   - Type filename in search box
   - Click Search
   - Checkboxes appear only for matching files

### Option C: Clear All Selections
1. Click **"Clear All"** button
2. All checkboxes are unchecked
3. Start over

---

## Step 4: Generate DTRs

### Click the Big Button:
```
┌─────────────────────────────────────────┐
│                                          │
│      🔄 GENERATE DTRs                  │
│                                          │
└─────────────────────────────────────────┘
```

This big blue button:
- ✓ Is easy to see
- ✓ Is easy to click
- ✓ Clearly shows what it does

### What Happens Next
1. Button becomes disabled (might say "Processing...")
2. System processes your files (takes 5-30 seconds)
3. Success message appears: "✓ Processing complete!"

---

## Step 5: Download Your Files

### Look for This Section:
```
┌─────────────────────────────────────────┐
│ 2. DOWNLOAD GENERATED DTR FILES         │
│ ✓ 5 files                              │
│                                          │
│ Book1_DTR.xlsx         [Download] [Delete]
│ Data_DTR.xlsx          [Download] [Delete]
│                                          │
└─────────────────────────────────────────┘
```

### Download Files
1. Find your file in the list
2. Click **[Download]** button
3. File saves to your computer

### Delete Old Files
1. Click **[Delete]** button
2. You'll see: "Are you sure? OK / Cancel"
3. Click OK to confirm
4. File is removed from list

### Search for Files
1. Type in the search box
2. Click Search
3. Only matching files appear
4. Click Clear to see all again

---

## 📊 Dashboard Stats (Top)

You'll see 4 numbers at the top:

```
┌─────────────┬─────────────┬──────────────┬──────────────┐
│ Available   │   Total     │  Generated   │ DTR Template │
│   Files     │ Employees   │    Files     │              │
│     5       │     24      │      3       │ DTR-TEMPLATE │
│ 2 need help │ Across all  │ Ready to DL  │ Fixed file   │
└─────────────┴─────────────┴──────────────┴──────────────┘
```

- **Available Files**: How many Excel files you have
- **Total Employees**: Total number of people across all files
- **Generated Files**: DTR files you can download
- **DTR Template**: The standard template being used (doesn't change)

---

## 💬 Getting Help

### Built-in Help
You'll see help sections like:

```
┌────────────────────────────┐
│  💡 NEED HELP?            │
│                            │
│ ✓ Files auto-convert      │
│ ✓ Select multiple files   │
│ ⚡ Takes a few seconds    │
│ 📥 Download from Results  │
└────────────────────────────┘
```

Click on any section to see more details.

### Warning Messages (If Needed)

If a file can't be processed:

```
┌────────────────────────────┐
│ ⚠ FILES NEED ATTENTION    │
│                            │
│ 2 file(s) cannot be       │
│ processed. They may be     │
│ corrupted or in an old     │
│ format.                    │
│                            │
│ [Show Details] ↓          │
│                            │
│ • OldFile.xls - Can't     │
│   read format             │
└────────────────────────────┘
```

**What to Do:**
1. Click "Show Details"
2. You'll see which files have problems
3. These files are automatically skipped
4. You can:
   - Try other files first
   - Contact your administrator
   - Convert the file manually (ask for help)

---

## ✅ Success! You're Done

When you see a green message:
```
✓ Processing complete! 

Successfully generated DTRs for 5 file(s) 
with 24 total employees.
```

This means:
- ✓ Files were processed
- ✓ DTR files were created
- ✓ You can now download them

---

## 🛠️ Troubleshooting

### "No files available"
**Problem**: The file list is empty

**Solution**:
1. Ask your administrator to upload files to the system
2. Files should be in the "excel-files" folder
3. Files must be Excel (.xlsx or .xls format)

---

### "I selected files but nothing happened"
**Problem**: The Generate button isn't working

**Solution**:
1. Check at least ONE checkbox is selected
2. Make sure all selected files have a ✓ checkmark
3. Click "Select All" to try again
4. Refresh the page and try again

---

### "Some files show a warning ⚠"
**Problem**: Some files couldn't be auto-converted

**Solution**:
1. Click "Show Details" to see which files
2. These files are skipped (won't be processed)
3. Process the remaining good files first
4. Ask administrator to convert problem files

**For Administrators:**
- Files are in old .xls format
- They need conversion to .xlsx
- Use Excel or LibreOffice to convert
- [See conversion guide](AUTO_CONVERSION_GUIDE.md)

---

### "Download button doesn't work"
**Problem**: File won't download

**Solution**:
1. Check your browser download settings
2. Try a different browser
3. Check your firewall/antivirus
4. Contact the administrator

---

### "I need to delete files"
**Problem**: Generated files are taking up space

**Solution**:
1. Find file in "Generated Files" section
2. Click [Delete] button
3. Confirm when asked
4. File is removed (can't be undone!)

**Warning**: Be careful! Deleted files cannot be recovered.

---

## 🎯 Common Tasks

### Generate DTRs for All Files
```
1. Click [Select All]
2. Click [Generate DTRs]
3. Wait for result
4. Done!
```
**Time**: 2 minutes

### Generate DTRs for One Department
```
1. Find file for that department
2. Click checkbox next to it
3. Click [Generate DTRs]
4. Done!
```
**Time**: 1 minute

### Find a Specific Generated File
```
1. Use search: "employee name"
2. Click [Search]
3. Only matching files appear
4. Click [Download]
```
**Time**: 30 seconds

### Clean Up Old Files
```
1. Scroll to "Generated Files" section
2. Click [Delete] on old files
3. Confirm each deletion
4. Done!
```
**Time**: 2 minutes per 10 files

---

## 📋 Checklist Before Starting

Before you begin, make sure:
- ✓ You have permission to access this system
- ✓ Excel files are in the system (ask admin if not)
- ✓ You know how many files to expect
- ✓ You have time (might take a few minutes)
- ✓ Your internet connection is stable

---

## 🤔 Frequently Asked Questions

### Q: Can I process multiple files at once?
**A**: Yes! Select multiple files with checkboxes, then click Generate.

### Q: How long does it take?
**A**: 
- 1 small file: ~10 seconds
- 5 files: ~30 seconds
- 10 files: ~1-2 minutes

### Q: What if I click Generate by accident?
**A**: Just stop it! The system is safe - it won't delete anything. You can regenerate anytime.

### Q: Can I change the DTR template?
**A**: No, the template is fixed by the administrator. Contact them if you need a different template.

### Q: Where are the generated files saved?
**A**: In the "output" folder. You can download them from the system.

### Q: Can I delete files?
**A**: Yes, but only from the "Generated Files" section. Be sure you don't need them first!

### Q: What if I can't find my file?
**A**: Use the search box! Type part of the filename and click Search.

---

## 👤 Admin? See These Guides:

- [DTR_GENERATOR_REDESIGN_GUIDE.md](DTR_GENERATOR_REDESIGN_GUIDE.md) - Full technical guide
- [AUTO_CONVERSION_GUIDE.md](AUTO_CONVERSION_GUIDE.md) - File conversion help
- [UI_UX_COMPARISON.md](UI_UX_COMPARISON.md) - What changed & why

---

## 📞 Still Need Help?

**For Users:**
1. Check the [Getting Started] section on the page
2. Read this guide again (it covers most issues)
3. Look for help icons (💡) on the page
4. Contact your administrator

**For Administrators:**
1. Read [DTR_GENERATOR_REDESIGN_GUIDE.md](DTR_GENERATOR_REDESIGN_GUIDE.md)
2. Check PHP error logs for technical issues
3. Test with a small set of files first
4. Enable debug logging if needed

---

## 🎉 You're Ready!

You now know:
- ✓ How to access the system
- ✓ How to select files
- ✓ How to generate DTRs
- ✓ How to download results
- ✓ How to troubleshoot issues

**Go ahead and try it!** The system is designed to be safe - you can't break anything.

---

**Last Updated**: March 5, 2026  
**Version**: 3.0  
**Language**: Simple, Non-Technical  
**Audience**: End Users, Non-Technical Staff
