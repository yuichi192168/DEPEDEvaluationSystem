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
        this._appCodeEditedManually = false;
        this._duplicateCheckDebounce = null;
        this._autoGenerateInFlight = false;
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

        // Remote field-level checks (debounced)
        const appCodeEl = document.getElementById('application_code');
        if (appCodeEl) {
            let deb = null;
            appCodeEl.addEventListener('input', () => {
                this._appCodeEditedManually = true;
                if (deb) clearTimeout(deb);
                deb = setTimeout(() => this.checkDuplicateCombination(), 450);
            });
        }

        const applicantNameEl = document.getElementById('applicant_name');
        if (applicantNameEl) {
            applicantNameEl.addEventListener('input', () => this.scheduleDuplicateCombinationCheck(450));
            applicantNameEl.addEventListener('change', () => this.scheduleDuplicateCombinationCheck(0));
        }

        const positionSelectors = ['position_key', 'position_group_select', 'position_applied'];
        positionSelectors.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            el.addEventListener('change', () => this.generateAndSetApplicationCode(true));
            el.addEventListener('input', () => this.scheduleAutoCodeGeneration());
        });
    }

    scheduleAutoCodeGeneration(delay = 300) {
        if (this._autoCodeTimer) clearTimeout(this._autoCodeTimer);
        this._autoCodeTimer = setTimeout(() => this.generateAndSetApplicationCode(true), delay);
    }

    scheduleDuplicateCombinationCheck(delay = 300) {
        if (this._duplicateCheckDebounce) clearTimeout(this._duplicateCheckDebounce);
        this._duplicateCheckDebounce = setTimeout(() => this.checkDuplicateCombination(), delay);
    }

    extractPositionGroupName() {
        const posKeyEl = document.getElementById('position_key');
        const selectedKey = posKeyEl ? posKeyEl.value : '';

        if (selectedKey && selectedKey !== 'custom' && typeof positions !== 'undefined' && positions[selectedKey] && positions[selectedKey].position_group) {
            return String(positions[selectedKey].position_group);
        }

        const groupSelect = document.getElementById('position_group_select');
        if (groupSelect && groupSelect.selectedIndex > 0) {
            const option = groupSelect.options[groupSelect.selectedIndex];
            if (option && option.textContent) return option.textContent.trim();
        }

        const groupLevelEl = document.getElementById('job_group_sg_level');
        const groupLevelVal = groupLevelEl ? String(groupLevelEl.value || '') : '';
        const match = groupLevelVal.match(/Group\s+(.+?)\s*\/\s*Salary\s*Grade/i);
        if (match && match[1]) return match[1].trim();

        return '';
    }

    async generateAndSetApplicationCode(force = false) {
        if (this._autoGenerateInFlight) return;

        const appCodeEl = document.getElementById('application_code');
        const positionTitleEl = document.getElementById('position_applied');
        if (!appCodeEl || !positionTitleEl) return;

        const positionGroupName = this.extractPositionGroupName();
        const positionTitle = (positionTitleEl.value || '').trim();

        if (!positionGroupName || !positionTitle) return;
        if (!force && this._appCodeEditedManually && appCodeEl.value.trim() !== '') return;

        this._autoGenerateInFlight = true;
        try {
            const params = new URLSearchParams({
                position_group_name: positionGroupName,
                position_title: positionTitle,
                year: String(new Date().getFullYear())
            });
            const resp = await fetch(`api/generate_application_code.php?${params.toString()}`);
            const json = await resp.json();
            if (json && json.success && json.application_code) {
                appCodeEl.value = json.application_code;
                appCodeEl.dispatchEvent(new Event('input'));
                appCodeEl.dispatchEvent(new Event('change'));
                this._appCodeEditedManually = false;
            }
        } catch (e) {
            console.warn('Auto code generation failed', e);
        } finally {
            this._autoGenerateInFlight = false;
        }
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
        const defaultMessage = (rule && rule.message) ? rule.message : 'Please check this field';

        if (!errorElement) {
            const field = document.getElementById(fieldId);
            const wrapper = field.parentElement;
            errorElement = document.createElement('div');
            errorElement.id = errorId;
            errorElement.className = 'field-error';
            errorElement.setAttribute('role', 'alert');
            wrapper.appendChild(errorElement);
        }

        errorElement.textContent = defaultMessage;
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
            console.log('Form validation failed - checking required fields:');
            this.requiredFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    const value = field.value.trim();
                    const isValid = this.validateField(fieldId);
                    console.log(`  ${fieldId}: ${value ? '"' + value + '"' : 'EMPTY'} - ${isValid ? 'VALID' : 'INVALID'}`);
                }
            });
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
            // Also save to IndexedDB for offline resilience
            try { this.idbSaveDraft(data); } catch(e) { console.warn('IDB save failed', e); }
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
            // Try IndexedDB fallback
            try { return this.idbRestoreDraft(); } catch (ie) { return false; }
        }
    }

    /* ------------------- IndexedDB helpers ------------------- */
    idbOpen() {
        return new Promise((resolve, reject) => {
            const req = indexedDB.open('deped_eval_db', 1);
            req.onupgradeneeded = function(e) {
                const db = e.target.result;
                if (!db.objectStoreNames.contains('drafts')) db.createObjectStore('drafts', { keyPath: 'id', autoIncrement: true });
            };
            req.onsuccess = function() { resolve(req.result); };
            req.onerror = function() { reject(req.error); };
        });
    }

    async idbSaveDraft(data) {
        try {
            const db = await this.idbOpen();
            const tx = db.transaction('drafts','readwrite');
            const store = tx.objectStore('drafts');
            store.put({ data: data, saved_at: new Date().toISOString() });
            return true;
        } catch (e) {
            console.warn('idbSaveDraft error', e); return false;
        }
    }

    async idbRestoreDraft() {
        try {
            const db = await this.idbOpen();
            const tx = db.transaction('drafts','readonly');
            const store = tx.objectStore('drafts');
            const req = store.openCursor(null, 'prev');
            return new Promise((resolve) => {
                req.onsuccess = (ev) => {
                    const cursor = ev.target.result;
                    if (cursor && cursor.value && cursor.value.data) {
                        const data = cursor.value.data;
                        Object.keys(data).forEach(name => {
                            if (name === '__saved_at') return;
                            const el = this.form.elements[name];
                            if (el) {
                                try { el.value = data[name]; } catch (e){}
                                el.dispatchEvent(new Event('input'));
                                el.dispatchEvent(new Event('change'));
                            }
                        });
                        resolve(true);
                    } else {
                        resolve(false);
                    }
                };
                req.onerror = () => resolve(false);
            });
        } catch (e) { console.warn('idbRestoreDraft error', e); return false; }
    }

    /* ------------------- Undo / Reset support ------------------- */
    prepareUndoAndReset() {
        if (!this.form) return;
        // Capture current state
        const snapshot = {};
        const elements = this.form.elements;
        for (let i = 0; i < elements.length; i++) {
            const el = elements[i];
            if (!el.name) continue;
            if (['INPUT','SELECT','TEXTAREA'].includes(el.tagName)) {
                if (el.type === 'button' || el.type === 'submit' || el.type === 'reset') continue;
                snapshot[el.name] = el.value;
            }
        }

        // Store temporarily
        this._lastSnapshot = snapshot;

        // Perform actual reset
        try { this.form.reset(); } catch (e) {}
        document.getElementById('baselineInfo').style.display = 'none';
        document.getElementById('livePreview').classList.remove('active');

        // Show undo bar
        this.showUndoBar();
    }

    showUndoBar() {
        let bar = document.getElementById('undoBar');
        if (!bar) {
            bar = document.createElement('div');
            bar.id = 'undoBar';
            bar.style.position = 'fixed';
            bar.style.bottom = '86px';
            bar.style.right = '18px';
            bar.style.zIndex = 1400;
            bar.innerHTML = `<div style="background:#fff;padding:10px;border-radius:6px;box-shadow:0 6px 18px rgba(0,0,0,0.12);display:flex;gap:8px;align-items:center;"><span style="font-weight:600;color:#333">Form cleared</span><button id="undoRestoreBtn" class="btn-secondary">Undo</button><button id="undoDismissBtn" class="btn-secondary">Dismiss</button></div>`;
            document.body.appendChild(bar);
        }

        const undo = document.getElementById('undoRestoreBtn');
        const dismiss = document.getElementById('undoDismissBtn');
        undo.addEventListener('click', () => this.restoreLastSnapshot());
        dismiss.addEventListener('click', () => { try { bar.remove(); } catch(e){} });

        // Auto-hide after 20s
        setTimeout(() => { try { bar.remove(); } catch(e){} }, 20000);
    }

    restoreLastSnapshot() {
        if (!this._lastSnapshot) return;
        const snap = this._lastSnapshot;
        Object.keys(snap).forEach(name => {
            const el = this.form.elements[name];
            if (el) {
                try { el.value = snap[name]; } catch (e) {}
                el.dispatchEvent(new Event('input'));
                el.dispatchEvent(new Event('change'));
            }
        });
        try { const bar = document.getElementById('undoBar'); if(bar) bar.remove(); } catch(e){}
        this._lastSnapshot = null;
        this.showBanner('success', 'Form restored');
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
        const positionInfo = document.getElementById('confirmationPositionInfo');
        const applicantInfo = document.getElementById('confirmationApplicantInfo');
        if (!modal || !checklist || !text || !positionInfo || !applicantInfo) return;

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

        const read = (id) => {
            const el = document.getElementById(id);
            return el ? String(el.value || '').trim() : '';
        };
        const safe = (v) => v ? escapeHtml(v) : '<em>Not set</em>';
        const buildSummaryTable = (rows) => {
            const body = rows.map(([label, value]) => {
                return `<tr><th scope="row">${escapeHtml(label)}</th><td>${safe(value)}</td></tr>`;
            }).join('');
            return `<table class="confirmation-table"><tbody>${body}</tbody></table>`;
        };

        positionInfo.innerHTML = buildSummaryTable([
            ['Position Group', this.extractPositionGroupName()],
            ['Position Applied', read('position_applied')],
            ['Job Group / SG', read('job_group_sg_level')],
            ['Application Code', read('application_code')],
            ['Applicant Name', read('applicant_name')],
            ['Total Preview Score', (document.getElementById('totalScore') || {}).textContent || '']
        ]);

        applicantInfo.innerHTML = buildSummaryTable([
            ['Education Level', read('applicant_education_dropdown')],
            ['Training Level', read('applicant_training_dropdown')],
            ['Experience Level', read('applicant_experience_dropdown')],
            ['Performance', read('applicant_performance')],
            ['Outstanding Accomplishments', read('applicant_outstanding_accomplishments')],
            ['Application of Education', read('applicant_application_of_education')],
            ['Application of L&D', read('applicant_application_of_ld')],
            ['Potential', read('applicant_potential')]
        ]);

        // Show modal and wire confirm/cancel
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');

        const cancel = document.getElementById('confirmationCancel');
        const confirm = document.getElementById('confirmationConfirm');

        const cleanup = () => {
            // Remove focus from any element to prevent aria-hidden warning
            if (document.activeElement && document.activeElement !== document.body) {
                document.activeElement.blur();
            }
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
            if (ok) {
                // Try server save as well
                this.postDraftToServer();
                this.showBanner('success', 'Draft saved locally');
            } else {
                this.showBanner('error', 'Failed to save draft locally');
            }
        });

        // Generate report button (show confirmation checklist first)
        const genBtn = document.getElementById('generate_report_btn');
        if (genBtn) genBtn.addEventListener('click', async (e) => {
            // If disabled, do nothing
            if (genBtn.disabled) { e.preventDefault(); return; }
            e.preventDefault();
            // Perform server-side validation before confirmation
            const valid = await this.validateFieldsServer();
            if (!valid) {
                this.showBanner('error', 'Please fix validation errors before proceeding');
                return;
            }
            await this.generateAndSetApplicationCode(true);
            const duplicateFound = await this.checkDuplicateCombination(true);
            if (duplicateFound) {
                return;
            }
            this.showConfirmationModal('generate the Evaluation Report', () => {
                // Submit the form
                this.saveDraftToLocalStorage(); // final local save before submit
                this.postDraftToServer();
                this.form.submit();
            });
        });

        // Generate CAR button
        const carBtn = document.getElementById('generate_car_btn');
        if (carBtn) carBtn.addEventListener('click', async (e) => {
            if (carBtn.disabled) { e.preventDefault(); return; }
            e.preventDefault();
            const valid = await this.validateFieldsServer();
            if (!valid) {
                this.showBanner('error', 'Please fix validation errors before proceeding');
                return;
            }
            await this.generateAndSetApplicationCode(true);
            const duplicateFound = await this.checkDuplicateCombination(true);
            if (duplicateFound) {
                return;
            }
            this.showConfirmationModal('generate the Comparative Assessment (CAR)', () => {
                // On confirm, save draft and navigate to CAR page
                this.saveDraftToLocalStorage();
                this.postDraftToServer();
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
        const setHelpToggleState = (open) => {
            if (!helpToggle || !helpDrawer) return;
            helpToggle.setAttribute('aria-pressed', String(open));
            helpDrawer.setAttribute('aria-hidden', String(!open));
            helpToggle.textContent = open ? '\u00d7' : '?';
            helpToggle.title = open ? 'Close help' : 'Help';
        };
        if (helpToggle) {
            helpToggle.addEventListener('click', () => {
                helpDrawer.classList.toggle('open');
                const open = helpDrawer.classList.contains('open');
                setHelpToggleState(open);
            });
            setHelpToggleState(helpDrawer ? helpDrawer.classList.contains('open') : false);
        }
        if (helpOpen) helpOpen.addEventListener('click', () => helpToggle.click());
        if (helpClose) helpClose.addEventListener('click', () => helpToggle.click());

        // Compact sticky bar toggle
        const stickyBar = document.querySelector('.sticky-action-bar');
        if (stickyBar) {
            // Add compact toggle button
            const inner = stickyBar.querySelector('.bar-inner');
            if (inner && !inner.querySelector('.compact-toggle')) {
                const t = document.createElement('button');
                t.className = 'compact-toggle';
                t.title = 'Toggle actions';
                t.innerHTML = '≡';
                t.addEventListener('click', () => stickyBar.classList.toggle('compact'));
                inner.insertBefore(t, inner.firstChild);
            }
        }
    }

    /* ------------------- Banner / Toast helpers ------------------- */
    showBanner(type, message) {
        // Use existing banner css classes
        const container = document.createElement('div');
        container.className = 'banner banner-' + (type === 'error' ? 'error' : (type === 'warning' ? 'warning' : (type === 'processing' ? 'processing' : 'success')) ) + ' auto-hide';
        container.innerHTML = `<div class="banner-content"><span class="banner-icon">${type === 'error' ? '✕' : (type === 'warning' ? '⚠' : (type === 'processing' ? '⟳' : '✓'))}</span><span class="banner-text">${message}</span><button class="banner-close" aria-label="Close">&times;</button></div>`;
        document.body.insertBefore(container, document.body.firstChild);
        const closeBtn = container.querySelector('.banner-close');
        if (closeBtn) closeBtn.addEventListener('click', () => container.remove());
        // Auto-remove after 6s
        setTimeout(() => { try { container.remove(); } catch(e){} }, 6400);
    }

    /* ------------------- Post draft to server ------------------- */
    async postDraftToServer() {
        if (!this.form) return;
        const data = {};
        const elements = this.form.elements;
        for (let i = 0; i < elements.length; i++) {
            const el = elements[i];
            if (!el.name) continue;
            if (['INPUT','SELECT','TEXTAREA'].includes(el.tagName)) {
                if (el.type === 'button' || el.type === 'submit' || el.type === 'reset') continue;
                data[el.name] = el.value;
            }
        }

        try {
            const resp = await fetch('api/save_draft.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const json = await resp.json();
            if (json && json.success) {
                this.showBanner('success', 'Draft synced to server');
            } else {
                this.showBanner('warning', 'Draft saved locally (server sync failed)');
            }
        } catch (e) {
            this.showBanner('warning', 'Draft saved locally (server unreachable)');
            try { this.requestBackgroundSync(); } catch(err){}
        }
    }

    async checkDuplicateCombination(showBanner = false) {
        const codeEl = document.getElementById('application_code');
        const nameEl = document.getElementById('applicant_name');
        if (!codeEl || !nameEl) return false;

        const code = codeEl.value.trim();
        const applicantName = nameEl.value.trim();
        if (!code) return false;

        try {
            const params = new URLSearchParams({
                application_code: code,
                applicant_name: applicantName
            });
            const resp = await fetch(`api/check_duplicate_application.php?${params.toString()}`);
            const json = await resp.json();

            if (json && json.success && json.name_code_exists) {
                this.showFieldError('application_code');
                const errEl = document.getElementById('application_code_error');
                if (errEl) errEl.textContent = 'Duplicate applicant name and application code found';
                nameEl.classList.add('invalid');
                codeEl.classList.add('invalid');

                nameEl.focus();
                nameEl.scrollIntoView({ behavior: 'smooth', block: 'center' });

                if (showBanner) {
                    this.showBanner('error', 'Duplicate found: Applicant Name and Application Code already exist.');
                }
                return true;
            }

            if (json && json.success && json.code_exists) {
                this.showFieldError('application_code');
                const errEl = document.getElementById('application_code_error');
                if (errEl) errEl.textContent = 'Application code already exists';
                codeEl.classList.add('invalid');
                if (showBanner) {
                    this.showBanner('warning', 'Application code already exists. A new code will be generated.');
                }
            } else {
                this.clearFieldError('application_code');
                codeEl.classList.remove('invalid');
                nameEl.classList.remove('invalid');
            }
        } catch (e) {
            // ignore remote check failures
        }
        return false;
    }

    async validateFieldsServer() {
        if (!this.form) return false;
        const data = {};
        const elements = this.form.elements;
        for (let i = 0; i < elements.length; i++) {
            const el = elements[i];
            if (!el.name) continue;
            if (['INPUT','SELECT','TEXTAREA'].includes(el.tagName)) {
                if (el.type === 'button' || el.type === 'submit' || el.type === 'reset') continue;
                data[el.name] = el.value;
            }
        }

        try {
            const resp = await fetch('api/validate_fields.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const json = await resp.json();
            if (json && json.valid) {
                return true;
            }
            if (json && json.errors) {
                Object.keys(json.errors).forEach(fieldId => {
                    const el = document.getElementById(fieldId);
                    if (el) {
                        this.showFieldError(fieldId);
                        const errEl = document.getElementById(fieldId + '_error');
                        if (errEl) errEl.textContent = json.errors[fieldId];
                    }
                });
            }
            return false;
        } catch (e) {
            // If server validation fails, fallback to client validation
            console.warn('Server validation failed', e);
            return this.validateForm();
        }
    }
}

// Initialize validator when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.formValidator = new FormValidator('evaluationForm');
    // Restore draft if present
    try { window.formValidator.restoreDraftFromLocalStorage(); } catch (e) { /* ignore */ }
    // Auto-generate application code from selected position context
    try { window.formValidator.generateAndSetApplicationCode(true); } catch (e) { /* ignore */ }
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

    // Wire Load Drafts sticky button
    const stickyLoad = document.getElementById('sticky_load_drafts');
    if (stickyLoad) stickyLoad.addEventListener('click', () => window.formValidator.openDraftsModal());

    // Register a message listener for modal close actions
    document.addEventListener('click', (e) => {
        const tgt = e.target;
        if (tgt && tgt.id === 'draftsClose') {
            const m = document.getElementById('draftsModal'); if (m) { m.style.display = 'none'; m.setAttribute('aria-hidden','true'); }
        }
    });

    // Ensure background sync is requested when drafts are saved but network failed
    try { if (!navigator.serviceWorker) { /* noop */ } } catch(e) {}
});

/* ------------------- Drafts UI & Background Sync helpers (prototype additions) ------------------- */

FormValidator.prototype.openDraftsModal = async function() {
    const modal = document.getElementById('draftsModal');
    const listEl = document.getElementById('draftsList');
    if (!modal || !listEl) return;
    modal.style.display = 'block';
    modal.setAttribute('aria-hidden','false');
    listEl.innerHTML = 'Loading...';
    try {
        const resp = await fetch('api/drafts_list.php');
        const json = await resp.json();
        if (!json || !json.success) { listEl.innerHTML = '<div>No drafts available</div>'; return; }
        const items = json.drafts || [];
        if (items.length === 0) { listEl.innerHTML = '<div>No drafts available</div>'; return; }
        listEl.innerHTML = '';
        items.forEach(d => {
            const row = document.createElement('div');
            row.className = 'draft-row';
            const name = d.applicant_name || d.application_code || ('Draft #' + d.id);
            const stamp = d.saved_at || d.created_at || '';
            const title = stamp ? (name + ' — ' + stamp) : name;
            row.innerHTML = `<div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid #eee;"><div style="flex:1">${escapeHtml(title)}</div><div style="margin-left:12px"><button class="btn-secondary load-draft-btn" data-id="${d.id}">Load</button></div></div>`;
            listEl.appendChild(row);
        });
        // Attach handlers
        listEl.querySelectorAll('.load-draft-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = parseInt(btn.getAttribute('data-id')) || 0;
                if (id) this.loadDraftById(id);
            });
        });
    } catch (e) {
        listEl.innerHTML = '<div>Error loading drafts</div>';
    }
};

