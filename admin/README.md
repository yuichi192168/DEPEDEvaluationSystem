# Admin Panel - DepEd HRMPSB Evaluation System

Welcome to the Admin Panel of the DepEd HRMPSB Evaluation System. This folder contains administrative tools for managing applicants, drafts, and system features.

## 📁 Folder Contents

### Admin Pages
- **`index.php`** - Admin Dashboard Home
  - Navigation hub for all admin features
  - Quick access to applicants management and drafts
  - Feature overview and setup instructions
  - Links to all admin tools

- **`applicants.php`** - Applicants Management Dashboard
  - Complete applicant management interface
  - Search, filter, and archive applicants
  - View applicant details and history
  - Bulk operations support
  - Real-time statistics

- **`drafts.php`** - Saved Drafts Management
  - View all saved application drafts
  - Load drafts back into the system
  - Delete old drafts

## 🚀 Getting Started

### First Time Setup
1. Navigate to `/admin/index.php`
2. Read the setup instructions
3. Execute the database migration script
4. Access the Applicants Management Dashboard

### Quick Access URLs
```
Admin Home:              /admin/index.php
Applicants Dashboard:    /admin/applicants.php
Saved Drafts:           /admin/drafts.php
Main Evaluation System: /index.php
```

## 🎯 Main Features

### Applicants Management Dashboard
The primary tool in this admin panel provides:

✅ **Applicant Viewing**
- View active applicants in organized table
- Real-time statistics and counts
- Responsive design for all devices
- Pagination for large datasets

✅ **Search & Filter**
- Search by applicant name
- Filter by position group (A, B, C)
- Combined search + filter functionality
- Instant results

✅ **Archive Management**
- Archive individual applicants
- Archive in bulk with one click
- Optional archive reasons
- Automatic timestamp recording

✅ **Restoration**
- Restore archived applicants
- Restore in bulk
- Complete history preservation
- No data loss

✅ **History & Audit**
- View complete archive history
- Automatic audit logging
- Reason tracking
- Timestamp recording

## 📊 Dashboard Statistics

The applicants dashboard provides real-time statistics:
- Total applicants (active + archived)
- Active applicants count
- Archived applicants count
- Distribution by position group (A, B, C)

## 🔐 Security

- Session-based access
- Input validation
- Prepared statements (SQL injection prevention)
- Audit logging of all operations
- Data integrity protection

## 📚 Documentation

Comprehensive documentation is provided:

### Setup & Installation
- `APPLICANTS_DASHBOARD_SETUP.md` - Step-by-step setup guide
- `APPLICANTS_DASHBOARD_GUIDE.md` - Complete user guide
- `QUICK_REFERENCE_CARD.md` - Quick reference for daily use
- `IMPLEMENTATION_SUMMARY.md` - Technical overview

### Database
- `database/migration_add_archiving.sql` - Database migration script
- Adds archiving tables and columns
- Creates audit logging table

### Code
- `classes/ApplicantManager.php` - Core manager class
- `api/archive_applicant.php` - Archive API
- `api/restore_applicant.php` - Restore API
- `api/get_applicants.php` - Get applicants API
- `api/get_applicant_details.php` - Details API
- `api/get_applicant_stats.php` - Statistics API
- `api/bulk_archive_applicants.php` - Bulk archive API

## ⚙️ Configuration

### Default Settings
- Items per page: 25 (editable in applicants.php)
- Default tab: Active applicants
- Database table: applicants with archive columns

### Customization
See `APPLICANTS_DASHBOARD_GUIDE.md` for:
- Adjusting items per page
- Adding authentication
- Customizing appearance
- Adding new features

## 🧪 Testing

Before using in production:

1. ✅ Run database migration
2. ✅ Test search functionality
3. ✅ Test archive/restore
4. ✅ Test bulk operations
5. ✅ Verify audit logging
6. ✅ Check statistics accuracy
7. ✅ Test on mobile devices
8. ✅ Verify all links work

See `APPLICANTS_DASHBOARD_SETUP.md` for detailed testing checklist.

