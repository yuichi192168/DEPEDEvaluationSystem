# Admin Applicants Management Dashboard - Setup & Usage Guide

## Overview

The Admin Applicants Management Dashboard is a comprehensive tool that allows administrators to efficiently manage all applicants in the DepEd HRMPSB Evaluation System. It includes features for searching, filtering, archiving, and restoring applicants with a clean, intuitive interface.

## Features

### 1. **Applicant Viewing & Management**
- View all active applicants in a paginated table
- Display applicant details including name, position group, and applied position
- Real-time application counts and statistics
- Responsive design that works on desktop and mobile devices

### 2. **Search & Filtering**
- Search applicants by name in real-time
- Filter by position group (A, B, C)
- Combined search and filter functionality
- Pagination support with customizable results per page

### 3. **Archiving System**
- Archive individual applicants with optional reasons
- Bulk archive multiple applicants at once
- Archive reason tracking (hired, withdrew, etc.)
- Automatic timestamp recording of archive date

### 4. **Restoration Features**
- Restore archived applicants back to active status
- Bulk restore multiple archived applicants
- Complete audit trail of all archive/restore actions
- Ability to restore applicants at any time

### 5. **Statistics Dashboard**
- Total applicants count (active + archived)
- Active applicants breakdown
- Archived applicants count
- Position group distribution (Groups A, B, C)
- Real-time updates

### 6. **Audit Trail & History**
- Complete archive history for each applicant
- Timestamp recording for all actions
- Reason tracking for archiving actions
- Administrative accountability

### 7. **Bulk Operations**
- Select multiple applicants with checkboxes
- Bulk archive with common reason
- Bulk restore operations
- Progress feedback on bulk actions

## Installation & Setup

### Step 1: Copy Files to Your System

The following files have been created in your system:

```
classes/ApplicantManager.php          - Core manager class
api/archive_applicant.php             - Archive API endpoint
api/restore_applicant.php             - Restore API endpoint
api/get_applicants.php                - Get applicants API endpoint
api/get_applicant_details.php         - Applicant details API endpoint
api/get_applicant_stats.php           - Statistics API endpoint
api/bulk_archive_applicants.php       - Bulk archive API endpoint
admin/index.php                       - Admin dashboard home
admin/applicants.php                  - Main applicants dashboard
database/migration_add_archiving.sql  - Database migration script
```

### Step 2: Run Database Migration

**IMPORTANT:** Before using the archiving features, you must execute the database migration script.

#### Method 1: Using phpMyAdmin
1. Open phpMyAdmin in your browser
2. Select your `deped_evaluation` database
3. Click the "SQL" tab
4. Open the file `database/migration_add_archiving.sql`
5. Copy and paste the SQL content
6. Click "Go" to execute

#### Method 2: Using MySQL Command Line
```bash
mysql -u username -p deped_evaluation < database/migration_add_archiving.sql
```

#### Method 3: Using MySQL Workbench
1. Connect to your MySQL database
2. File → Open SQL Script → select `migration_add_archiving.sql`
3. Click the "Execute" button (lightning bolt icon)

The migration script will:
- Add `archive_status` column to the `applicants` table
- Add `archived_at` column to track archiving timestamp
- Add `archive_reason` column for archiving notes
- Create `archived_applicants_audit` table for audit logging
- Create necessary indexes for performance

### Step 3: Verify Database Changes

After running the migration, verify the changes:

```sql
-- Check applicants table structure
DESCRIBE applicants;

-- Should show the new columns:
-- - archive_status (ENUM: active, archived)
-- - archived_at (TIMESTAMP)
-- - archive_reason (VARCHAR)

-- Check if audit table exists
SHOW TABLES LIKE 'archived_applicants_audit';
```

## Usage Guide

### Accessing the Dashboard

1. **Admin Home**: Navigate to `/admin/index.php`
2. **Applicants Dashboard**: Click "Open Dashboard" or navigate to `/admin/applicants.php`

### Main Dashboard Screens

#### Active Applicants Tab
- View all applicants with `archive_status = 'active'`
- See creation date for each applicant
- Perform individual archive or view details

