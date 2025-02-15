<?php 
    $title = 'Dashboard Ahli';
    
    // Check if user has completed initial setup
    if (isset($_SESSION['first_login']) && !isset($_SESSION['fees_paid'])) {
        header('Location: /users/fees/initial');
        exit;
    }
    
    require_once '../app/views/layouts/header.php';
?>

<div class="container py-4">
    <?php if (isset($_SESSION['error']) || isset($_SESSION['success'])): ?>
        <div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
        <?php if (isset($_SESSION['error'])): ?>
                        <div class="modal-header border-0 bg-danger bg-opacity-10">
                            <h5 class="modal-title text-danger">Error</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
                        <div class="modal-header border-0 bg-success bg-opacity-10">
                            <h5 class="modal-title text-success">Berjaya!</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
            </div>
        <?php endif; ?>
        
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-white shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">Selamat Datang, <?= htmlspecialchars($member->name) ?></h4>
                            <p class="text-muted mb-0">No. Ahli: <?= htmlspecialchars($member->member_id) ?></p>
                        </div>
                        <a href="/users/profile" class="btn btn-outline-primary">
                            <i class="bi bi-person-circle me-2"></i>Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <!-- Total Savings -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="bi bi-wallet2 text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Jumlah Simpanan</h6>
                            <h4 class="mb-0">RM <?= number_format($totalSavings, 2) ?></h4>
                        </div>
                    </div>
                    <a href="/users/savings" class="btn btn-primary w-100">Urus Simpanan</a>
                </div>
            </div>
        </div>

        <!-- Savings Transfer -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-success bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="bi bi-arrow-left-right text-success fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Pindahan Simpanan</h5>
                            <p class="text-muted small mb-0">Pindah wang antara akaun</p>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="/users/savings/transfer" class="btn btn-success">
                            <i class="bi bi-arrow-left-right me-2"></i>
                            Pindah Antara Akaun
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loan Status -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="bi bi-cash-stack text-warning fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Status Pembiayaan</h6>
                            <h4 class="mb-0">
                                <?php if (!empty($activeLoans)): ?>
                                    RM <?= number_format($totalLoanAmount, 2) ?>
                                <?php else: ?>
                                    Tiada Pembiayaan Aktif
                                <?php endif; ?>
                            </h4>
                        </div>
                    </div>
                    <a href="/users/loans/status" class="btn btn-warning w-100">Lihat Pembiayaan</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Aktiviti Terkini</h5>
                        <a href="/users/statements" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-file-text me-2"></i>Lihat Penyata
                        </a>
                    </div>

                    <?php if (!empty($recentActivities)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <tbody>
                                    <?php foreach ($recentActivities as $activity): ?>
                                        <tr>
                                            <td style="width: 40px;">
                                                <div class="activity-icon 
                                                    <?= $activity->type === 'savings' ? 'bg-success' : 'bg-primary' ?> 
                                                    bg-opacity-10 rounded-circle p-2">
                                                    <i class="bi 
                                                        <?= $activity->type === 'savings' ? 'bi-wallet2' : 'bi-cash-stack' ?> 
                                                        <?= $activity->type === 'savings' ? 'text-success' : 'text-primary' ?> 
                                                        small"></i>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="d-block"><?= htmlspecialchars($activity->description) ?></span>
                                                <small class="text-muted">
                                                    <?= date('d/m/Y H:i', strtotime($activity->created_at)) ?>
                                                </small>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge 
                                                    <?= $activity->type === 'savings' ? 
                                                        ($activity->action === 'withdrawal' ? 'bg-danger' : 'bg-success') : 
                                                        'bg-primary' ?>">
                                                    RM <?= number_format($activity->amount, 2) ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4 mb-0">Tiada aktiviti terkini</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-2px);
}
.icon-box {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.activity-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.table td {
    padding: 1rem;
    vertical-align: middle;
}
.badge {
    padding: 0.5rem 0.75rem;
    font-weight: 500;
}
</style>

<?php require_once '../app/views/layouts/footer.php'; ?> 