<?php 
    $title = 'Bayaran Keahlian';
    require_once '../app/views/layouts/header.php';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Setup Progress -->
            <div class="setup-progress mb-4">
                <div class="steps d-flex justify-content-between">
                    <div class="step completed">
                        <div class="step-icon">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <span class="step-label">Daftar Akaun</span>
                    </div>
                    <div class="step completed">
                        <div class="step-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <span class="step-label">Tetapkan Kata Laluan</span>
                    </div>
                    <div class="step active">
                        <div class="step-icon">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <span class="step-label">Bayaran Keahlian</span>
                    </div>
                </div>
            </div>

            <!-- Fees Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0">
                    <h4 class="mb-0 fw-bold">Yuran Keahlian</h4>
                </div>
                <div class="card-body p-4">
                    <div class="alert custom-alert-info mb-4">
                        <div class="d-flex">
                            <div class="alert-icon">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <div class="alert-content">
                                <h6 class="alert-heading mb-1">Maklumat Pembayaran</h6>
                                <p class="mb-0">Sila sahkan pembayaran yuran berikut untuk mengaktifkan keahlian anda.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Fees Breakdown -->
                    <div class="fees-list">
                        <div class="fee-item">
                            <div class="fee-label">
                                <span class="fee-name">Fee Masuk</span>
                                <small class="text-muted d-block">Yuran kemasukan ahli baru</small>
                            </div>
                            <div class="fee-amount">
                                <div class="input-group">
                                    <span class="input-group-text border-0 bg-light">RM</span>
                                    <input type="number" 
                                           class="form-control form-control-lg border-0 text-end fee-input" 
                                           name="entrance_fee" 
                                           value="50.00" 
                                           step="0.01" 
                                           min="0" 
                                           readonly>
                                </div>
                            </div>
                        </div>

                        <div class="fee-item">
                            <div class="fee-label">
                                <span class="fee-name">Modal Saham</span>
                                <small class="text-muted d-block">Saham minimum keahlian</small>
                            </div>
                            <div class="fee-amount">
                                <div class="input-group">
                                    <span class="input-group-text border-0 bg-light">RM</span>
                                    <input type="number" 
                                           class="form-control form-control-lg border-0 text-end fee-input" 
                                           name="share_capital"
                                           value="25.00" 
                                           step="0.01" 
                                           min="0">
                                </div>
                            </div>
                        </div>

                        <div class="fee-item">
                            <div class="fee-label">
                                <span class="fee-name">Modal Yuran</span>
                                <small class="text-muted d-block">Yuran keahlian</small>
                            </div>
                            <div class="fee-amount">
                                <div class="input-group">
                                    <span class="input-group-text border-0 bg-light">RM</span>
                                    <input type="number" 
                                           class="form-control form-control-lg border-0 text-end fee-input" 
                                           name="fee_capital" 
                                           value="35.00" 
                                           step="0.01" 
                                           min="0">
                                </div>
                            </div>
                        </div>

                        <div class="fee-item">
                            <div class="fee-label">
                                <span class="fee-name">Simpanan Tetap</span>
                                <small class="text-muted d-block">Simpanan tetap</small>
                            </div>
                            <div class="fee-amount">
                                <div class="input-group">
                                    <span class="input-group-text border-0 bg-light">RM</span>
                                    <input type="number" 
                                           class="form-control form-control-lg border-0 text-end fee-input" 
                                           name="fixed_savings" 
                                           value="25.00" 
                                           step="0.01" 
                                           min="0">
                                </div>
                            </div>
                        </div>

                        <div class="fee-item">
                            <div class="fee-label">
                                <span class="fee-name">Modal Deposit</span>
                                <small class="text-muted d-block">Modal deposit</small>
                            </div>
                            <div class="fee-amount">
                                <div class="input-group">
                                    <span class="input-group-text border-0 bg-light">RM</span>
                                    <input type="number" 
                                           class="form-control form-control-lg border-0 text-end fee-input" 
                                           name="deposit_capital" 
                                           value="25.00" 
                                           step="0.01" 
                                           min="0">
                                </div>
                            </div>
                        </div>

                        <div class="fee-item">
                            <div class="fee-label">
                                <span class="fee-name">Tabung Kebajikan</span>
                                <small class="text-muted d-block">Tabung kebajikan</small>
                            </div>
                            <div class="fee-amount">
                                <div class="input-group">
                                    <span class="input-group-text border-0 bg-light">RM</span>
                                    <input type="number" 
                                           class="form-control form-control-lg border-0 text-end fee-input" 
                                           name="welfare_fund" 
                                           value="25.00" 
                                           step="0.01" 
                                           min="0">
                                </div>
                            </div>
                        </div>

                        <div class="fee-item total">
                            <div class="fee-label">
                                <span class="fee-name">Jumlah Keseluruhan</span>
                                <small class="text-muted d-block">Akan ditolak melalui gaji</small>
                            </div>
                            <div class="fee-amount">
                                <div class="input-group">
                                    <span class="input-group-text border-0 bg-primary text-white">RM</span>
                                    <input type="number" 
                                           class="form-control form-control-lg border-0 text-end fw-bold" 
                                           id="total_amount" 
                                           value="400.00" 
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirmation Form -->
                    <form action="/users" method="GET" class="mt-4">
                        <div class="alert custom-alert-warning mb-4">
                            <div class="d-flex">
                                <div class="alert-icon">
                                    <i class="bi bi-exclamation-triangle"></i>
                                </div>
                                <div class="alert-content">
                                    <h6 class="alert-heading mb-1">Nota Penting</h6>
                                    <p class="mb-0">Dengan menekan butang di bawah, anda mengesahkan bahawa pembayaran akan ditolak melalui tolak gaji.</p>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-check2-circle me-2"></i>
                            Sahkan Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Progress Steps */
