# Form Validation System - Technical Specification

## Overview
A comprehensive client-side validation system for the DepEd HRMPSB Evaluation System that provides real-time feedback, Philippine contact number validation, and intelligent form state management.

---

## Architecture

### Core Components

#### 1. FormValidator Class (`js/form-validation.js`)
```javascript
class FormValidator {
    constructor(formId = 'evaluationForm')
    
    // Initialization
    init()
    setupValidationRules()
    attachEventListeners()
    
    // Validation Methods
    validateField(fieldId)
    validateForm()
    validatePhilippineContactNumber(value)
    
    // UI Updates
    updateFieldIndicator(fieldId, isValid)
    updateProgressIndicator()
    updateButtonStates()
    showFieldError(fieldId)
    clearFieldError(fieldId)
    
    // Event Handlers
    handleFieldChange(fieldId)
    handleFieldBlur(fieldId)
    handleFormSubmit(e)
    
    // Error Handling
    showValidationErrors()
    scrollToFirstInvalidField()
    
    // Utilities
    isFormValid()
    getValidationState()
}
```

#### 2. CSS Styling (`css/form-validation.css`)
- Valid field states
- Invalid field states
- Progress indicator styles
- Error message styling
- Helper text styling
- Button disabled states
- Accessibility features

---

## Validation Rules

### Required Fields Configuration
```javascript
{
    applicant_name: {
        validate: (value) => value.trim().length > 0,
        message: 'Applicant name is required',
        fieldName: 'Name of Applicant'
    },
    contact_number: {
        validate: (value) => this.validatePhilippineContactNumber(value),
        message: 'Use format 09XXXXXXXXX or +639XXXXXXXXX',
        fieldName: 'Contact Number'
    },
    position_applied: {
        validate: (value) => value.trim().length > 0,
        message: 'Position is required',
        fieldName: 'Position Applied For'
    },
    schools_division_office: {
        validate: (value) => value.trim().length > 0,
        message: 'Schools Division Office is required',
        fieldName: 'Schools Division Office'
    },
    job_group_sg_level: {
        validate: (value) => value.trim().length > 0,
        message: 'Job Group and Salary Grade is required',
        fieldName: 'Job Group / Salary Grade'
    }
}
```

### Philippine Contact Number Validation
```javascript
validatePhilippineContactNumber(value) {
    if (!value) return false;
    
    const cleaned = value.replace(/[\s\-]/g, '');
    const format1 = /^09\d{9}$/;        // 09XXXXXXXXX (11 digits total)
    const format2 = /^\+639\d{9}$/;    // +639XXXXXXXXX (12 chars total)
    
    return format1.test(cleaned) || format2.test(cleaned);
}
```

---

## Event Flow

### User Input → Validation → UI Update

```
User types in field
    ↓
'input' event fired
    ↓
validateField() checks current value
    ↓
isValid = true/false
    ↓
updateFieldIndicator() updates UI
    ↓
updateProgressIndicator() updates progress bar
    ↓
updateButtonStates() enables/disables submit button
```

### Events Triggered

| Event | When | Handler |
|-------|------|---------|
| `input` | User types | `handleFieldChange()` |
| `blur` | User leaves field | `handleFieldBlur()` |
| `change` | Field value changes | `handleFieldChange()` |
| `submit` | Form submit | `handleFormSubmit()` |
| `DOMContentLoaded` | Page loads | Initialize validator |
| `fieldUpdated` | Custom event | Trigger validation |

---

## State Management

### Form State
```javascript
{
    isValid: boolean,              // Overall form validity
    requiredFields: [              // Tracked field IDs
        'applicant_name',
        'contact_number',
        'position_applied',
        'schools_division_office',
        'job_group_sg_level'
    ]
}
```

### Field State
```javascript
{
    fieldId: {
        value: string,             // Current field value
        isValid: boolean,          // Validation result
        isEmpty: boolean,          // Is field empty
        isDirty: boolean,          // User has interacted
        hasError: boolean,         // Show error state
        errorMessage: string       // Error text to display
    }
}
```

---

## CSS Classes

### Field States
```css
input.valid     /* Valid input with green styling */
input.invalid   /* Invalid input with red styling */
```

### Indicators
```css
.valid-indicator          /* Green checkmark */
.field-error              /* Red error message */
.required-indicator       /* Red asterisk for required */
```

### Button States
```css
.action-button            /* Primary action button */
.action-button:disabled   /* Disabled button styling */
.action-button.disabled   /* Alternative disabled class */
```

### Progress Indicator
```css
.form-progress            /* Progress container */
.progress-bar             /* Progress bar background */
.progress-fill            /* Animated progress fill */
.progress-text            /* Progress text (X of Y) */
.form-progress.completed  /* Completed state styling */
```