## 🚀 Deployment

### Prerequisites
- PHP 5.6+
- MySQL/MariaDB database
- Web server (Apache/Nginx)
- Database migration executed

### Installation Steps
1. Copy files to your web server
2. Execute database migration
3. Update database credentials if needed
4. Test all features
5. Set up access controls
6. Start using

### Post-Deployment
- Monitor performance
- Review audit logs
- Back up database regularly
- Train administrators

## 📞 Support

### For Installation Issues
→ See `APPLICANTS_DASHBOARD_SETUP.md`

### For Usage Questions
→ See `APPLICANTS_DASHBOARD_GUIDE.md`

### For Quick Reference
→ See `QUICK_REFERENCE_CARD.md`

### For Technical Details
→ See `IMPLEMENTATION_SUMMARY.md`

## 🔄 Regular Maintenance

### Daily
- Check statistics for new applicants
- Review any archived applicants
- Archive completed evaluations

### Weekly
- Review archived applicants
- Verify database size
- Check audit logs

### Monthly
- Full database backup
- Performance review
- Archive cleanup
- Metrics reporting

## 🎓 Training

### For Administrators
1. Read `QUICK_REFERENCE_CARD.md`
2. Follow `APPLICANTS_DASHBOARD_GUIDE.md`
3. Practice on test data
4. Review best practices

### For Developers
1. Review `IMPLEMENTATION_SUMMARY.md`
2. Study `ApplicantManager.php` class
3. Examine API endpoints
4. Review database schema

## 📊 Features Checklist

### Core Features
- [x] View active applicants
- [x] View archived applicants
- [x] Search applicants
- [x] Filter by position group
- [x] Archive individuals
- [x] Archive in bulk
- [x] Restore individuals
- [x] Restore in bulk
- [x] View applicant details
- [x] View archive history
- [x] Real-time statistics
- [x] Audit logging
- [x] Pagination
- [x] Responsive design

### API Endpoints
- [x] Archive applicant
- [x] Restore applicant
- [x] Get applicants list
- [x] Get applicant details
- [x] Get statistics
- [x] Bulk archive

### Documentation
- [x] Setup guide
- [x] User guide
- [x] Quick reference
- [x] API documentation
- [x] Implementation summary
- [x] This README

## 🎉 What's New

### Version 1.0 - Initial Release
- Complete applicants management dashboard
- Archive/restore functionality
- Search and filtering
- Bulk operations
- Audit logging
- Real-time statistics
- Responsive design
- Comprehensive documentation
- 6 REST API endpoints
- 1 Manager class
- Complete database migration

## 🌟 Highlights

⭐ **User-Friendly Interface**
- Clean, intuitive design
- Easy navigation
- Clear action buttons
- Helpful information displays

⭐ **Powerful Features**
- Search and filter
- Archive/restore
- Bulk operations
- Complete history

⭐ **Secure & Reliable**
- Prepared statements
- Input validation
- Audit logging
- No data loss

⭐ **Well Documented**
- Setup guides
- User guides
- API documentation
- Quick reference
- Best practices

⭐ **Production Ready**
- Tested functionality
- Error handling
- Performance optimized
- Mobile responsive

## 📝 License & Credits

DepEd HRMPSB Evaluation System
© 2026

## 🔗 Related Resources

### In This Project
- Main System: `/index.php`
- Database: `database/`
- Classes: `classes/ApplicantManager.php`
- API: `api/`
- Documentation: Root folder `*.md` files

### External Resources
- MySQL Documentation
- PHP Documentation
- HTML5/CSS3 Standards
- Web Accessibility

## ✅ Ready to Use?

1. ✅ Database migration executed?
2. ✅ All files uploaded?
3. ✅ Documentation reviewed?
4. ✅ Features tested?
5. ✅ Team trained?

**You're ready to go!** 🚀

Navigate to `/admin/index.php` to start managing applicants.

---

**Admin Panel Version**: 1.0 | **System Version**: 2.0 | **Updated**: February 2026
