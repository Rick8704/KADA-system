document.addEventListener('DOMContentLoaded', function() {
    const gradeInput = document.getElementById('gradeInput');
    const gradeSearch = document.getElementById('gradeSearch');
    const gradeSelect = document.getElementById('gradeSelect');
    const selectButton = document.getElementById('selectGradeBtn');
    const gradeModal = new bootstrap.Modal(document.getElementById('gradeModal'));

    // Show modal when clicking input
    gradeInput.addEventListener('click', function() {
        gradeModal.show();
    });

    // Handle grade selection
    selectButton.addEventListener('click', function() {
        const selectedOption = gradeSelect.options[gradeSelect.selectedIndex];
        if (selectedOption && !selectedOption.disabled) {
            gradeInput.value = selectedOption.value;
            gradeModal.hide();
            
            // Remove any validation errors
            gradeInput.classList.remove('is-invalid');
            const errorMessage = gradeInput.nextElementSibling;
            if (errorMessage?.classList.contains('invalid-feedback')) {
                errorMessage.remove();
            }
        }
    });

    // Filter grades
    gradeSearch.addEventListener('input', function() {
        const searchText = this.value.toLowerCase();
        Array.from(gradeSelect.options).forEach(option => {
            if (option.disabled) return;
            const text = option.text.toLowerCase();
            option.style.display = text.includes(searchText) ? '' : 'none';
        });
    });

    // Reset search when modal is hidden
    document.getElementById('gradeModal').addEventListener('hidden.bs.modal', function() {
        gradeSearch.value = '';
        Array.from(gradeSelect.options).forEach(option => {
            option.style.display = '';
        });
    });

    // Handle double-click selection
    gradeSelect.addEventListener('dblclick', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption && !selectedOption.disabled) {
            gradeInput.value = selectedOption.value;
            gradeModal.hide();
        }
    });

    // Handle enter key on select
    gradeSelect.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption && !selectedOption.disabled) {
                gradeInput.value = selectedOption.value;
                gradeModal.hide();
            }
        }
    });
}); 

function isValidDate(year, month, day) {
    // Convert 2-digit year to 4-digit year
    const currentYear = new Date().getFullYear();
    const century = parseInt(year) > (currentYear - 2000) ? '19' : '20';
    const fullYear = century + year;
    
    // Create date object and verify the date is valid
    const date = new Date(fullYear, month - 1, day);
    
    return date.getFullYear() === parseInt(fullYear) &&
           date.getMonth() === parseInt(month) - 1 &&
           date.getDate() === parseInt(day) &&
           parseInt(month) >= 1 && parseInt(month) <= 12 &&
           parseInt(day) >= 1 && parseInt(day) <= 31;
}

function formatIC(input) {
    // Remove all non-digits
    let value = input.value.replace(/\D/g, '');
    
    // Format with dashes
    if (value.length > 6) {
        value = value.substring(0, 6) + '-' + value.substring(6);
    }
    if (value.length > 9) {
        value = value.substring(0, 9) + '-' + value.substring(9);
    }
    
    input.value = value;
}

function validateIC(icNumber) {
    // Remove all non-digits
    const cleanIC = icNumber.replace(/\D/g, '');
    
    if (cleanIC.length !== 12) {
        return false;
    }
    
    // Extract year, month, and day
    const year = cleanIC.substring(0, 2);
    const month = cleanIC.substring(2, 4);
    const day = cleanIC.substring(4, 6);
    
    return isValidDate(year, month, day);
}

function calculateAgeAndBirthday(icNumber) {
    if (!validateIC(icNumber)) {
        alert('Nombor K/P tidak sah. Sila masukkan tarikh yang sah.');
        document.querySelector('input[name="ic_no"]').value = '';
        document.querySelector('input[name="ic_no"]').focus();
        return false;
    }
    
    // Extract date components
    const cleanIC = icNumber.replace(/\D/g, '');
    let year = cleanIC.substring(0, 2);
    let month = cleanIC.substring(2, 4);
    let day = cleanIC.substring(4, 6);
    
    // Determine century
    const currentYear = new Date().getFullYear();
    const fullYear = parseInt(year) > (currentYear - 2000) ? 1900 + parseInt(year) : 2000 + parseInt(year);
    
    // Create birth date
    const birthDate = new Date(fullYear, parseInt(month) - 1, parseInt(day));
    
    // Calculate age
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    
    // Adjust age if birthday hasn't occurred this year
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    // Format birthday for input field (YYYY-MM-DD)
    const formattedMonth = (birthDate.getMonth() + 1).toString().padStart(2, '0');
    const formattedDay = birthDate.getDate().toString().padStart(2, '0');
    const formattedBirthday = `${birthDate.getFullYear()}-${formattedMonth}-${formattedDay}`;
    
    // Update the form fields
    document.getElementById('birthday').value = formattedBirthday;
    document.getElementById('age').value = age;
}

