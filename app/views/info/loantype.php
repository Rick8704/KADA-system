<?php 
    $title = 'Maklumat Pembiayaan';
    require_once '../app/views/layouts/header.php';
?>
<link rel="stylesheet" href="/css/landing.css">
<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-gradient">Jenis-jenis Pembiayaan</h1>
        <p class="lead">Pilihan pembiayaan yang sesuai dengan keperluan anda</p>
    </div>

    <!-- Perniagaan Section -->
    <h3 class="mb-4"><i class="bi bi-shop me-2"></i>Perniagaan</h3>
    <div class="row g-4 mb-5">
        <!-- Al-Baiubithaman Ajil -->
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <h3 class="card-title mb-0">Skim Pembiayaan Al-Baiubithaman Ajil</h3>
                    </div>
                    <p class="card-text">Pembiayaan perniagaan berdasarkan konsep jual beli yang mematuhi prinsip Syariah.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Had maksimum: RM15,000.00</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Kadar Faedah: 4.2% setahun</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bai Al-Inah -->
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-success bg-opacity-10 text-success me-3">
                            <i class="bi bi-currency-exchange"></i>
                        </div>
                        <h3 class="card-title mb-0">Skim Pembiayaan Bai Al-Inah</h3>
                    </div>
                    <p class="card-text">Pembiayaan peribadi yang fleksibel mengikut keperluan anda.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Had maksimum: RM10,000.00</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Kadar Faedah: 4.2% setahun</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Membaikpulih Kenderaan -->
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-warning bg-opacity-10 text-warning me-3">
                            <i class="bi bi-car-front"></i>
                        </div>
                        <h3 class="card-title mb-0">Skim Pembiayaan Membaikpulih Kenderaan</h3>
                    </div>
                    <p class="card-text">Pembiayaan untuk membaiki dan menyelenggara kenderaan anda.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Had maksimum: RM2,000.00</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Kadar Faedah: 4.2% setahun</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Cukai Jalan & Insuran -->
        <div class="col-lg-6">
            <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-info bg-opacity-10 text-info me-3">
                            <i class="bi bi-file-text"></i>
                        </div>
                        <h3 class="card-title mb-0">Skim Pembiayaan Cukai Jalan & Insuran</h3>
                    </div>
                    <p class="card-text">Pembiayaan untuk pembayaran cukai jalan dan insuran kenderaan.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Kadar Faedah: 4.2% setahun</li>
                        <li class="mb-2"><i class="bi bi-info-circle text-info me-2"></i>Jumlah pembiayaan berdasarkan kos sebenar</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Al-Qardhul Hasan Section -->
    <h3 class="mb-4"><i class="bi bi-heart me-2"></i>Al-Qardhul Hasan</h3>
    <div class="row g-4">
        <!-- Pinjaman Kecemasan -->
        <div class="col-lg-4">
            <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-danger bg-opacity-10 text-danger me-3">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>
                        <h3 class="card-title mb-0">Pinjaman Kecemasan</h3>
                    </div>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Had maksimum: RM500.00</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Pinjaman Berjamin -->
        <div class="col-lg-4">
            <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-primary bg-opacity-10 text-primary me-3">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="card-title mb-0">Pinjaman Berjamin</h3>
                    </div>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Had maksimum: RM1,000.00</li>
                        <li class="mb-2"><i class="bi bi-info-circle text-info me-2"></i>80% daripada saham/yuran atau dua penjamin</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Skim Khas -->
        <div class="col-lg-4">
            <div class="card h-100 shadow-sm hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-circle bg-success bg-opacity-10 text-success me-3">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                        <h3 class="card-title mb-0">Skim Khas</h3>
                    </div>
                    <p class="card-text">Pembiayaan pembelajaran anak.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check2-circle text-success me-2"></i>Had maksimum: RM2,000.00</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="/auth/login" class="btn btn-gradient btn-lg">
            <i class="bi bi-file-earmark-text me-2"></i>Mohon Sekarang
        </a>
    </div>

    <div class="text-center mt-4">
        <a href="/" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Halaman Utama
        </a>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>