/**
 * Form Validation System
 * Provides real-time validation for DepEd HRMPSB Evaluation System
 * Includes Philippine contact number validation and required field checking
 */

class FormValidator {
    constructor(formId = 'evaluationForm') {
        this.form = document.getElementById(formId);
        this.requiredFields = [
            'applicant_name',
            'contact_number',
            'position_applied',
            'schools_division_office',
            'job_group_sg_level'
        ];
        this.validationRules = {};
        this.isValid = false;
        this.init();
    }

    init() {
        if (!this.form) return;
        
        // Setup validation rules
        this.setupValidationRules();
        
        // Attach event listeners
        this.attachEventListeners();
        
        // Initial validation
        this.validateForm();
    }

    setupValidationRules() {
        this.validationRules = {
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
        };
    }

    attachEventListeners() {
        // Validate on input
        this.requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', () => this.handleFieldChange(fieldId));
                field.addEventListener('blur', () => this.handleFieldBlur(fieldId));
                field.addEventListener('change', () => this.handleFieldChange(fieldId));
            }
        });

        // Form submission
        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        // Watch for changes to update validation state
        document.addEventListener('fieldUpdated', () => {
            this.validateForm();
        });
    }

    /**
     * Philippine Contact Number Validation
     * Accepts: 09XXXXXXXXX or +639XXXXXXXXX
     */
    validatePhilippineContactNumber(value) {
        if (!value) return false;
        
        // Remove spaces and hyphens
        const cleaned = value.replace(/[\s\-]/g, '');
        
        // Check format: 09XXXXXXXXX (11 digits) or +639XXXXXXXXX (12 chars)
        const format1 = /^09\d{9}$/;  // 09XXXXXXXXX
        const format2 = /^\+639\d{9}$/; // +639XXXXXXXXX
        
        return format1.test(cleaned) || format2.test(cleaned);
    }

    handleFieldChange(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        const isValid = this.validateField(fieldId);
        this.updateFieldIndicator(fieldId, isValid);
        this.validateForm();
    }

    handleFieldBlur(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        const isValid = this.validateField(fieldId);
        this.updateFieldIndicator(fieldId, isValid);
        
        // Show error message on blur if invalid
        if (!isValid && field.value.trim().length > 0) {
            this.showFieldError(fieldId);
        } else {
            this.clearFieldError(fieldId);
        }
        
        this.validateForm();
    }

    validateField(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return false;

        const rule = this.validationRules[fieldId];
        if (!rule) return true; // If no rule, consider valid

        return rule.validate(field.value);
    }

    updateFieldIndicator(fieldId, isValid) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        const indicator = document.getElementById(`${fieldId}_valid_indicator`);
        
        // Only show check if field has value and is valid
        if (isValid && field.value.trim().length > 0) {
            field.classList.remove('invalid');
            field.classList.add('valid');
            if (indicator) {
                indicator.style.display = 'inline-block';
            }
        } else {
            field.classList.remove('valid');
            if (field.value.trim().length > 0) {
                field.classList.add('invalid');
            }
            if (indicator) {
                indicator.style.display = 'none';
            }
        }
    }

    showFieldError(fieldId) {
        const errorId = `${fieldId}_error`;
        let errorElement = document.getElementById(errorId);

        const rule = this.validationRules[fieldId];
        if (!rule) return;

        if (!errorElement) {
            const field = document.getElementById(fieldId);
            const wrapper = field.parentElement;
            errorElement = document.createElement('div');
            errorElement.id = errorId;
            errorElement.className = 'field-error';
            errorElement.setAttribute('role', 'alert');
            wrapper.appendChild(errorElement);
        }

        errorElement.textContent = rule.message;
        errorElement.style.display = 'block';
    }

    clearFieldError(fieldId) {
        const errorElement = document.getElementById(`${fieldId}_error`);
        if (errorElement) {
            errorElement.style.display = 'none';
        }
    }

    validateForm() {
        let allValid = true;

        this.requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && field.value.trim().length > 0) {
                const isValid = this.validateField(fieldId);
                if (!isValid) {
                    allValid = false;
                }
            } else if (field) {
                // Required field is empty
                allValid = false;
            }
        });

        this.isValid = allValid;
        this.updateProgressIndicator();
        this.updateButtonStates();

        return allValid;
    }

    updateProgressIndicator() {
        const progressElement = document.getElementById('form_progress');
        if (!progressElement) return;

        let completedCount = 0;
        this.requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field && field.value.trim().length > 0 && this.validateField(fieldId)) {
                completedCount++;
            }
        });

        const totalRequired = this.requiredFields.length;
        const percentage = Math.round((completedCount / totalRequired) * 100);

        // Update progress bar
        const progressBar = progressElement.querySelector('.progress-fill');
        if (progressBar) {
            progressBar.style.width = percentage + '%';
        }

        // Update text
        const progressText = progressElement.querySelector('.progress-text');
        if (progressText) {
            progressText.textContent = `${completedCount} of ${totalRequired} required fields completed`;
        }

        // Add/remove completed class
        if (completedCount === totalRequired && this.isValid) {
            progressElement.classList.add('completed');
        } else {
            progressElement.classList.remove('completed');
        }
    }

    updateButtonStates() {
        // Disable/enable action buttons based on form validity
        const buttons = document.querySelectorAll('.action-button');
        buttons.forEach(button => {
            if (this.isValid) {
                button.disabled = false;
                button.classList.remove('disabled');
            } else {
                button.disabled = true;
                button.classList.add('disabled');
            }
        });
    }

    handleFormSubmit(e) {
        if (!this.isValid) {
            e.preventDefault();
            this.showValidationErrors();
            this.scrollToFirstInvalidField();
            return false;
        }
    }

    showValidationErrors() {
        let firstInvalid = null;

        this.requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field) return;

            if (field.value.trim().length === 0 || !this.validateField(fieldId)) {
                field.classList.add('invalid');
                this.showFieldError(fieldId);
                
                if (!firstInvalid) {
                    firstInvalid = field;
                }
            }
        });
    }

    scrollToFirstInvalidField() {
        let firstInvalid = null;

        for (const fieldId of this.requiredFields) {
            const field = document.getElementById(fieldId);
            if (field && (field.value.trim().length === 0 || !this.validateField(fieldId))) {
                firstInvalid = field;
                break;
            }
        }

        if (firstInvalid) {
            firstInvalid.focus();
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    isFormValid() {
        return this.isValid;
    }

    getValidationState() {
        const state = {};
        this.requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                state[fieldId] = {
                    value: field.value,
                    isValid: this.validateField(fieldId),
                    isEmpty: field.value.trim().length === 0
                };
            }
        });
        return state;
    }
}

// Initialize validator when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.formValidator = new FormValidator('evaluationForm');
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FormValidator;
}
