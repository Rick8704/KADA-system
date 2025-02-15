<?php 
    $title = 'Tetapkan Kata Laluan';
    require_once '../app/views/layouts/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Tetapkan Kata Laluan</h2>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <form id="setupPasswordForm" action="/auth/setup-password" method="POST">
                        <input type="hidden" name="first_login" value="1">
                        <div class="mb-3">
                            <label class="form-label">No. Kad Pengenalan</label>
                            <input type="text" 
                                   name="ic" 
                                   class="form-control" 
                                   required 
                                   placeholder="000000-00-0000"
                                   maxlength="14"
                                   oninput="formatIC(this)">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Kata Laluan Baharu</label>
                            <input type="password" 
                                   name="password" 
                                   class="form-control" 
                                   required 
                                   minlength="8"
                                   autocomplete="new-password">
                            <div class="form-text">Minimum 8 aksara</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Sahkan Kata Laluan</label>
                            <input type="password" 
                                   name="confirm_password" 
                                   class="form-control" 
                                   required 
                                   minlength="8"
                                   autocomplete="new-password">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-shield-lock me-2"></i>
                                Tetapkan Kata Laluan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function formatIC(input) {
    // Remove any non-digit characters
    let value = input.value.replace(/\D/g, '');
    
    // Add dashes after positions 6 and 8
    if (value.length > 6) {
        value = value.slice(0, 6) + '-' + value.slice(6);
        if (value.length > 9) {
            value = value.slice(0, 9) + '-' + value.slice(9);
        }
    }
    
    // Update input value
    input.value = value;
}

document.getElementById('setupPasswordForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (this.checkValidity()) {
        const formData = new FormData(this);
        
        fetch('/auth/setup-password', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message briefly before redirecting
                const alert = document.createElement('div');
                alert.className = 'alert alert-success';
                alert.innerHTML = '<i class="bi bi-check-circle me-2"></i>Kata laluan berjaya ditetapkan. Mengarahkan semula...';
                this.insertAdjacentElement('beforebegin', alert);
                
                // Redirect after a short delay
                setTimeout(() => {
                    window.location.href = '/users/fees/initial';
                }, 1500);
            } else {
                // Show error message
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger';
                alert.innerHTML = `<i class="bi bi-exclamation-circle me-2"></i>${data.message || 'Ralat menetapkan kata laluan'}`;
                this.insertAdjacentElement('beforebegin', alert);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const alert = document.createElement('div');
            alert.className = 'alert alert-danger';
            alert.innerHTML = '<i class="bi bi-exclamation-circle me-2"></i>Ralat menetapkan kata laluan';
            this.insertAdjacentElement('beforebegin', alert);
        });
    } else {
        this.classList.add('was-validated');
    }
});
</script>

<?php require_once '../app/views/layouts/footer.php'; ?> 