---

## Integration Points

### HTML Integration
```html
<!-- Progress Indicator -->
<div class="form-progress" id="form_progress">
    <label class="progress-label">Form Completion Status</label>
    <div class="progress-bar">
        <div class="progress-fill"></div>
    </div>
    <span class="progress-text">0 of 5 required fields completed</span>
</div>

<!-- Field with Validation -->
<div class="form-group">
    <label for="field_id">
        Field Name 
        <span class="required-indicator">*</span>
    </label>
    <div class="field-wrapper">
        <input type="text" id="field_id" name="field_name" required>
        <span class="valid-indicator" id="field_id_valid_indicator">✓</span>
    </div>
    <span class="helper-text">Helper text explaining expected input</span>
</div>

<!-- Error Message -->
<div id="field_id_error" class="field-error" role="alert"></div>

<!-- Action Button -->
<button type="submit" class="btn-primary action-button" disabled>
    Generate Evaluation Report
</button>
```

### JavaScript Integration
```html
<!-- Include validation script -->
<script src="js/form-validation.js"></script>

<!-- Access validator instance -->
<script>
    // After DOM loads, validator is available as:
    window.formValidator
    
    // Manual validation trigger
    window.formValidator.validateForm()
    
    // Check form validity
    if (window.formValidator.isFormValid()) {
        // Form is valid, proceed
    }
</script>
```

---

## API Reference

### FormValidator Methods

#### `constructor(formId)`
Initialize the validator for a specific form.
```javascript
const validator = new FormValidator('evaluationForm');
```

#### `validateField(fieldId)`
Validate a single field against its rule.
```javascript
const isValid = validator.validateField('contact_number');
// Returns: true or false
```

#### `validateForm()`
Validate all required fields.
```javascript
const allValid = validator.validateForm();
// Returns: true if all fields valid, false otherwise
// Also updates: progress bar, button states, form state
```

#### `validatePhilippineContactNumber(value)`
Validate Philippine contact number format.
```javascript
const isValid = validator.validatePhilippineContactNumber('09123456789');
// Returns: true for valid format, false otherwise
```

#### `updateFieldIndicator(fieldId, isValid)`
Update the UI indicator (checkmark/error) for a field.
```javascript
validator.updateFieldIndicator('contact_number', true);
// Shows green checkmark if true, hides if false
```

#### `updateProgressIndicator()`
Update the progress bar and text.
```javascript
validator.updateProgressIndicator();
// Updates: progress bar width, completed count text
```

#### `updateButtonStates()`
Enable/disable action buttons based on form validity.
```javascript
validator.updateButtonStates();
// Enables buttons if form valid, disables if not
```

#### `showFieldError(fieldId)`
Display error message for a field.
```javascript
validator.showFieldError('contact_number');
// Shows error message below field
```

#### `clearFieldError(fieldId)`
Hide error message for a field.
```javascript
validator.clearFieldError('contact_number');
// Hides error message
```

#### `isFormValid()`
Check if form is currently valid.
```javascript
if (validator.isFormValid()) {
    // All fields valid
}
```

#### `getValidationState()`
Get validation state of all required fields.
```javascript
const state = validator.getValidationState();
// Returns: {
//     fieldId: { value, isValid, isEmpty },
//     ...
// }
```

#### `scrollToFirstInvalidField()`
Scroll to and focus first invalid field.
```javascript
validator.scrollToFirstInvalidField();
// Automatically called on form submit if invalid
```

---

## Configuration

### Required Fields List
Edit `requiredFields` array to change which fields are required:
```javascript
this.requiredFields = [
    'applicant_name',
    'contact_number',
    'position_applied',
    'schools_division_office',
    'job_group_sg_level'
];
```

### Validation Rules
Modify `setupValidationRules()` to add/change rules:
```javascript
this.validationRules[fieldId] = {
    validate: (value) => /* validation logic */,
    message: 'Error message',
    fieldName: 'Display name'
};
```

---

## Error Handling

### Validation Error Messages
Error messages appear in red below invalid fields:
```
┌─ Contact Number ─────────────────┐
│ 09XXXXXXX                        │ [error icon]
└──────────────────────────────────┘
[red] Use format 09XXXXXXXXX or +639XXXXXXXXX
```

### Form Submission Behavior
```javascript
// On form submit:
if (!this.isFormValid()) {
    e.preventDefault();           // Block submission
    this.showValidationErrors();  // Mark all errors
    this.scrollToFirstInvalidField(); // Focus error
    return false;
}
// If valid, form submits normally
```

---

