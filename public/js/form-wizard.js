document.addEventListener('DOMContentLoaded', function() {
    console.log('Form Wizard JS loaded');
    const form = document.getElementById('membershipForm');
    const pageTitle = document.querySelector('.pageTitle');
    const steps = document.querySelectorAll('.step');
    const contents = document.querySelectorAll('.step-content');
    const prevBtn = document.querySelector('.prev-step');
    const nextBtn = document.querySelector('.next-step');
    const submitBtn = document.querySelector('.submit-form');
    let currentStep = 1;
    let maxStepReached = 1;

    function scrollToPageTitle() {
        if (pageTitle) {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
    function validateCurrentStep() {
        const currentContent = document.querySelector(`.step-content[data-step="${currentStep}"]`);
        const requiredFields = currentContent.querySelectorAll('[required]');
        let isValid = true;
        let firstInvalid = null;

        // Remove existing error messages
        const existingErrors = currentContent.querySelectorAll('.invalid-feedback');
        existingErrors.forEach(error => error.remove());

        // Remove all invalid states
        requiredFields.forEach(field => {
            field.classList.remove('is-invalid');
        });

        // Validate each required field
        requiredFields.forEach(field => {
            let isEmpty = false;
            
            // Check different types of fields
            if (field.tagName === 'SELECT') {
                const selectedOption = field.options[field.selectedIndex];
                isEmpty = !field.value || field.value === "" || (selectedOption && selectedOption.disabled);
            } else if (field.type === 'number') {
                isEmpty = !field.value || field.value <= 0 || isNaN(parseFloat(field.value));
            } else if (field.type === 'tel') {
                isEmpty = !field.value.trim() || !/^[\d\s-+()]*$/.test(field.value);
            } else if (field.name === 'ic_no' || field.name === 'family_ic[]') {
                // Improved IC validation
                const cleanIC = field.value.replace(/\D/g, '');
                isEmpty = !field.value.trim() || cleanIC.length !== 12 || !/^\d{6}-\d{2}-\d{4}$/.test(field.value);
            } else {
                isEmpty = !field.value.trim();
            }

            if (isEmpty) {
                isValid = false;
                field.classList.add('is-invalid');
                
                // Create error message
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                
                // Custom error messages based on field type
                if (field.tagName === 'SELECT') {
                    errorDiv.textContent = 'Sila pilih satu pilihan';
                } else if (field.type === 'number') {
                    errorDiv.textContent = 'Sila masukkan nombor yang sah';
                } else if (field.type === 'tel') {
                    errorDiv.textContent = 'Sila masukkan nombor telefon yang sah';
                } else if (field.name === 'ic_no' || field.name === 'family_ic[]') {
                    errorDiv.textContent = 'Sila masukkan nombor IC yang sah (format: XXXXXX-XX-XXXX)';
                } else {
                    errorDiv.textContent = 'Ruangan ini perlu diisi';
                }
                
                field.insertAdjacentElement('afterend', errorDiv);

                if (!firstInvalid) {
                    firstInvalid = field;
                }
            }
        });

        // Clear validation state when field is changed
        requiredFields.forEach(field => {
            field.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                    const errorMessage = this.nextElementSibling;
                    if (errorMessage?.classList.contains('invalid-feedback')) {
                        errorMessage.remove();
                    }
                }
            });
        });

        // Scroll to first invalid field
        if (firstInvalid) {
            setTimeout(() => {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalid.focus();
            }, 100);
        }

        return isValid;
    }

    function updateStep(step) {
        steps.forEach((s, index) => {
            s.classList.remove('active');
            if (index + 1 <= maxStepReached && index + 1 !== step) {
                s.classList.add('completed');
            } else {
                s.classList.remove('completed');
            }
        });
        steps[step-1].classList.add('active');

        contents.forEach(c => c.classList.remove('active'));
        contents[step-1].classList.add('active');

        prevBtn.style.display = step === 1 ? 'none' : 'block';
        nextBtn.style.display = step === steps.length ? 'none' : 'block';
        submitBtn.style.display = step === steps.length ? 'block' : 'none';

        // Scroll to top of new step
        scrollToPageTitle();
    }

    nextBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (validateCurrentStep()) {
            if (currentStep < steps.length) {
                currentStep++;
                maxStepReached = Math.max(maxStepReached, currentStep);
                updateStep(currentStep);
                scrollToPageTitle();
            }
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentStep > 1) {
            currentStep--;
            updateStep(currentStep);
            scrollToPageTitle();
        }
    });

    steps.forEach((step, index) => {
        step.addEventListener('click', () => {
            const stepNumber = index + 1;
            if (stepNumber <= maxStepReached) {
                if (stepNumber > currentStep) {
                    if (validateCurrentStep()) {
                        currentStep = stepNumber;
                        updateStep(currentStep);
                    }
                } else {
                    currentStep = stepNumber;
                    updateStep(currentStep);
                }
            }
        });
    });

    // Prevent form submission if current step is invalid
    form.addEventListener('submit', (e) => {
        if (!validateCurrentStep()) {
            e.preventDefault();
        }
    });

    // Update the change event listener for real-time validation
    contents.forEach(content => {
        const fields = content.querySelectorAll('[required]');
        fields.forEach(field => {
            field.addEventListener('change', () => {
                if (field.classList.contains('is-invalid')) {
                    let isValid = true;
                    if (field.tagName === 'SELECT') {
                        const selectedOption = field.options[field.selectedIndex];
                        isValid = field.value && field.value !== "" && !(selectedOption && selectedOption.disabled);
                    } else if (field.type === 'number') {
                        isValid = field.value && field.value > 0;
                    } else {
                        isValid = field.value.trim() !== "";
                    }

                    if (isValid) {
                        field.classList.remove('is-invalid');
                        const errorMessage = field.nextElementSibling;
                        if (errorMessage && errorMessage.classList.contains('invalid-feedback')) {
                            errorMessage.remove();
                        }
                    }
                }
            });
        });
    });

    // Add Family Member functionality
    const addFamilyBtn = document.querySelector('.add-family-member');
    const familyContainer = document.querySelector('.family-member-container');
    
    if (addFamilyBtn && familyContainer) {
        addFamilyBtn.addEventListener('click', () => {
            const template = familyContainer.querySelector('.family-member').cloneNode(true);
            
            // Clear input values
            template.querySelectorAll('input').forEach(input => {
                input.value = '';
                // Re-add the IC formatting for new IC input fields
                if (input.name === 'family_ic[]') {
                    input.oninput = function() {
                        let value = this.value.replace(/\D/g, '');
                        value = value.substring(0, 14);
                        if (value.length >= 6) {
                            value = value.substring(0, 6) + '-' + value.substring(6);
                        }
                        if (value.length >= 9) {
                            value = value.substring(0, 9) + '-' + value.substring(9);
                        }
                        this.value = value;
                    };
                }
            });
            
            // Reset select to default
            template.querySelectorAll('select').forEach(select => {
                select.selectedIndex = 0;
            });
            
            // Show remove button
            const removeBtn = template.querySelector('.remove-family');
            removeBtn.style.display = 'block';
            
            // Add remove functionality
            removeBtn.addEventListener('click', () => {
                if (familyContainer.querySelectorAll('.family-member').length > 1) {
                    template.remove();
                }
            });
            
            // Remove any existing error messages in the template
            template.querySelectorAll('.invalid-feedback').forEach(error => error.remove());
            template.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            
            // Add the new family member form
            familyContainer.appendChild(template);
        });
    }

    // Add remove functionality to initial remove button
    const initialRemoveBtn = document.querySelector('.remove-family');
    if (initialRemoveBtn) {
        initialRemoveBtn.addEventListener('click', function() {
            if (familyContainer.querySelectorAll('.family-member').length > 1) {
                this.closest('.family-member').remove();
            }
        });
    }

    // Find the form submission handling code and update it:
    document.getElementById('membershipForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate the form
        if (this.checkValidity()) {
            // Submit the form
            this.submit();
        } else {
            e.stopPropagation();
            this.classList.add('was-validated');
        }
    });
}); 