#### Archived Applicants Tab
- View all archived applicants
- See archive date and reason for each applicant
- Perform individual restore or view complete history

### Searching for Applicants

1. Enter applicant name in the search box (partial match supported)
2. Optionally select a position group filter
3. Click "Search" or press Enter
4. Results display immediately with pagination

**Example searches:**
- "John" - finds all applicants with "John" in their name
- "Maria" with Group B - finds applicants named Maria in Group B

### Archiving an Applicant

#### Individual Archive:
1. Click the "📦 Archive" button next to the applicant
2. A modal dialog appears asking for confirmation
3. Enter optional reason (e.g., "Hired", "Withdrew Application", "Duplicate")
4. Click "📦 Archive" to confirm
5. Applicant moves to archived tab immediately
6. Success message appears

#### Bulk Archive:
1. Select applicants using the checkboxes
2. Click "📦 Bulk Archive" button
3. Enter optional reason that applies to all selected applicants
4. Click "📦 Archive All"
5. All selected applicants are archived
6. Refresh shows updated counts

### Restoring Applicants

#### Individual Restore:
1. Go to "Archived Applicants" tab
2. Click the "↩️ Restore" button next to the applicant
3. Confirm the restoration in the modal
4. Click "↩️ Restore"
5. Applicant moves back to active list

#### Bulk Restore:
1. Go to "Archived Applicants" tab
2. Select archived applicants
3. Click "↩️ Bulk Restore"
4. Confirm restoration
5. All selected applicants return to active status

### Viewing Applicant Details

1. Click the "👁️ View" button on any applicant row
2. Modal opens showing:
   - Full applicant information
   - Position and group details
   - Current status (active/archived)
   - Creation date
   - Archive date (if archived)
   - Archive reason (if applicable)
   - Complete archive/restore history
3. Close modal by clicking the X or outside the modal

### Using Statistics

The dashboard shows real-time statistics:
- **Total Applicants**: Sum of active and archived
- **Active Applicants**: Count of active candidates
- **Archived Applicants**: Count of archived candidates
- **Group A/B/C**: Distribution of active applicants by group

Statistics update automatically when applicants are archived or restored.

### Pagination

- Navigate through large result sets using pagination controls
- Shows page numbers with Previous/Next buttons
- First and Last page shortcuts available
- Results per page set to 25 (configurable in applicants.php)

## API Endpoints

The dashboard uses REST API endpoints for all operations:

### Archive Applicant
```
POST /api/archive_applicant.php
Content-Type: application/json

{
  "applicant_id": 1,
  "reason": "Hired",
  "archived_by": "Admin Name"
}

Response:
{
  "success": true,
  "message": "Applicant archived successfully"
}
```

### Restore Applicant
```
POST /api/restore_applicant.php
Content-Type: application/json

{
  "applicant_id": 1,
  "restored_by": "Admin Name"
}

Response:
{
  "success": true,
  "message": "Applicant restored successfully"
}
```

### Get Applicants
```
GET /api/get_applicants.php?status=active&search=john&group=A&limit=50&offset=0

Response:
{
  "success": true,
  "applicants": [...],
  "total": 10
}
```

### Get Applicant Details
```
GET /api/get_applicant_details.php?id=1

Response:
{
  "success": true,
  "applicant": {...},
  "history": [...]
}
```

### Bulk Archive
```
POST /api/bulk_archive_applicants.php
Content-Type: application/json

{
  "applicant_ids": [1, 2, 3],
  "reason": "Batch processing complete",
  "archived_by": "Admin Name"
}

Response:
{
  "success": true,
  "message": "Archived 3 applicant(s). Failed: 0",
  "archived": 3,
  "failed": 0
}
```

## Database Schema

### Applicants Table Changes

```sql
ALTER TABLE applicants 
ADD COLUMN archive_status ENUM('active', 'archived') DEFAULT 'active';
ADD COLUMN archived_at TIMESTAMP NULL;
ADD COLUMN archive_reason VARCHAR(255) NULL;
```

### Archived Applicants Audit Table