## Accessibility Features

### ARIA Attributes
```html
<!-- Field error with alert role -->
<div id="field_error" class="field-error" role="alert" aria-live="assertive">
    Error message
</div>

<!-- Progress indicator with live region -->
<div class="form-progress" aria-live="polite" aria-atomic="true">
    <!-- Updates announced to screen readers -->
</div>

<!-- Required indicator -->
<span class="required-indicator" aria-label="Required field">*</span>
```

### Keyboard Navigation
- Tab through fields normally
- Validation on blur and input
- Error messages immediately available
- Form submit respects HTML5 validation

### Screen Reader Support
- Error messages announced with `role="alert"`
- Progress updates announced with `aria-live`
- Field labels properly associated
- Helper text readable

---

## Performance Considerations

### Optimization Techniques
1. **Minimal DOM queries** - Cache field references
2. **Debounced updates** - Prevent excessive repaints
3. **Event delegation** - Attach listeners efficiently
4. **CSS animations** - GPU-accelerated with transforms
5. **Conditional rendering** - Only show/hide elements as needed

### Browser Compatibility
- Modern browsers (Chrome, Firefox, Safari, Edge)
- ES6 JavaScript features used
- CSS Grid and Flexbox for layout
- CSS transitions and animations
- LocalStorage for session data (if needed)

---

## Testing Guide

### Unit Testing
```javascript
// Test contact number validation
console.assert(
    validator.validatePhilippineContactNumber('09123456789'),
    'Should validate 09X format'
);

console.assert(
    !validator.validatePhilippineContactNumber('9123456789'),
    'Should reject incomplete format'
);
```

### Integration Testing
```javascript
// Test form validation
const field = document.getElementById('contact_number');
field.value = '09123456789';
field.dispatchEvent(new Event('input'));

console.assert(
    validator.isFormValid() === false,
    'Form should be invalid (other fields empty)'
);
```

### Manual Testing Checklist
- [ ] All required fields validate correctly
- [ ] Contact number validates Philippine formats
- [ ] Green checkmarks appear when valid
- [ ] Error messages appear when invalid
- [ ] Progress bar updates in real-time
- [ ] Submit button disabled until valid
- [ ] First invalid field focused on submit
- [ ] Works on mobile browsers
- [ ] Screen reader announces errors
- [ ] Keyboard navigation works

---

## Debugging

### Enable Console Logging
Add debug logging to FormValidator:
```javascript
console.log('Field validation state:', {
    fieldId: fieldId,
    value: field.value,
    isValid: isValid,
    rule: this.validationRules[fieldId]
});
```

### Check Validation State
In browser console:
```javascript
// Check overall form state
window.formValidator.isFormValid()

// Get detailed state
window.formValidator.getValidationState()

// Test specific field
window.formValidator.validateField('contact_number')
```

---

## Future Enhancements

### Potential Improvements
1. **Async validation** - Server-side validation
2. **Conditional fields** - Show/hide based on other fields
3. **Custom validators** - Plugin system for validators
4. **Multi-language** - Localization support
5. **Analytics** - Track validation errors
6. **Undo/Redo** - Form history
7. **Auto-save** - Save form state to localStorage
8. **Password fields** - Strength meter
9. **File uploads** - File type/size validation
10. **Dependent fields** - Cross-field validation

---

## Files Summary

| File | Size | Purpose |
|------|------|---------|
| `js/form-validation.js` | ~345 lines | Main validation logic |
| `css/form-validation.css` | ~320 lines | Styling and animations |
| `index.php` | Modified | Integration points |

---

## References

- [ARIA: alert role](https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/Roles/alert_role)
- [HTML5 Constraint Validation](https://html.spec.whatwg.org/multipage/form-control-infrastructure.html)
- [CSS Animations](https://developer.mozilla.org/en-US/docs/Web/CSS/animation)
- [JavaScript Events](https://developer.mozilla.org/en-US/docs/Web/Events)

---

## Support & Maintenance

### Common Modifications

**Add a new required field:**
1. Add field ID to `requiredFields` array
2. Add validation rule to `setupValidationRules()`
3. Update HTML to include validation indicators
4. Refresh page, validation runs automatically

**Change validation message:**
1. Edit `validationRules[fieldId].message` in `js/form-validation.js`
2. Refresh page

**Change colors:**
1. Edit CSS variables in `css/form-validation.css`
2. Modify `--banner-success-*` or `--banner-error-*` colors
3. Refresh page

**Disable validation for a field:**
1. Remove field ID from `requiredFields` array
2. Refresh page

---

*Last Updated: 2026-01-31*
*Version: 1.0*
*Status: Production Ready*
