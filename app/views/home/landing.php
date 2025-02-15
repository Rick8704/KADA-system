<?php 
    $title = 'Welcome to KADA';
?>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert-backdrop"></div>
    <div class="alert alert-success alert-dismissible fade show alert-floating" role="alert">
        <?php 
        echo $_SESSION['success_message'];
        unset($_SESSION['success_message']); 
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<link rel="stylesheet" href="/css/landing.css">

<div class="animated-hero min-vh-100 d-flex align-items-center">
    <div class="hero-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
    </div>
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <span class="hero-badge">Koperasi Kakitangan KADA</span>
                <h1 class="display-4 fw-bold mb-4">
                    Platform Digital <span class="text-gradient">Koperasi Kakitangan</span> KADA Kelantan
                </h1>
                <p class="lead mb-5">Urus semua urusan koperasi dengan mudah dan selamat melalui platform digital kami.</p>
                <div class="d-flex gap-3">
                    <a href="/auth/login" class="btn btn-gradient btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Log Masuk
                    </a>
                    <a href="/guest/create" class="btn btn-outline-primary btn-lg">Daftar Sekarang</a>
                    <a href="/guest/check-status" class="btn btn-primary btn-lg px-4">
                    <i class="bi bi-search me-2"></i>Semak Status
                </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="annual-reports-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold">Laporan Tahunan</h2>
            <div class="header-line"></div>
        </div>

        <?php if (!empty($annualReports)): ?>
            <div class="row g-4 justify-content-center">
                <?php foreach ($annualReports as $report): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="annual-report-item">
                            <div class="report-content">
                                <div class="report-badge">
                                    <?= htmlspecialchars($report['year']) ?>
                                </div>
                                <h5 class="report-title"><?= htmlspecialchars($report['title']) ?></h5>
                                <div class="report-meta">
                                    <span class="report-date">
                                        <i class="bi bi-calendar-event"></i>
                                        <?= date('d/m/Y', strtotime($report['uploaded_at'])) ?>
                                    </span>
                                    <a href="<?= htmlspecialchars($report['file_path']) ?>" 
                                       class="download-link" 
                                       target="_blank"
                                       title="Muat Turun">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-folder2-open"></i>
                <p>Tiada laporan tahunan pada masa ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="services-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold">Perkhidmatan Kami</h2>
            <div class="header-line"></div>
        </div>
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="service-card-3d">
                    <div class="card-content">
                        <div class="service-icon gradient-1">
                            <i class="bi bi-person-plus-fill"></i>
                        </div>
                        <h3>Keahlian</h3>
                        <p>Daftar dan urus keahlian anda secara dalam talian dengan mudah dan pantas.</p>
                        <a href="/guest/create" class="service-link">
                            Ketahui Lebih Lanjut <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="service-card-3d">
                    <div class="card-content">
                        <div class="service-icon gradient-2">
                            <i class="bi bi-cash-coin"></i>
                        </div>
                        <h3>Simpanan</h3>
                        <p>Simpan wang anda dengan kadar faedah yang menarik dan akses simpanan bila-bila masa.</p>
                        <a href="/users" class="service-link">
                            Ketahui Lebih Lanjut <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="service-card-3d">
                    <div class="card-content">
                        <div class="service-icon gradient-1">
                            <i class="bi bi-currency-exchange"></i>
                        </div>
                        <h3>Pembiayaan</h3>
                        <p>Mohon pinjaman dengan kadar faedah yang kompetitif dan proses kelulusan yang cepat.</p>
                        <a href="/info/loantype" class="service-link">
                            Ketahui Lebih Lanjut <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="service-card-3d">
                    <div class="card-content">
                        <div class="service-icon gradient-3">
                            <i class="bi bi-calculator"></i>
                        </div>
                        <h3>Kalkulator</h3>
                        <p>Kira anggaran bayaran pinjaman anda dengan kalkulator pinjaman kami.</p>
                        <a href="#" class="service-link" data-bs-toggle="modal" data-bs-target="#calculatorModal">
                            Kira Sekarang <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<!-- <div class="features-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold mb-4">Kenapa Pilih Kami?</h2>
                <div class="feature-item">
                    <i class="bi bi-shield-check"></i>
                    <div>
                        <h4>Keselamatan Terjamin</h4>
                        <p>Sistem keselamatan yang diiktiraf dengan encryption data terkini.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <i class="bi bi-lightning-charge"></i>
                    <div>
                        <h4>Proses Pantas</h4>
                        <p>Proses permohonan dan kelulusan yang cepat dan efisien.</p>
                    </div>
                </div>
                <div class="feature-item">
                    <i class="bi bi-headset"></i>
                    <div>
                        <h4>Sokongan 24/7</h4>
                        <p>Khidmat sokongan pelanggan yang sedia membantu bila-bila masa.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="/img/features-illustration.svg" alt="Features" class="img-fluid">
            </div>
        </div>
    </div>
