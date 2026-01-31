Quick test and integration notes for the Assessment Processor

APIs

- Process application (POST JSON):
  http://localhost/DEPEDEvaluationSystemV2/api/process_individual_application.php

  Example JSON body (curl):

```bash
curl -s -X POST http://localhost/DEPEDEvaluationSystemV2/api/process_individual_application.php \
  -H "Content-Type: application/json" \
  -d '{
    "position":"Teacher I",
    "salary_grade":11,
    "is_general_services":false,
    "applicant":{
      "name":"Juan Dela Cruz",
      "personal_information":{"name":"Juan Dela Cruz"},
      "education_level":"Bachelor of Secondary Education",
      "training_hours":40,
      "experience_years":2,
      "eligibilities":["LET"],
      "pb_et_rating":85,
      "cot_rating":25,
      "trf_rating":15,
      "education_documents":["TOR.pdf"],
      "eligibility_documents":["LET.pdf"],
      "work_experience_documents":["COE.pdf"],
      "training_documents":["PD.pdf"],
      "performance_ratings":["RPMS.pdf"],
      "birth_certificate":"birth.pdf",
      "transcript_of_records":"tor.pdf",
      "id_picture":"photo.jpg",
      "other_required_docs":[]
    }
  }'
```

- Get position groups (GET):
  http://localhost/DEPEDEvaluationSystemV2/api/get_position_groups.php

- Get QS for a position (GET):
  http://localhost/DEPEDEvaluationSystemV2/api/get_qs.php?position=Teacher%20I

UI Example

- A static example demonstrating the cascading dropdown is available at:
  `static/position_dropdown_example.html`

  Open in your browser (from project root):

```
http://localhost/DEPEDEvaluationSystemV2/static/position_dropdown_example.html
```

Next steps

- Expand `classes/AssessmentProcessor.php` `QUAL_STANDARDS` and increments tables (Tables 2.a/2.b/2.c).
- Wire the APIs into the UI: use `get_position_groups.php` to populate the group dropdown, then call `get_qs.php` to autopopulate QS text when a specific position is selected.
- Add CAR generation, ranking, audit trails, and PDF export as next features.
