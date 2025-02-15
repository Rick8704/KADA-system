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

                    <form action="/auth/setup-password" method="POST">
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
</script>

<?php require_once '../app/views/layouts/footer.php'; ?> 