// Add form submission validation
document.getElementById('membershipForm').addEventListener('submit', function(e) {
    const birthdayInput = document.getElementById('birthday');
    if (!birthdayInput.value) {
        e.preventDefault();
        alert('Sila masukkan No. K/P yang sah untuk mengisi tarikh lahir secara automatik.');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.querySelector('input[name="name"]');
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }
});

const statePostcodes = {
    'JOHOR': ['01', '02', '79', '80', '81', '82', '83', '84', '85', '86'],
    'KEDAH': ['05', '06', '07', '08', '09'],
    'KELANTAN': ['15', '16', '17', '18'],
    'MELAKA': ['75', '76', '77', '78'],
    'NEGERI SEMBILAN': ['70', '71', '72', '73', '74'],
    'PAHANG': ['25', '26', '27', '28', '39'],
    'PERAK': ['30', '31', '32', '33', '34', '35', '36'],
    'PERLIS': ['01', '02'],
    'PULAU PINANG': ['10', '11', '12', '13', '14'],
    'SABAH': ['88', '89', '90', '91'],
    'SARAWAK': ['93', '94', '95', '96', '97', '98'],
    'SELANGOR': ['40', '41', '42', '43', '44', '45', '46', '47', '48', '49'],
    'TERENGGANU': ['20', '21', '22', '23', '24'],
    'KUALA LUMPUR': ['50', '51', '52', '53', '54', '55', '56', '57', '58', '59', '60'],
    'LABUAN': ['87'],
    'PUTRAJAYA': ['62']
};

function detectPostcodeAndState(type, address) {
    // Clear existing values
    document.getElementById(`${type}_postcode`).value = '';
    document.getElementById(`${type}_state`).value = '';

    if (!address) return;

    // Convert to uppercase for consistency
    address = address.toUpperCase();

    // Try to find postcode (5 digits)
    const postcodeMatch = address.match(/\b\d{5}\b/);
    if (postcodeMatch) {
        const postcode = postcodeMatch[0];
        document.getElementById(`${type}_postcode`).value = postcode;

        // Find state based on postcode
        const prefix = postcode.substring(0, 2);
        for (const [state, prefixes] of Object.entries(statePostcodes)) {
            if (prefixes.includes(prefix)) {
                document.getElementById(`${type}_state`).value = state;
                break;
            }
        }
    }

    // If no postcode found, try to find state by name
    if (!document.getElementById(`${type}_state`).value) {
        for (const state of Object.keys(statePostcodes)) {
            if (address.includes(state)) {
                document.getElementById(`${type}_state`).value = state;
                break;
            }
        }
    }
}

// Add debounce function to prevent too many calls
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

const detectAddressDebounced = debounce((type, value) => detectPostcodeAndState(type, value), 500);

// Initialize event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Add manual postcode validation for both home and office
    ['home', 'office'].forEach(type => {
        const postcodeInput = document.getElementById(`${type}_postcode`);
        if (postcodeInput) {
            postcodeInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').substring(0, 5);
                
                if (this.value.length === 5) {
                    const prefix = this.value.substring(0, 2);
                    let stateFound = false;
                    
                    for (const [state, prefixes] of Object.entries(statePostcodes)) {
                        if (prefixes.includes(prefix)) {
                            document.getElementById(`${type}_state`).value = state;
                            stateFound = true;
                            break;
                        }
                    }
                    
                    if (!stateFound) {
                        this.setCustomValidity('Poskod tidak sah');
                    } else {
                        this.setCustomValidity('');
                    }
                }
            });
        }
    });

    const nameInput = document.querySelector('input[name="name"]');
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const backHomeBtn = document.querySelector('.back-home');
    const prevBtn = document.querySelector('.prev-step');
    
    // Function to update button visibility
    function updateButtonVisibility(currentStep) {
        if (currentStep === 1) {
            backHomeBtn.style.display = 'inline-block';
            prevBtn.style.display = 'none';
        } else {
            backHomeBtn.style.display = 'none';
            prevBtn.style.display = 'inline-block';
        }
    }
    
    // Initial state - show back home button on step 1
    updateButtonVisibility(1);
    
    // Create a MutationObserver to watch for changes in the active step
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.target.classList.contains('active')) {
                const currentStep = parseInt(mutation.target.getAttribute('data-step'));
                updateButtonVisibility(currentStep);
            }
        });
    });

    // Observe all step elements for class changes
    document.querySelectorAll('.step').forEach(function(step) {
        observer.observe(step, {
            attributes: true,
            attributeFilter: ['class']
        });
    });
});