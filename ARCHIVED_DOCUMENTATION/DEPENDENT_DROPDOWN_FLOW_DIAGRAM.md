# DEPENDENT DROPDOWN SYSTEM - FLOW DIAGRAM

## 1. INITIAL PAGE LOAD

```
Page Loads (index.php)
    ↓
JavaScript executes: loadPositionGroups()
    ↓
Fetch api/get_position_groups.php
    ↓
AssessmentProcessor::getPositionGroups()
    ↓
AssessmentProcessor::buildPositionGroups()
    ↓
Reads config/baseline_library.php
    ↓
Groups 130+ positions by position_group field
    ↓
Returns ordered array of 6 groups with their positions
    ↓
JSON Response:
[
  { group: "teaching positions", positions: ["Teacher I"] },
  { group: "higher teaching positions", positions: ["Teacher II", "Master Teacher I", ...] },
  { group: "school administration position", positions: ["School Principal I", ...] },
  { group: "related teaching position", positions: ["Guidance Counselor I", ...] },
  { group: "non-teaching level I", positions: ["Attorney I", "Accountant II", ...] },
  { group: "non-teaching level II", positions: ["Dentist II", "Architect II", ...] }
]
    ↓
JavaScript populates Position Group dropdown with 6 options
    ↓
Page displays both dropdowns ready for interaction
```

## 2. USER SELECTS POSITION GROUP

```
User clicks "Position Group" dropdown and selects "non-teaching level I"
    ↓
Change event fires on position_group_select
    ↓
JavaScript retrieves selected group from window.positionGroups array
    ↓
Extracts positions array: [
    "Attorney I",
    "Accountant II", 
    "Engineer II",
    ... (30+ positions)
]
    ↓
Clears Position dropdown (except "Custom Position" option)
    ↓
Loops through positions array
    ↓
For each position, finds matching key in baseline_library
    Example: "Attorney I" → key "attorney_i"
    ↓
Creates <option value="attorney_i">Attorney I</option>
    ↓
Adds option to Position dropdown
    ↓
Position dropdown now shows only non-teaching level I positions:
  - Attorney I
  - Attorney II
  - Attorney III
  - Attorney IV
  - Attorney V
  - Accountant II
  - Accountant III
  - Accountant IV
  - Engineer II
  - Engineer III
  - Engineer V
  ... and more
    ↓
Auto-selects first position in group
    ↓
Triggers position_key change event
```

## 3. USER SELECTS POSITION

```
User selects "Attorney V" from Position dropdown
    ↓
Change event fires on position_key
    ↓
JavaScript looks up "Attorney V" in positions object
    ↓
Retrieves baseline data:
{
  position_name: "Attorney V",
  position_group: "non-teaching level I",
  salary_grade: 25,
  education: { degree: "Master", ... },
  training: 40,
  experience: 60,
  ...
}
    ↓
Auto-fills form fields:
  - Position Applied For: "Attorney V"
  - Position Group: "non-teaching level I"
  - Education baseline: Master's degree
  - Training baseline: 40 hours
  - Experience baseline: 60 months
    ↓
Triggers calculatePreview() function
    ↓
calculatePreview() determines position group = "non-teaching level I"
    ↓
Looks up weights for that group:
{
  education: 5,
  training: 5,
  experience: 20,
  performance: 20,
  outstanding_accomplishments: 10,
  ...
}
    ↓
Live Preview table updates with correct weights for non-teaching level I
    ↓
Shows calculation: applicant scores × weights for that group
```

## 4. COMPLETE DATA FLOW VISUALIZATION

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         config/baseline_library.php                      │
│                      (130+ Positions with Groups)                        │
├─────────────────────────────────────────────────────────────────────────┤
│  'teacher_i' → position_group: 'teaching positions'                      │
│  'teacher_ii' → position_group: 'higher teaching positions'              │
│  'principal_i' → position_group: 'school administration position'        │
│  'attorney_i' → position_group: 'non-teaching level I'                   │
│  'dentist_ii' → position_group: 'non-teaching level II'                  │
│  'guidance_counselor_i' → position_group: 'related teaching position'    │
│  ... (130+ total)                                                        │
└─────────────────────────────────────────────────────────────────────────┘
                                    ↓
                    reads and processes
                                    ↓
┌─────────────────────────────────────────────────────────────────────────┐
│              classes/AssessmentProcessor.php                            │
│          buildPositionGroups() → getPositionGroups()                    │
├─────────────────────────────────────────────────────────────────────────┤
│  Groups positions by position_group field                               │
│  Sorts alphabetically within each group                                 │
│  Returns organized structure:                                           │
│    teaching positions → [Teacher I]                                     │
│    higher teaching positions → [Teacher II, Master Teacher I, ...]      │
│    school administration position → [Principal I, II, III, ...]         │
│    related teaching position → [Guidance Counselor I, ...]              │
│    non-teaching level I → [Attorney I, Accountant II, ...]              │
│    non-teaching level II → [Dentist II, Architect II, ...]              │
└─────────────────────────────────────────────────────────────────────────┘
                                    ↓
                   converts to JSON
                                    ↓