</div> -->

<!-- <div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-gradient-primary py-3">
                    <h5 class="card-title mb-0 text-center text-white">
                        <i class="bi bi-search me-2"></i>
                        Semakan Status Permohonan Menjadi Anggota
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <p class="text-muted">
                            Sila masukkan nama penuh anda untuk menyemak status permohonan keahlian koperasi
                        </p>
                    </div>
                    <form id="enquiryForm" onsubmit="checkStatus(event)">
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold">
                                <i class="bi bi-person-badge me-2"></i>
                                Nama Penuh (seperti dalam Kad Pengenalan)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" 
                                       class="form-control form-control-lg text-uppercase" 
                                       id="name" 
                                       name="name" 
                                       style="text-transform: uppercase;" 
                                       oninput="this.value = this.value.toUpperCase()" 
                                       placeholder="Contoh: AHMAD BIN ABDULLAH"
                                       required>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-search me-2"></i>
                                Semak Status
                            </button>
                        </div>
                    </form>
                    <div id="statusResult" class="mt-4" style="display: none;">
                        <div class="alert rounded-4 shadow-sm border-0" role="alert"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<!-- Calculator Modal -->
<div class="modal fade" id="calculatorModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header border-0 bg-gradient-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-calculator-fill me-2"></i>
                    Kalkulator Pinjaman
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="loanCalculatorForm">
                    <div class="mb-4">
                        <label class="form-label text-muted fw-medium">Amaun Dipohon</label>
                        <div class="input-group">
                            <span class="input-group-text border-0 bg-light">RM</span>
                            <input type="number" id="loanAmount" class="form-control bg-light border-0" required step="100" min="1000">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted fw-medium">Tempoh Pembiayaan</label>
                        <div class="input-group">
                            <input type="number" id="loanDuration" class="form-control bg-light border-0" required step="1" min="6" max="120">
                            <span class="input-group-text border-0 bg-light">Bulan</span>
                        </div>
                    </div>
                    <div class="interest-rate-box mb-4 p-3 rounded-3 bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted fw-medium">Kadar Faedah</span>
                            <span class="badge bg-success">4.2% Setahun</span>
                        </div>
                    </div>
                    <div class="calculated-result mb-4" style="display: none;">
                        <div class="result-box p-4 rounded-3 bg-gradient-success text-white text-center">
                            <h6 class="mb-2 opacity-75">Ansuran Bulanan</h6>
                            <h2 class="mb-0">RM <span id="monthlyPayment">0.00</span></h2>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100 py-3 fw-medium">
                        <i class="bi bi-calculator me-2"></i>Kira Ansuran
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* Annual Reports Section */
.annual-reports-section {
    background-color: #f8f9fa;
    position: relative;
}

.annual-report-item {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.annual-report-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
}

.annual-report-item:hover .report-icon {
    transform: scale(1.05);
}

.annual-report-item:hover .download-link {
    opacity: 1;
    transform: translateY(0);
}

.report-content {
    position: relative;
}

.report-badge {
    display: inline-block;
    padding: 0.35rem 0.75rem;
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.75rem;
}

.report-title {
    color: #2d3436;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 1rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 2.8em;
}

.report-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.report-date {
    color: #6c757d;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.report-date i {
    font-size: 0.875rem;
    opacity: 0.7;
}

.download-link {
    width: 32px;
    height: 32px;
    background: #198754;
    color: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    opacity: 0.9;
    transform: translateY(5px);
}

