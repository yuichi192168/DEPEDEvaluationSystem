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

    /* ---------------------- Draft & Autosave ---------------------- */
    saveDraftToLocalStorage() {
        if (!this.form) return;
        const data = {};
        // Serialize all form controls
        const elements = this.form.elements;
        for (let i = 0; i < elements.length; i++) {
            const el = elements[i];
            if (!el.name) continue;
            // Only save text-like inputs, selects, textareas, hidden
            if (['INPUT','SELECT','TEXTAREA'].includes(el.tagName)) {
                if (el.type === 'button' || el.type === 'submit' || el.type === 'reset') continue;
                data[el.name] = el.value;
            }
        }
        data.__saved_at = new Date().toISOString();
        try {
            localStorage.setItem('deped_eval_draft', JSON.stringify(data));
            console.info('Draft saved locally');
            return true;
        } catch (e) {
            console.warn('Failed to save draft locally', e);
            return false;
        }
    }

    restoreDraftFromLocalStorage() {
        try {
            const raw = localStorage.getItem('deped_eval_draft');
            if (!raw) return false;
            const data = JSON.parse(raw);
            if (!data) return false;
            Object.keys(data).forEach(name => {
                if (name === '__saved_at') return;
                const el = this.form.elements[name];
                if (el) {
                    try { el.value = data[name]; } catch (e) { /* ignore */ }
                    el.dispatchEvent(new Event('input'));
                    el.dispatchEvent(new Event('change'));
                }
            });
            console.info('Draft restored from localStorage');
            return true;
        } catch (e) {
            console.warn('Failed to restore draft', e);
            return false;
        }
    }

    scheduleAutoSave(delay = 1200) {
        if (this._autosaveTimer) clearTimeout(this._autosaveTimer);
        this._autosaveTimer = setTimeout(() => {
            this.saveDraftToLocalStorage();
        }, delay);
    }

    startAutoSave(intervalMs = 15000) {
        if (this._autoSaveInterval) clearInterval(this._autoSaveInterval);
        this._autoSaveInterval = setInterval(() => {
            this.saveDraftToLocalStorage();
        }, intervalMs);
    }

    stopAutoSave() {
        if (this._autoSaveInterval) clearInterval(this._autoSaveInterval);
        if (this._autosaveTimer) clearTimeout(this._autosaveTimer);
    }

    /* ---------------------- Confirmation Modal & Checklist ---------------------- */
    showConfirmationModal(actionLabel = 'Proceed', onConfirm = null) {
        const modal = document.getElementById('confirmationModal');
        const checklist = document.getElementById('confirmationChecklist');
        const text = document.getElementById('confirmationText');
        if (!modal || !checklist || !text) return;

        // Fill text
        text.textContent = `You are about to ${actionLabel}. Please review the checklist below before continuing.`;

        // Build checklist from validation state
        const state = this.getValidationState();
        checklist.innerHTML = '';
        this.requiredFields.forEach(fieldId => {
            const st = state[fieldId] || { isValid: false, isEmpty: true };
            const li = document.createElement('li');
            li.className = st.isValid ? 'complete' : 'incomplete';
            const dot = document.createElement('span');
            dot.className = 'dot';
            const label = document.createElement('span');
            const friendly = (this.validationRules[fieldId] && this.validationRules[fieldId].fieldName) || fieldId;
            label.textContent = ` ${friendly} ${st.isValid ? '(complete)' : '(incomplete)'}`;
            li.appendChild(dot);
            li.appendChild(label);
            checklist.appendChild(li);
        });

        // Show modal and wire confirm/cancel
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');

        const cancel = document.getElementById('confirmationCancel');
        const confirm = document.getElementById('confirmationConfirm');

        const cleanup = () => {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            confirm.removeEventListener('click', onClickConfirm);
            cancel.removeEventListener('click', onClickCancel);
        };

        const onClickConfirm = () => {
            cleanup();
            if (typeof onConfirm === 'function') onConfirm();
        };

        const onClickCancel = () => {
            cleanup();
        };

        confirm.addEventListener('click', onClickConfirm);
        cancel.addEventListener('click', onClickCancel);
    }

    /* ---------------------- Attach action handlers for buttons ---------------------- */
    attachActionHandlers() {
        // Save draft (button inside form)
        const saveBtn = document.getElementById('save_draft_btn');
        if (saveBtn) saveBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const ok = this.saveDraftToLocalStorage();
            if (ok) alert('Draft saved locally.'); else alert('Failed to save draft locally.');
        });

        // Generate report button (show confirmation checklist first)
        const genBtn = document.getElementById('generate_report_btn');
        if (genBtn) genBtn.addEventListener('click', (e) => {
            // If disabled, do nothing
            if (genBtn.disabled) { e.preventDefault(); return; }
            e.preventDefault();
            this.showConfirmationModal('generate the Evaluation Report', () => {
                // Submit the form
                this.saveDraftToLocalStorage(); // final local save before submit
                this.form.submit();
            });
        });

        // Generate CAR button
        const carBtn = document.getElementById('generate_car_btn');
        if (carBtn) carBtn.addEventListener('click', (e) => {
            if (carBtn.disabled) { e.preventDefault(); return; }
            e.preventDefault();
            this.showConfirmationModal('generate the Comparative Assessment (CAR)', () => {
                // On confirm, save draft and navigate to CAR page
                this.saveDraftToLocalStorage();
                window.location.href = 'comparative_assessment_results.php?generate_from_form=1';
            });
        });

        // Sticky action bar buttons mirror main buttons (and include reset/view)
        const stickySave = document.getElementById('sticky_save_draft');
        if (stickySave) stickySave.addEventListener('click', () => document.getElementById('save_draft_btn').click());
        const stickyGen = document.getElementById('sticky_generate_report');
        if (stickyGen) stickyGen.addEventListener('click', () => document.getElementById('generate_report_btn').click());
        const stickyCAR = document.getElementById('sticky_generate_car');
        if (stickyCAR) stickyCAR.addEventListener('click', () => document.getElementById('generate_car_btn').click());
        const stickyReset = document.getElementById('sticky_reset');
        if (stickyReset) stickyReset.addEventListener('click', () => { try { resetForm(); } catch(e){ const frm = document.getElementById('evaluationForm'); if(frm) frm.reset(); } });
        const stickyView = document.getElementById('sticky_view_results');
        if (stickyView) stickyView.addEventListener('click', () => { window.location.href = 'comparative_assessment_results.php?view=all'; });

        // Help drawer toggles
        const helpToggle = document.getElementById('helpToggle');
        const helpOpen = document.getElementById('help_open');
        const helpDrawer = document.getElementById('helpDrawer');
        const helpClose = document.getElementById('helpClose');
        const helpBtnTop = document.getElementById('helpToggle');
        if (helpToggle) {
            helpToggle.addEventListener('click', () => {
                helpDrawer.classList.toggle('open');
                const open = helpDrawer.classList.contains('open');
                helpToggle.setAttribute('aria-pressed', String(open));
                helpDrawer.setAttribute('aria-hidden', String(!open));
            });
        }
        if (helpOpen) helpOpen.addEventListener('click', () => helpToggle.click());
        if (helpClose) helpClose.addEventListener('click', () => helpToggle.click());
    }
}

// Initialize validator when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.formValidator = new FormValidator('evaluationForm');
    // Restore draft if present
    try { window.formValidator.restoreDraftFromLocalStorage(); } catch (e) { /* ignore */ }
    // Start periodic autosave
    try { window.formValidator.startAutoSave(15000); } catch (e) { /* ignore */ }
    // Attach handlers for new action buttons and help drawer
    try { window.formValidator.attachActionHandlers(); } catch (e) { /* ignore */ }

    // Wire lightweight autosave on input change
    const inputs = window.formValidator.form ? Array.from(window.formValidator.form.querySelectorAll('input,select,textarea')) : [];
    inputs.forEach(el => {
        el.addEventListener('input', () => window.formValidator.scheduleAutoSave(1200));
        el.addEventListener('change', () => window.formValidator.scheduleAutoSave(800));
    });
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FormValidator;
}