.setup-progress {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
}

.steps {
    position: relative;
}

.steps::before {
    content: '';
    position: absolute;
    top: 24px;
    left: 50px;
    right: 50px;
    height: 2px;
    background: #e9ecef;
    z-index: 0;
}

.step {
    text-align: center;
    z-index: 1;
    position: relative;
    padding: 0 1rem;
}

.step-icon {
    width: 48px;
    height: 48px;
    background: white;
    border: 2px solid #dee2e6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-size: 1.25rem;
    color: #6c757d;
    transition: all 0.3s ease;
}

.step.completed .step-icon {
    background: #198754;
    border-color: #198754;
    color: white;
}

.step.active .step-icon {
    background: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.step-label {
    font-size: 0.875rem;
    color: #6c757d;
    font-weight: 500;
}

.step.completed .step-label {
    color: #198754;
}

.step.active .step-label {
    color: #0d6efd;
}

/* Custom Alerts */
.custom-alert-info, .custom-alert-warning {
    border: none;
    border-radius: 12px;
    padding: 1rem;
}

.custom-alert-info {
    background: rgba(13, 110, 253, 0.05);
}

.custom-alert-warning {
    background: rgba(255, 193, 7, 0.05);
}

.alert-icon {
    font-size: 1.5rem;
    margin-right: 1rem;
}

.custom-alert-info .alert-icon {
    color: #0d6efd;
}

.custom-alert-warning .alert-icon {
    color: #ffc107;
}

/* Fees List */
.fees-list {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    margin: 1.5rem 0;
}

.fee-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.fee-item:last-child {
    border-bottom: none;
}

.fee-item.total {
    font-weight: 600;
    font-size: 1.1rem;
    color: #0d6efd;
    border-top: 2px solid rgba(13, 110, 253, 0.1);
    margin-top: 0.5rem;
    padding-top: 1rem;
    border-bottom: none;
}

.amount {
    font-family: monospace;
    font-weight: 500;
}

/* Button Styles */
.btn-lg {
    padding: 1rem 1.5rem;
    font-weight: 500;
    border-radius: 8px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const feeInputs = document.querySelectorAll('.fee-input');
    const totalInput = document.getElementById('total_amount');

    function calculateTotal() {
        let total = 0;
        feeInputs.forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        totalInput.value = total.toFixed(2);
    }

    feeInputs.forEach(input => {
        input.addEventListener('input', calculateTotal);
    });
});
</script>

<?php require_once '../app/views/layouts/footer.php'; ?> 