```sql
CREATE TABLE archived_applicants_audit (
    id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,
    applicant_name VARCHAR(255) NOT NULL,
    action ENUM('archived', 'restored') NOT NULL,
    reason VARCHAR(255) NULL,
    archived_by VARCHAR(255) NULL,
    archived_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT NULL,
    FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE CASCADE,
    INDEX idx_applicant_id (applicant_id),
    INDEX idx_action (action),
    INDEX idx_archived_at (archived_at)
);
```

## Best Practices

### 1. **Regular Archiving**
- Archive processed applicants after evaluation completion
- Use meaningful reasons ("Hired", "Rejected", "Withdrew", etc.)
- This keeps the active list manageable

### 2. **Backup Before Bulk Operations**
- Consider backing up your database before large bulk operations
- Test with single applicant first if unsure

### 3. **Documenting Reasons**
- Always include archive reasons for audit trails
- Use consistent terminology
- Enable future recovery if needed

### 4. **Access Control**
- Restrict admin access to authorized personnel only
- Implement proper authentication
- Consider adding role-based access control

### 5. **Monitoring**
- Check statistics regularly
- Review archived applicants periodically
- Verify audit trail for sensitive operations

## Troubleshooting

### Issue: Migration script doesn't execute
**Solution**: 
- Verify database credentials
- Check that deped_evaluation database exists
- Ensure you have SQL execute privileges
- Try using phpMyAdmin instead of command line

### Issue: Archiving button doesn't work
**Solution**:
- Check browser console for JavaScript errors (F12 → Console)
- Verify API endpoints are accessible
- Ensure database migration was completed
- Check file permissions on api/ folder

### Issue: Archive history not showing
**Solution**:
- Verify archived_applicants_audit table exists
- Check that audit table has data
- Refresh the page and try again
- Clear browser cache

### Issue: Performance is slow with many applicants
**Solution**:
- Adjust limit parameter (currently 25 per page)
- Archive older applicants to reduce active list size
- Add database indexes if missing
- Consider using search/filter to narrow results

## Architecture Overview

### Classes
- **ApplicantManager**: Core business logic for archive operations
  - `getActiveApplicants()` - Retrieve active applicants with pagination
  - `getArchivedApplicants()` - Retrieve archived applicants
  - `archiveApplicant()` - Archive single applicant
  - `restoreApplicant()` - Restore single applicant
  - `bulkArchive()` - Archive multiple applicants
  - `getStatistics()` - Get dashboard statistics

### API Layer
- Stateless endpoints for all operations
- JSON request/response format
- Error handling and validation
- Prepared statements for SQL security

### Frontend
- Responsive HTML/CSS/JavaScript interface
- Tab-based navigation (active/archived)
- Modal dialogs for confirmations
- Real-time search and filtering
- Checkbox selection for bulk operations

## Security Considerations

1. **SQL Injection Prevention**: Uses prepared statements
2. **Input Validation**: All user input validated before processing
3. **Session Management**: Uses PHP sessions for state
4. **CSRF Protection**: Consider adding tokens for sensitive operations
5. **Authentication**: Add proper user authentication before production use
6. **Audit Logging**: All actions logged in audit table

## Future Enhancements

Consider implementing:
- Email notifications on archiving
- Advanced filtering options (date range, batch operations)
- Export to CSV/Excel
- Role-based access control
- Activity dashboard
- Automated archiving rules
- Multi-level approval process
- Archive categories/tags

## Support & Maintenance

### Regular Maintenance
- Monitor database growth
- Archive old applicants periodically
- Review audit logs monthly
- Backup database regularly

### Performance Optimization
- Database indexes on archive_status and archived_at
- Pagination limits prevent large dataset transfers
- Lazy loading of applicant details

## Version Information

- **System**: DepEd HRMPSB Evaluation System v2.0
- **Feature**: Applicants Management Dashboard
- **Created**: February 2026
- **Database**: MySQL/MariaDB
- **PHP Version**: 5.6+

---

For questions or issues, refer to the API endpoints documentation or contact your system administrator.
