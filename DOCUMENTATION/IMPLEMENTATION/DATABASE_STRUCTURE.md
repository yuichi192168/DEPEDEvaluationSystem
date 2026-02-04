# 🗄️ DATABASE STRUCTURE - Schema and Tables

Complete database schema documentation for the system.

**Reading time: 15 minutes**

---

## 📋 Database Overview

**Database Name:** deped_evaluation

**Server:** MySQL/MariaDB (localhost)

**Tables:** 5 main tables

**Records:** 
- applicants: 4 sample records (SAMPLE-001 to SAMPLE-004)
- evaluations: 4 sample records
- comparative_assessment_results: 4 sample records
- positions: 2+ positions

---

## 🗄️ TABLE 1: applicants

**Purpose:** Store applicant information

**Primary Key:** id (auto-increment)

### Column Definitions

| Column | Type | Null | Default | Description |
|--------|------|------|---------|-------------|
| id | INT | NO | auto_increment | Unique applicant ID |
| name | VARCHAR(255) | NO | - | Full name of applicant |
| email | VARCHAR(255) | YES | NULL | Email address |
| position_id | INT | NO | - | Foreign key to positions table |
| application_code | VARCHAR(50) | NO | - | Unique application code |
| status | VARCHAR(50) | YES | active | Applicant status |
| created_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Record creation time |
| updated_at | TIMESTAMP | YES | NULL | Last update time |

### Indexes

```sql
PRIMARY KEY (id)
UNIQUE KEY (application_code)
FOREIGN KEY (position_id) REFERENCES positions(id)
```

### Sample Data

| id | name | email | position_id | application_code | status | created_at |
|----|------|-------|-------------|------------------|--------|-----------|
| 1 | Maria Santos | maria@example.com | 1 | SAMPLE-001 | active | 2026-01-22 |
| 2 | Juan Dela Cruz | juan@example.com | 1 | SAMPLE-002 | active | 2026-01-22 |
| 3 | Ana Reyes | ana@example.com | 2 | SAMPLE-003 | active | 2026-01-22 |
| 4 | Carlos Mendoza | carlos@example.com | 2 | SAMPLE-004 | active | 2026-01-22 |

### Create Statement

```sql
CREATE TABLE applicants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    position_id INT NOT NULL,
    application_code VARCHAR(50) NOT NULL UNIQUE,
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (position_id) REFERENCES positions(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 🗄️ TABLE 2: evaluations

**Purpose:** Store evaluation scores for applicants

**Primary Key:** id (auto-increment)

**Relationship:** One evaluation per applicant (1:1 with applicants)

### Column Definitions

| Column | Type | Null | Default | Description |
|--------|------|------|---------|-------------|
| id | INT | NO | auto_increment | Unique evaluation ID |
| applicant_id | INT | NO | - | Foreign key to applicants |
| education_level | VARCHAR(50) | YES | - | Education qualification |
| education_score | DECIMAL(5,2) | YES | 0.00 | Education score (0-10) |
| training_hours | INT | YES | 0 | Training hours completed |
| training_score | DECIMAL(5,2) | YES | 0.00 | Training score (0-5) |
| experience_years | INT | YES | 0 | Years of experience |
| experience_score | DECIMAL(5,2) | YES | 0.00 | Experience score (0-20) |
| performance_rating | VARCHAR(50) | YES | - | Performance rating |
| performance_score | DECIMAL(5,2) | YES | 0.00 | Performance score (0-25) |
| accomplishments_score | DECIMAL(5,2) | YES | 0.00 | Accomplishments score (0-5) |
| app_education_score | DECIMAL(5,2) | YES | 0.00 | App of Education (0-10) |
| app_lnd_score | DECIMAL(5,2) | YES | 0.00 | App of L&D (0-10) |
| potential_score | DECIMAL(5,2) | YES | 0.00 | Potential score (0-10) |
| total_score | DECIMAL(5,2) | YES | 0.00 | Total weighted score |
| evaluator_name | VARCHAR(255) | YES | - | Evaluator's name |
| evaluation_date | DATE | YES | - | Date of evaluation |
| created_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Record creation time |
| updated_at | TIMESTAMP | YES | NULL | Last update time |

### Score Calculation

```
Total Score = 
  (education_score × 0.15) +
  (training_score × 0.05) +
  (experience_score × 0.20) +
  (performance_score × 0.25) +
  (accomplishments_score × 0.05) +
  (app_education_score × 0.10) +
  (app_lnd_score × 0.10) +
  (potential_score × 0.10)
