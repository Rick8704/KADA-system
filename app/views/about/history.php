<?php 
    $title = 'Sejarah Koperasi';
    require_once '../app/views/layouts/header.php';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-body p-5">
                    <!-- Header with decorative line -->
                    <h2 class="card-title text-center mb-4 position-relative">
                        <i class="bi bi-clock-history me-2 text-primary"></i>
                        <span class="fw-bold">Sejarah Koperasi KADA</span>
                        <div class="position-relative mt-3">
                            <hr class="bg-primary" style="height: 2px; width: 50%; margin: 0 auto;">
                            <div class="position-absolute top-50 start-50 translate-middle bg-white px-3">
                                <i class="bi bi-star-fill text-primary"></i>
                            </div>
                        </div>
                    </h2>

                    <!-- Timeline Section -->
                    <div class="timeline">
                        <div class="timeline-item mb-4">
                            <h5 class="fw-bold text-primary">
                                <i class="bi bi-calendar-event me-2"></i>Penubuhan
                            </h5>
                            <p class="lead ms-4 mb-0" style="line-height: 1.6;">
                                Koperasi Kakitangan KADA Kelantan Berhad telah ditubuhkan di bawah Akta Koperasi 1993.
                            </p>
                        </div>

                        <div class="timeline-item mb-4">
                            <h5 class="fw-bold text-primary">
                                <i class="bi bi-bullseye me-2"></i>Objektif Penubuhan
                            </h5>
                            <ul class="list-group list-group-flush ms-4">
                                <li class="list-group-item bg-transparent d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-3"></i>
                                    Menggalakkan penjimatan di kalangan anggota
                                </li>
                                <li class="list-group-item bg-transparent d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-3"></i>
                                    Memberi kemudahan pembiayaan kepada anggota
                                </li>
                                <li class="list-group-item bg-transparent d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill text-success me-3"></i>
                                    Meningkatkan kesejahteraan sosio-ekonomi anggota
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Back button -->
                    <div class="text-center mt-4">
                        <a href="/" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali ke Halaman Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add custom styles -->
<style>
    .card {
        transition: transform 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }

    .timeline-item {
        background-color: #f8f9fa;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 1rem;
    }

    .list-group-item {
        border-left: none;
        border-right: none;
        border-radius: 0;
        padding: 1rem 0.5rem;
        transition: background-color 0.3s ease;
    }

    .list-group-item:hover {
        background-color: rgba(0,0,0,0.02);
    }

    .text-primary {
        color: #0d6efd !important;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>

<?php require_once '../app/views/layouts/footer.php'; ?>
