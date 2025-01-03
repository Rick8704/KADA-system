<?php 
    $title = 'Tetapan Bayaran Berulang';
    require_once '../app/views/layouts/header.php';
?>

<div class="container mt-4">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" id="successAlert">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body p-4">
                    <h4 class="card-title mb-4">
                        <i class="bi bi-arrow-repeat me-2"></i>Tetapan Bayaran Berulang
                    </h4>

                    <!-- Information Card -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading">
                            <i class="bi bi-info-circle me-2"></i>Mengenai Bayaran Berulang
                        </h6>
                        <ul class="mb-0">
                            <li>Potongan akan dibuat secara automatik setiap bulan</li>
                            <li>Minimum RM10 sebulan</li>
                            <li>Boleh dibatalkan pada bila-bila masa</li>
                            <li>Notifikasi akan dihantar sebelum potongan dibuat</li>
                        </ul>
                    </div>

                    <form action="/admin/savings/recurring/store" method="POST" class="needs-validation" novalidate>
                        <!-- Amount Setting -->
                        <div class="mb-4">
                            <label class="form-label">Jumlah Potongan Bulanan (RM)</label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type="number" name="amount" class="form-control form-control-lg" 
                                       min="10" max="1000" step="10" required
                                       value="<?= $recurringPayment['amount'] ?? '' ?>">
                            </div>
                            <div class="form-text">Minimum: RM10.00 sebulan</div>
                        </div>

                        <!-- Deduction Date -->
                        <div class="mb-4">
                            <label class="form-label">Tarikh Potongan</label>
                            <select name="deduction_day" class="form-select form-select-lg" required>
                                <?php for($i = 1; $i <= 28; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($recurringPayment['deduction_day'] ?? '') == $i ? 'selected' : '' ?>>
                                        <?= $i ?> hb setiap bulan
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <div class="form-text">Pilih tarikh untuk potongan bulanan</div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <label class="form-label">Kaedah Pembayaran</label>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input type="radio" class="btn-check" name="payment_method" 
                                           id="salary" value="salary" required>
                                    <label class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" 
                                           for="salary">
                                        <i class="bi bi-wallet2 fs-3 mb-2"></i>
                                        <span>Potongan Gaji</span>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" class="btn-check" name="payment_method" 
                                           id="fpx" value="fpx" required>
                                    <label class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" 
                                           for="fpx">
                                        <i class="bi bi-bank fs-3 mb-2"></i>
                                        <span>FPX</span>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="radio" class="btn-check" name="payment_method" 
                                           id="card" value="card" required>
                                    <label class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3" 
                                           for="card">
                                        <i class="bi bi-credit-card fs-3 mb-2"></i>
                                        <span>Kad Kredit/Debit</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Agreement -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="agreement" required>
                                <label class="form-check-label" for="agreement">
                                    Saya bersetuju untuk memberi kebenaran potongan bulanan mengikut tetapan di atas
                                </label>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="/admin/savings" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Simpan Tetapan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (isset($recurringPayment) && is_array($recurringPayment) && !empty($recurringPayment)): ?>
            <!-- Current Settings Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Tetapan Semasa</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <h6 class="text-muted">Jumlah Bulanan</h6>
                            <p class="fs-5 mb-0">RM <?= number_format($recurringPayment['amount'] ?? 0, 2) ?></p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h6 class="text-muted">Tarikh Potongan</h6>
                            <p class="fs-5 mb-0"><?= $recurringPayment['deduction_day'] ?? '-' ?> hb</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h6 class="text-muted">Kaedah Pembayaran</h6>
                            <p class="fs-5 mb-0">
                                <?php
                                $method = $recurringPayment['payment_method'] ?? '';
                                switch($method) {
                                    case 'salary':
                                        echo '<i class="bi bi-wallet2 me-2"></i>Potongan Gaji';
                                        break;
                                    case 'fpx':
                                        echo '<i class="bi bi-bank me-2"></i>FPX';
                                        break;
                                    case 'card':
                                        echo '<i class="bi bi-credit-card me-2"></i>Kad';
                                        break;
                                    default:
                                        echo '-';
                                }
                                ?>
                            </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <h6 class="text-muted">Status</h6>
                            <span class="badge bg-<?= ($recurringPayment['status'] ?? '') === 'active' ? 'success' : 'warning' ?>">
                                <?= ($recurringPayment['status'] ?? '') === 'active' ? 'Aktif' : 'Tidak Aktif' ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()

// Auto-dismiss success alert
document.addEventListener('DOMContentLoaded', function() {
    const successAlert = document.getElementById('successAlert');
    if (successAlert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(successAlert);
            bsAlert.close();
        }, 3000);
    }
});
</script>

<?php require_once '../app/views/layouts/footer.php'; ?> 