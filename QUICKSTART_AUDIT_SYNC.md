# Quick Start: Audit Logs, Drafts Recovery & Background Sync

## 🚀 Deployment Checklist

### Step 1: Run Migration
```bash
cd C:\xampp\htdocs\DEPEDEvaluationSystemV2
php scripts/migrate_add_audit_logs.php
```

**Expected Output:**
```
schema.sql already contains audit_logs DDL
audit_logs table ensured in database.
```

### Step 2: Verify Installation
```bash
# Check audit_logs table exists
mysql -u root -p deped_evaluation -e "SHOW TABLES LIKE 'audit_logs';"
mysql -u root -p deped_evaluation -e "DESC audit_logs;"
```

### Step 3: Test in Browser
1. Open **http://localhost/DEPEDEvaluationSystemV2/**
2. Fill the form (at least 5 required fields)
3. Click **Save Draft** button in sticky bar
4. Verify success banner appears
5. Refresh page and see form data restored

### Step 4: Test Load Drafts
1. Fill form with different values
2. Click **Save Draft** again
3. Click **Load Drafts** button
4. Modal should show 2+ recent drafts
5. Click Load on first draft
6. Form should restore to saved state

---

## 🧪 Testing Scenarios

### Scenario 1: Offline Drafts with Background Sync
1. Open DevTools (F12) → Network tab
2. Set throttling to **Offline**
3. Fill form and click **Save Draft**
4. See: "Draft saved locally (server unreachable)"
5. Go back Online
6. Check MySQL `audit_logs` table for new entry
7. Check console for sync messages

### Scenario 2: Duplicate Applicant Prevention
1. Enter an applicant name that exists in `applicants` table
2. Click **Generate Report** or **Generate CAR**
3. Verify error: "An applicant with this name already exists…"
4. Try different name
5. Validation should pass

### Scenario 3: Service Worker Precaching
1. Open DevTools → Application → Service Workers
2. Should see one registered SW at `/sw.js`
3. Go to Cache Storage → `deped-eval-v1`
4. Should see ~5 cached assets (index.php, CSS, JS)
5. Set network to Offline
6. Reload page
7. Form should load from cache

---

## 📊 Database Verification

### Check Audit Logs Created
```sql
SELECT COUNT(*) FROM audit_logs;
SELECT action, object_type, COUNT(*) as count FROM audit_logs GROUP BY action, object_type;
```

### View Recent Drafts
```sql
SELECT id, session_id, application_code, created_at FROM drafts ORDER BY updated_at DESC LIMIT 10;
```

### Check Applicant Names (for duplicate validation)
```sql
SELECT name, COUNT(*) as count FROM applicants GROUP BY LOWER(TRIM(name)) HAVING count > 1;
```

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Migration fails with "DB connection failed" | Check `initialize.php` DB credentials (DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT) |
| Load Drafts button not showing | Refresh browser; check browser console for JS errors |
| Service Worker not registering | Clear browser cache; verify `sw.js` accessible at root URL |
| Duplicate check not working | Ensure `applicants` table exists; check MySQL for records |
| Background sync not triggering | Simulate offline/online in DevTools Network tab |

---

## 📝 API Endpoints

### Save Draft
**POST** `/api/save_draft.php`
```json
{
  "applicant_name": "John Doe",
  "position_applied": "Teacher I",
  ...
}
```
**Response:**
```json
{
  "success": true,
  "id": 123,
  "message": "Draft saved"
}
```

### List Drafts
**GET** `/api/drafts_list.php`
**Response:**
```json
{
  "success": true,
  "drafts": [
    { "id": 123, "session_id": "abc123", "created_at": "2026-01-31 10:30:00" }
  ]
}
```

### Load Draft
**GET** `/api/drafts_load.php?id=123`
**Response:**
```json
{
  "success": true,
  "data": { "applicant_name": "John Doe", ... }
}
```

### Validate Fields (with Duplicate Check)
**POST** `/api/validate_fields.php`
```json
{
  "applicant_name": "John Doe",
  "contact_number": "09123456789",
  ...
}
```
**Response (if duplicate):**
```json
{
  "valid": false,
  "errors": {
    "applicant_name": "An applicant with this name already exists in the system"
  }
}
```

---

## 🔐 Security Notes

✓ **SQL Injection Prevention:** Using `real_escape_string()`  
✓ **XSS Prevention:** HTML escaping in JS  
✓ **CSRF Protection:** Session-based (existing)  
✓ **IP Logging:** Audit logs store client IP  
✓ **Offline Data:** Stored in browser IndexedDB (user-local only)

---

## 📖 Documentation Files

- `IMPLEMENTATION_AUDIT_DRAFTS_SYNC.md` — Complete feature documentation
- `ARCHITECTURE.md` (if exists) — System architecture overview
- `README.md` — Main project documentation

---

**Last Updated:** 2026-01-31  
**Status:** ✅ Implementation Complete & Tested
