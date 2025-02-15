<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Koperasi KADA' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <img src="/img/logo-kada.png" alt="Koperasi KADA" height="45" class="me-2">
                <span class="brand-text d-none d-sm-inline">Koperasi KADA</span>
            </a>
            
            <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/about/history">
                            <i class="bi bi-book me-2"></i>Sejarah
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about/facts">
                            <i class="bi bi-bar-chart me-2"></i>Fakta & Angka
                        </a>
                    </li>
                </ul>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="d-flex align-items-center nav-right">
                        <div class="user-info me-3">
                            <span class="welcome-text">Selamat Datang,</span>
                            <span class="username"><?= htmlspecialchars($_SESSION['username'] ?? '') ?></span>
                        </div>
                        <a href="/auth/logout" class="btn btn-logout">
                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['member_id'])): ?>
                    <div class="d-flex align-items-center nav-right">
                        <div class="user-info me-3">
                            <span class="welcome-text">Selamat Datang,</span>
                            <span class="username"><?= htmlspecialchars($_SESSION['member_name'] ?? '') ?></span>
                        </div>
                        <a href="/auth/logout" class="btn btn-logout">
                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                        </a>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['director_id'])): ?>
                    <div class="d-flex align-items-center nav-right">
                        <div class="user-info me-3">
                            <span class="welcome-text">Selamat Datang,</span>
                            <span class="username"><?= htmlspecialchars($_SESSION['director_name'] ?? '') ?></span>
                        </div>
                        <a href="/auth/logout" class="btn btn-logout">
                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <style>
    .navbar {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.04);
        padding: 0.75rem 0;
    }

    .navbar-brand {
        transition: opacity 0.3s ease;
    }

    .navbar-brand:hover {
        opacity: 0.85;
    }

    .brand-text {
        font-weight: 600;
        color: #198754;
        margin-left: 0.5rem;
    }

    .navbar-nav .nav-link {
        color: #2d3436 !important;
        font-weight: 500;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
        color: #198754 !important;
        background: rgba(25, 135, 84, 0.08);
    }

    .custom-toggler {
        border: none;
        padding: 0.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .custom-toggler:hover {
        background: rgba(25, 135, 84, 0.08);
    }

    .custom-toggler i {
        color: #198754;
        font-size: 1.5rem;
    }

    .nav-right {
        background: rgba(25, 135, 84, 0.08);
        padding: 0.5rem 1rem;
        border-radius: 12px;
    }

    .user-info {
        display: flex;
        flex-direction: column;
    }

    .welcome-text {
        font-size: 0.75rem;
        color: #6c757d;
    }

    .username {
        font-weight: 600;
        color: #198754;
    }

    .btn-logout {
        background: #fff;
        color: #dc3545;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-logout:hover {
        background: #dc3545;
        color: #fff;
        transform: translateY(-1px);
    }

    @media (max-width: 991px) {
        .navbar-collapse {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-top: 1rem;
        }

        .nav-right {
            margin-top: 1rem;
            width: 100%;
            justify-content: space-between;
        }
    }
    </style>
</body>
</html>