.download-link:hover {
    background: #146c43;
    color: white;
    opacity: 1;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    background: white;
    border-radius: 16px;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.empty-state i {
    font-size: 2.5rem;
    color: #6c757d;
    opacity: 0.5;
    margin-bottom: 1rem;
    display: block;
}

.empty-state p {
    color: #6c757d;
    margin: 0;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .annual-report-item {
        padding: 1.25rem;
    }
    
    .report-icon {
        width: 40px;
        height: 40px;
    }
    
    .report-title {
        font-size: 0.9rem;
    }
}

/* Calculator Modal Styles */
.modal-content {
    border-radius: 16px;
    overflow: hidden;
}

.bg-gradient-success {
    background: linear-gradient(45deg, #198754, #20c997);
}

.form-control:focus, .input-group-text {
    box-shadow: none;
}

.form-control::placeholder {
    color: #adb5bd;
}

.interest-rate-box {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.result-box {
    animation: fadeInUp 0.4s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.btn-success {
    background: linear-gradient(45deg, #198754, #20c997);
    border: none;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(25, 135, 84, 0.3);
}

.badge {
    padding: 0.5rem 1rem;
    font-weight: 500;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.querySelector('.alert-floating');
        const backdrop = document.querySelector('.alert-backdrop');
        
        if (alert && backdrop) {
            // Handle manual dismissal
            alert.querySelector('.btn-close').addEventListener('click', () => {
                alert.remove();
                backdrop.remove();
            });
            
            // Click on backdrop to dismiss
            backdrop.addEventListener('click', () => {
                alert.remove();
                backdrop.remove();
            });
        }
    });

    // function checkStatus(event) {
    //     event.preventDefault();
        
    //     const name = document.getElementById('name').value.toUpperCase();
    //     const statusResult = document.getElementById('statusResult');
    //     const alertDiv = statusResult.querySelector('.alert');
        
    //     console.log('Sending request for name:', name); // Debug log
        
    //     fetch('/guest/checkStatus', {
    //         method: 'POST',
    //         headers: {
    //             'Content-Type': 'application/json',
    //         },
    //         body: JSON.stringify({ name: name })
    //     })
    //     .then(response => {
    //         console.log('Response status:', response.status); // Debug log
    //         return response.json();
    //     })
    //     .then(data => {
    //         console.log('Response data:', data); // Debug log
    //         statusResult.style.display = 'block';
            
    //         if (data.success) {
    //             alertDiv.className = `alert ${getAlertClass(data.status)}`;
    //             alertDiv.textContent = data.message;
    //         } else {
    //             alertDiv.className = 'alert alert-danger';
    //             alertDiv.textContent = data.error || 'An error occurred while checking the status.';
    //         }
    //     })
    //     .catch(error => {
    //         console.error('Error:', error); // Debug log
    //         statusResult.style.display = 'block';
    //         alertDiv.className = 'alert alert-danger';
    //         alertDiv.textContent = 'An error occurred while checking the status.';
    //     });
    // }

    // function getAlertClass(status) {
    //     switch(status) {
    //         case 'Pending':
    //             return 'alert-warning bg-warning-subtle border-0';
    //         case 'Lulus':
    //         case 'Active':
    //             return 'alert-success bg-success-subtle border-0';
    //         case 'Tolak':
    //         case 'Inactive':
    //             return 'alert-danger bg-danger-subtle border-0';
    //         case 'not_found':
    //             return 'alert-info bg-info-subtle border-0';
    //         default:
    //             return 'alert-secondary bg-secondary-subtle border-0';
    //     }
    // }

    document.getElementById('loanCalculatorForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const amount = parseFloat(document.getElementById('loanAmount').value);
        const duration = parseInt(document.getElementById('loanDuration').value);
        const annualRate = 4.2 / 100;
        const monthlyRate = annualRate / 12;
        
        const monthlyPayment = amount * monthlyRate * Math.pow(1 + monthlyRate, duration) 
                              / (Math.pow(1 + monthlyRate, duration) - 1);
        
        const resultElement = document.querySelector('.calculated-result');
        resultElement.style.display = 'block';
        
        // Animate the number
        const paymentElement = document.getElementById('monthlyPayment');
        const finalAmount = monthlyPayment.toFixed(2);
        
        // Format with commas
        paymentElement.textContent = parseFloat(finalAmount).toLocaleString('en-MY', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    });
</script>

<?php require_once '../app/views/layouts/footer.php'; ?> 