┌─────────────────────────────────────────────────────────────────────────┐
│                  api/get_position_groups.php (API)                      │
│                    Returns JSON to Frontend                             │
├─────────────────────────────────────────────────────────────────────────┤
│  [                                                                       │
│    { group: "teaching positions", positions: [...] },                   │
│    { group: "higher teaching positions", positions: [...] },            │
│    { group: "school administration position", positions: [...] },       │
│    { group: "related teaching position", positions: [...] },            │
│    { group: "non-teaching level I", positions: [...] },                 │
│    { group: "non-teaching level II", positions: [...] }                 │
│  ]                                                                       │
└─────────────────────────────────────────────────────────────────────────┘
                                    ↓
                  consumed by frontend
                                    ↓
┌─────────────────────────────────────────────────────────────────────────┐
│                    index.php (Frontend JavaScript)                      │
│              loadPositionGroups() → Cascading Logic                     │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                          │
│  Position Group Dropdown (First)      Position Dropdown (Second)        │
│  ┌──────────────────────────┐         ┌──────────────────────────┐     │
│  │ -- Select Group --       │         │ -- Custom Position --    │     │
│  │ teaching positions       │         │ [Empty until group sel.] │     │
│  │ higher teaching...       │         │                          │     │
│  │ school administration... │    →    │ Filters based on:        │     │
│  │ related teaching...      │    on   │ position_group_select    │     │
│  │ non-teaching level I     │    →    │ selected value           │     │
│  │ non-teaching level II    │    change                          │     │
│  └──────────────────────────┘         └──────────────────────────┘     │
│                                                                          │
│  When "non-teaching level I" is selected:                               │
│  Position dropdown auto-populates with positions where                  │
│  position_group == "non-teaching level I"                               │
│                                                                          │
│  → Attorney I                                                            │
│  → Attorney II                                                           │
│  → Attorney III                                                          │
│  → Accountant II                                                         │
│  → Engineer II                                                           │
│  ... (30+ total)                                                         │
└─────────────────────────────────────────────────────────────────────────┘
                                    ↓
                         when position selected
                                    ↓
┌─────────────────────────────────────────────────────────────────────────┐
│               Live Preview & Weight Calculation                         │
│            (calculatePreview() in index.php)                            │
├─────────────────────────────────────────────────────────────────────────┤
│  Gets selected position group: "non-teaching level I"                   │
│                                                                          │
│  Looks up weights for that group:                                       │
│  weights['non-teaching level I'] = {                                    │
│    education: 5,                                                         │
│    training: 5,                                                          │
│    experience: 20,                                                       │
│    performance: 20,                                                      │
│    outstanding_accomplishments: 10,                                      │
│    application_of_education: 10,                                         │
│    application_of_ld: 10,                                                │
│    potential: 20                                                         │
│  }                                                                       │
│                                                                          │
│  Calculates: applicant_score × weight × baseline_comparison             │
│  Updates live preview table with results                                 │
│  Displays total score                                                    │
└─────────────────────────────────────────────────────────────────────────┘
```

## 5. DEPENDENCY CHAIN

```
Position Group Selection
    ↓ triggers
Position Dropdown Filter
    ↓ contains
Position Objects from baseline_library
    ↓ with field
position_group: "group_name"
    ↓ used for
Weight Lookup: weights[position_group]
    ↓ applied to
Live Preview Calculations
    ↓ creates
Evaluation Score
```

## 6. KEY FILES IN DEPENDENCY ORDER

```
1. config/baseline_library.php
   └─ Contains raw position data with position_group field
   
2. classes/AssessmentProcessor.php
   └─ Builds groups from baseline_library
   └─ Defines weight mappings per group
   
3. api/get_position_groups.php
   └─ Converts AssessmentProcessor groups to JSON
   
4. index.php (JavaScript section)
   └─ loadPositionGroups() - Fetches and populates
   └─ calculatePreview() - Uses weights for live preview
```

## 7. EXAMPLE: NON-TEACHING LEVEL I FILTER

```
Step 1: User selects "non-teaching level I" from first dropdown

Step 2: JavaScript executes:
    groups[selectedGroupIndex] 
    = { 
        group: "non-teaching level I",
        positions: [
            "Attorney I",
            "Attorney II", 
            "Attorney III",
            "Attorney IV",
            "Attorney V",
            "Accountant II",
            "Accountant III",
            "Accountant IV",
            "Architect II",
            "Architect III",
            "Chief Admin Officer",
            "Chief Accountant",
            "Chief Education Supervisor",
            "Chief Health Program Officer",
            "Engineer II",
            "Engineer III",
            "Engineer V",
            "ICT Officer I",
            "ICT Officer III",
            "Internal Auditor II",
            "Internal Auditor III",
            "Medical Officer II",
            "Medical Officer III",
            "Medical Officer IV",
            "Planning Officer II",
            "Planning Officer III",
            "Planning Officer V",
            "Project Dev Officer II",
            "Project Dev Officer III",
            "Project Dev Officer V",
            "Vocational Admin II"
        ]
    }

Step 3: JavaScript populates position_key dropdown with these 30+ options

Step 4: User selects position → baseline loads → weights apply
```

---

## STATUS: ✓ FULLY IMPLEMENTED

All components are integrated and working together to provide a dependent dropdown experience where users select a position group first, then see only relevant positions for that group.