```

### Indexes

```sql
PRIMARY KEY (id)
FOREIGN KEY (applicant_id) REFERENCES applicants(id)
INDEX (total_score)  -- For sorting and ranking
```

### Sample Data

| applicant_id | education_score | training_score | experience_score | performance_score | total_score |
|--------------|-----------------|-----------------|------------------|-------------------|------------|
| 1 (Maria) | 10.00 | 5.00 | 15.00 | 9.00 | 9.38 |
| 2 (Juan) | 9.00 | 4.00 | 14.00 | 8.00 | 8.73 |
| 3 (Ana) | 8.00 | 3.00 | 12.00 | 7.00 | 8.08 |
| 4 (Carlos) | 7.00 | 2.00 | 10.00 | 6.00 | 7.25 |

### Create Statement

```sql
CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,
    education_level VARCHAR(50),
    education_score DECIMAL(5,2) DEFAULT 0.00,
    training_hours INT DEFAULT 0,
    training_score DECIMAL(5,2) DEFAULT 0.00,
    experience_years INT DEFAULT 0,
    experience_score DECIMAL(5,2) DEFAULT 0.00,
    performance_rating VARCHAR(50),
    performance_score DECIMAL(5,2) DEFAULT 0.00,
    accomplishments_score DECIMAL(5,2) DEFAULT 0.00,
    app_education_score DECIMAL(5,2) DEFAULT 0.00,
    app_lnd_score DECIMAL(5,2) DEFAULT 0.00,
    potential_score DECIMAL(5,2) DEFAULT 0.00,
    total_score DECIMAL(5,2) DEFAULT 0.00,
    evaluator_name VARCHAR(255),
    evaluation_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (applicant_id) REFERENCES applicants(id),
    INDEX (total_score)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 🗄️ TABLE 3: positions

**Purpose:** Store position definitions

**Primary Key:** id (auto-increment)

### Column Definitions

| Column | Type | Null | Default | Description |
|--------|------|------|---------|-------------|
| id | INT | NO | auto_increment | Unique position ID |
| name | VARCHAR(255) | NO | - | Position title |
| position_code | VARCHAR(50) | YES | - | Unique position code |
| department | VARCHAR(255) | YES | - | Department/Unit |
| description | TEXT | YES | - | Position description |
| status | VARCHAR(50) | YES | active | Position status |
| created_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Record creation time |

### Indexes

```sql
PRIMARY KEY (id)
UNIQUE KEY (position_code)
```

### Sample Data

| id | name | position_code | department | description |
|----|------|----------------|-----------|-------------|
| 1 | Information and Communications Technology | ICT-001 | ICT Unit | ICT Teacher/Specialist |
| 2 | [Another Position] | POS-002 | Education | Secondary Teacher |

### Create Statement

```sql
CREATE TABLE positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    position_code VARCHAR(50) UNIQUE,
    department VARCHAR(255),
    description TEXT,
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 🗄️ TABLE 4: comparative_assessment_results (CAR)

**Purpose:** Store CAR (Comparative Assessment Result) records with rankings

**Primary Key:** id (auto-increment)

**Relationship:** One CAR record per applicant (1:1 with applicants)

### Column Definitions

| Column | Type | Null | Default | Description |
|--------|------|------|---------|-------------|
| id | INT | NO | auto_increment | Unique CAR ID |
| position_id | INT | NO | - | Foreign key to positions |
| applicant_id | INT | NO | - | Foreign key to applicants |
| evaluation_id | INT | NO | - | Foreign key to evaluations |
| applicant_name | VARCHAR(255) | NO | - | Applicant name (denormalized) |
| application_code | VARCHAR(50) | NO | - | Application code (denormalized) |
| total_score | DECIMAL(5,2) | NO | - | Total weighted score |
| rank | INT | NO | - | Ranking within position |
| remarks | TEXT | YES | - | Any remarks |
| created_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Record creation time |
| updated_at | TIMESTAMP | YES | NULL | Last update time |

### Sample Data

| position_id | applicant_name | application_code | total_score | rank |
|-------------|---|---|---|---|
| 1 | Maria Santos | SAMPLE-001 | 9.38 | 1 |
| 1 | Juan Dela Cruz | SAMPLE-002 | 8.73 | 2 |
| 2 | Ana Reyes | SAMPLE-003 | 8.08 | 1 |
| 2 | Carlos Mendoza | SAMPLE-004 | 7.25 | 2 |

### Ranking Logic

Ranking is calculated **per position**:

```sql
-- For each position, rank applicants by score (highest = 1)
SELECT applicant_id, position_id, total_score,
       ROW_NUMBER() OVER (PARTITION BY position_id 
                         ORDER BY total_score DESC) as rank