FormValidator.prototype.loadDraftById = async function(id) {
    try {
        const resp = await fetch('api/drafts_load.php?id=' + encodeURIComponent(id));
        const json = await resp.json();
        if (!json || !json.success) { this.showBanner('error', 'Failed to load draft'); return; }
        const data = json.data || {};
        // Write to localStorage and restore
        try { localStorage.setItem('deped_eval_draft', JSON.stringify(data)); } catch(e){}
        const modal = document.getElementById('draftsModal'); if (modal) { modal.style.display = 'none'; modal.setAttribute('aria-hidden','true'); }
        // Restore directly into form
        Object.keys(data).forEach(name => {
            if (name === '__saved_at') return;
            const el = this.form.elements[name];
            if (el) { try { el.value = data[name]; } catch(e){} el.dispatchEvent(new Event('input')); el.dispatchEvent(new Event('change')); }
        });
        this.showBanner('success', 'Draft loaded');
    } catch (e) {
        this.showBanner('error', 'Failed to load draft');
    }
};

FormValidator.prototype.requestBackgroundSync = function() {
    if (!('serviceWorker' in navigator)) return;
    navigator.serviceWorker.ready.then(reg => {
        if (reg && reg.sync && typeof reg.sync.register === 'function') {
            reg.sync.register('sync-drafts').catch(()=>{});
        } else if (reg && reg.active) {
            try { reg.active.postMessage({ type: 'registerSync' }); } catch(e){}
        }
    }).catch(()=>{});
};

// Escape helper for safe text insertion
function escapeHtml(s) {
    if (!s) return '';
    return String(s).replace(/[&"'<>]/g, function (m) { return {'&':'&amp;','"':'&quot;','\'':'&#39;','<':'&lt;','>':'&gt;'}[m]; });
}


// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FormValidator;
}
