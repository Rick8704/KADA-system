<?php 
    $title = 'Senarai Permohonan Berhenti';
    require_once '../app/views/layouts/header.php';
?>

<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">
            <i class="bi bi-list-ul me-2"></i>
            Senarai Permohonan Berhenti
        </h5>
        <a href="/admin" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $_SESSION['error'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <?php if (empty($resignations)): ?>
                <div class="text-center py-4">
                    <p class="text-muted mb-0">Tiada permohonan berhenti</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID Ahli</th>
                                <th>Nama</th>
                                <th>Tarikh Mohon</th>
                                <th>Sebab-sebab</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resignations as $resignation): ?>
                                <tr>
                                    <td><?= htmlspecialchars($resignation['member_id']) ?></td>
                                    <td><?= htmlspecialchars($resignation['name']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($resignation['resignation_date'])) ?></td>
                                    <td><?= nl2br(htmlspecialchars($resignation['reasons'] ?? '-')) ?></td>
                                    <td>
                                        <form action="/admin/resignations/approve" method="POST" class="d-inline">
                                            <input type="hidden" name="member_id" value="<?= $resignation['id'] ?>">
                                            <button type="submit" class="btn btn-success btn-sm" 
                                                    onclick="return confirm('Adakah anda pasti untuk meluluskan permohonan ini?')">
                                                <i class="bi bi-check-circle me-2"></i>Lulus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?> 