FROM comparative_assessment_results
```

### Indexes

```sql
PRIMARY KEY (id)
FOREIGN KEY (position_id) REFERENCES positions(id)
FOREIGN KEY (applicant_id) REFERENCES applicants(id)
FOREIGN KEY (evaluation_id) REFERENCES evaluations(id)
UNIQUE KEY (position_id, applicant_id)  -- One CAR per applicant per position
```

### Create Statement

```sql
CREATE TABLE comparative_assessment_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_id INT NOT NULL,
    applicant_id INT NOT NULL,
    evaluation_id INT NOT NULL,
    applicant_name VARCHAR(255) NOT NULL,
    application_code VARCHAR(50) NOT NULL,
    total_score DECIMAL(5,2) NOT NULL,
    rank INT NOT NULL,
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (position_id) REFERENCES positions(id),
    FOREIGN KEY (applicant_id) REFERENCES applicants(id),
    FOREIGN KEY (evaluation_id) REFERENCES evaluations(id),
    UNIQUE KEY (position_id, applicant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 🗄️ TABLE 5: users (Optional)

**Purpose:** Store user accounts for system access

**Primary Key:** id (auto-increment)

### Column Definitions

| Column | Type | Null | Default | Description |
|--------|------|------|---------|-------------|
| id | INT | NO | auto_increment | Unique user ID |
| username | VARCHAR(100) | NO | - | Login username |
| password | VARCHAR(255) | NO | - | Hashed password |
| email | VARCHAR(255) | YES | - | User email |
| role | VARCHAR(50) | YES | evaluator | User role |
| status | VARCHAR(50) | YES | active | Account status |
| created_at | TIMESTAMP | NO | CURRENT_TIMESTAMP | Account creation time |
| last_login | TIMESTAMP | YES | NULL | Last login time |

### Roles

- `admin` - System administrator
- `evaluator` - Can submit evaluations
- `reviewer` - Can view results
- `viewer` - Read-only access

### Create Statement

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    role VARCHAR(50) DEFAULT 'evaluator',
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 📊 Database Relationships

### Entity Relationship Diagram

```
positions
    ↓ (1 to many)
applicants
    ├─ (1 to 1)
    └─ evaluations
    
evaluations
    ├─ (1 to 1)
    └─ comparative_assessment_results
```

### Relationship Details

**positions ↔ applicants:**
- Type: One-to-Many (1:N)
- One position can have multiple applicants
- Foreign Key: applicants.position_id → positions.id

**applicants ↔ evaluations:**
- Type: One-to-One (1:1)
- Each applicant has one evaluation record
- Foreign Key: evaluations.applicant_id → applicants.id

**applicants ↔ comparative_assessment_results:**
- Type: One-to-One (1:1)
- Each applicant has one CAR record per position
- Foreign Key: CAR.applicant_id → applicants.id

**evaluations ↔ comparative_assessment_results:**
- Type: One-to-One (1:1)
- Each evaluation generates one CAR record
- Foreign Key: CAR.evaluation_id → evaluations.id

---

## 🔑 Important Queries

### Query 1: Get Positions with Applicants

**Purpose:** Show only positions that have applicants (used in dropdown)

```sql
SELECT p.id, p.name, p.position_code, COUNT(a.id) as applicant_count
FROM positions p
INNER JOIN applicants a ON p.id = a.position_id
GROUP BY p.id
ORDER BY p.name ASC;
```

**Result:**
```
id | name | position_code | applicant_count
1  | Information and Communications Technology | ICT-001 | 2
2  | [Another Position] | POS-002 | 2
```

### Query 2: Get CAR Results for Position

**Purpose:** Display all applicants for a position with rankings

```sql
SELECT 
    car.id,
    car.applicant_name,
    car.application_code,
    car.total_score,
    car.rank,
    e.education_score,
    e.training_score,
    e.experience_score,
    e.performance_score,
    e.accomplishments_score,
    e.app_education_score,
    e.app_lnd_score,
    e.potential_score
FROM comparative_assessment_results car
INNER JOIN evaluations e ON car.evaluation_id = e.id
WHERE car.position_id = ?
ORDER BY car.rank ASC;
```

### Query 3: Get Applicant Ranking

**Purpose:** Find specific applicant's rank within position

```sql
SELECT 
    a.name,
    a.application_code,
    car.rank,
    car.total_score,
    (SELECT COUNT(*) FROM comparative_assessment_results 
     WHERE position_id = car.position_id) as total_applicants
FROM applicants a
INNER JOIN comparative_assessment_results car ON a.id = car.applicant_id
WHERE a.id = ?;
```

### Query 4: Calculate Rankings

**Purpose:** Auto-calculate rankings for a position

```sql
UPDATE comparative_assessment_results car1
INNER JOIN (
    SELECT id,
           ROW_NUMBER() OVER (ORDER BY total_score DESC) as new_rank
    FROM comparative_assessment_results
    WHERE position_id = ?
) car2 ON car1.id = car2.id
SET car1.rank = car2.new_rank;
```

---

## 📈 Data Flow

### When Evaluation is Submitted

```
1. User fills evaluation form
   ↓
2. Form submitted to process_evaluation.php
   ↓
3. Data inserted into evaluations table
   ↓
4. Total score calculated
   ↓
5. CAR record created in comparative_assessment_results
   ↓
6. Ranking calculated (per position)
   ↓
7. Success response to user
```

### Database Sequence

```
INSERT INTO evaluations (applicant_id, education_score, ..., total_score)
  VALUES (?, ?, ..., calculated_total)

INSERT INTO comparative_assessment_results (position_id, applicant_id, evaluation_id, total_score, rank)
  VALUES (?, ?, ?, calculated_total, calculated_rank)

UPDATE comparative_assessment_results 
  SET rank = (SELECT COUNT(*) FROM comparative_assessment_results 
              WHERE position_id = ? AND total_score > ?)
```

---

## 🔒 Constraints and Rules

### Primary Key Constraints
- Every table has unique id
- Ensures no duplicate records
- Auto-incremented for new records

### Foreign Key Constraints
- applicants.position_id → positions.id
- evaluations.applicant_id → applicants.id
- comparative_assessment_results.position_id → positions.id
- comparative_assessment_results.applicant_id → applicants.id
- comparative_assessment_results.evaluation_id → evaluations.id

### Unique Constraints
- applicants.application_code (unique per applicant)
- positions.position_code (unique per position)
- comparative_assessment_results (unique: position_id + applicant_id)

### Not Null Constraints
- applicants: name, position_id, application_code
- evaluations: applicant_id
- positions: name
- comparative_assessment_results: position_id, applicant_id, evaluation_id, applicant_name, application_code, total_score, rank

---

## 🛠️ Sample Installation

### Run This to Create All Tables

```sql
-- Create positions table first (referenced by others)
CREATE TABLE positions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    position_code VARCHAR(50) UNIQUE,
    department VARCHAR(255),
    description TEXT,
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create applicants table
CREATE TABLE applicants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    position_id INT NOT NULL,
    application_code VARCHAR(50) NOT NULL UNIQUE,
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (position_id) REFERENCES positions(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create evaluations table
CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,
    education_level VARCHAR(50),
    education_score DECIMAL(5,2) DEFAULT 0.00,
    training_hours INT DEFAULT 0,
    training_score DECIMAL(5,2) DEFAULT 0.00,
    experience_years INT DEFAULT 0,
    experience_score DECIMAL(5,2) DEFAULT 0.00,
    performance_rating VARCHAR(50),
    performance_score DECIMAL(5,2) DEFAULT 0.00,
    accomplishments_score DECIMAL(5,2) DEFAULT 0.00,
    app_education_score DECIMAL(5,2) DEFAULT 0.00,
    app_lnd_score DECIMAL(5,2) DEFAULT 0.00,
    potential_score DECIMAL(5,2) DEFAULT 0.00,
    total_score DECIMAL(5,2) DEFAULT 0.00,
    evaluator_name VARCHAR(255),
    evaluation_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (applicant_id) REFERENCES applicants(id),
    INDEX (total_score)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create comparative_assessment_results (CAR) table
CREATE TABLE comparative_assessment_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    position_id INT NOT NULL,
    applicant_id INT NOT NULL,
    evaluation_id INT NOT NULL,
    applicant_name VARCHAR(255) NOT NULL,
    application_code VARCHAR(50) NOT NULL,
    total_score DECIMAL(5,2) NOT NULL,
    rank INT NOT NULL,
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (position_id) REFERENCES positions(id),
    FOREIGN KEY (applicant_id) REFERENCES applicants(id),
    FOREIGN KEY (evaluation_id) REFERENCES evaluations(id),
    UNIQUE KEY (position_id, applicant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 📞 Database Support

For database issues, see: [../TROUBLESHOOTING/DATABASE_FIXES.md](../TROUBLESHOOTING/DATABASE_FIXES.md)

---

**Complete! Your database is now fully documented.**
