<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\Director;

class DirectorController extends BaseController
{
    private $director;

    public function __construct()
    {
        $this->director = new Director();
    }

    public function dashboard()
    {
        try {
            if (!isset($_SESSION['director_id'])) {
                throw new \Exception('Sila log masuk untuk mengakses dashboard');
            }

            $metrics = $this->director->getMetrics();
            $recentActivities = $this->director->getRecentActivities();
            $membershipTrends = $this->director->getMembershipTrends();
            $financialMetrics = $this->director->getFinancialMetrics();
            $membershipStats = $this->director->getMembershipStats();
            $financialTrends = $this->director->getFinancialTrends();

            // Calculate monthly savings trend
            $currentMonth = date('m');
            $currentYear = date('Y');
            $lastMonth = $currentMonth - 1;
            $lastMonthYear = $currentYear;
            
            if ($lastMonth == 0) {
                $lastMonth = 12;
                $lastMonthYear = $currentYear - 1;
            }

            // Get savings data
            $metrics['total_savings'] = $this->director->getTotalSavings();
            $metrics['last_month_savings'] = $this->director->getTotalSavingsByMonth($lastMonth, $lastMonthYear);

            $metrics = array_merge($metrics, $financialMetrics);

            $this->view('director/dashboard', [
                'metrics' => $metrics,
                'recentActivities' => $recentActivities,
                'membershipTrends' => $membershipTrends,
                'membershipStats' => $membershipStats,
                'financialTrends' => $financialTrends
            ]);
        } catch (\Exception $e) {
            error_log('Error in director dashboard: ' . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            header('Location: /auth/login');
            exit;
        }
    }

    public function showAddDirector()
    {
        try {
            \App\Middleware\AuthMiddleware::validateAccess('admin');
            
            $this->view('director/add', [
                'departments' => [
                    'Pengurusan',
                    'Kewangan',
                    'Operasi',
                    'Pentadbiran',
                    'Teknologi Maklumat'
                ],
                'positions' => [
                    'Pengarah Eksekutif',
                    'Pengarah Kewangan',
                    'Pengarah Operasi',
                    'Pengarah Pentadbiran'
                ]
            ]);
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /admin');
            exit;
        }
    }

    public function store()
    {
        try {
            \App\Middleware\AuthMiddleware::validateAccess('admin');

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception('Invalid request method');
            }

            // Debug: Log POST data
            error_log('POST data: ' . print_r($_POST, true));

            // Validate required fields
            $requiredFields = ['username', 'name', 'email', 'password', 'position', 'department', 'phone_number'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new \Exception("Field $field is required");
                }
            }

            // Validate email format
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                throw new \Exception('Invalid email format');
            }

            // Validate password confirmation
            if ($_POST['password'] !== $_POST['confirm_password']) {
                throw new \Exception('Kata laluan tidak sepadan');
            }

            try {
                $directorId = $this->director->generateDirectorId();
                error_log('Generated Director ID: ' . $directorId);

                $data = [
                    'director_id' => $directorId,
                    'username' => $_POST['username'],
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    'position' => $_POST['position'],
                    'department' => $_POST['department'],
                    'phone_number' => $_POST['phone_number'],
                    'status' => 'active'
                ];

                // Debug: Log data being inserted
                error_log('Attempting to create director with data: ' . print_r($data, true));

                if ($this->director->create($data)) {
                    $_SESSION['success'] = 'Pengarah baru berjaya ditambah';
                    header('Location: /admin');
                    exit;
                }

                throw new \Exception('Failed to add new director');

            } catch (\PDOException $e) {
                error_log('PDO Error in store: ' . $e->getMessage());
                throw new \Exception('Database error: ' . $e->getMessage());
            }

        } catch (\Exception $e) {
            error_log('Error in store: ' . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            header('Location: /director/add');
            exit;
        }
    }

    public function updateLoanStatus()
    {
        try {
            $loanId = $_POST['loan_id'] ?? null;
            $status = strtolower($_POST['status'] ?? '');
            $remarks = $_POST['remarks'] ?? '';

            if (!$loanId || !$status) {
                throw new \Exception('ID dan status diperlukan');
            }

            $validStatuses = ['pending', 'approved', 'rejected'];
            if (!in_array($status, $validStatuses)) {
                throw new \Exception('Status tidak sah');
            }

            $updateData = [
                'id' => $loanId,
                'status' => $status,
                'remarks' => $remarks,
                'updated_by' => $_SESSION['director_id'],
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $result = $this->director->updateLoanStatus($updateData);
            
            if ($result) {
                $_SESSION['success'] = $status === 'approved' 
                    ? 'Permohonan pembiayaan telah diluluskan' 
                    : 'Permohonan pembiayaan telah ditolak';
            } else {
                throw new \Exception('Gagal mengemaskini status pembiayaan');
            }

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /director/loans');
        exit;
    }

    public function showLoans()
    {
        try {
            if (!isset($_SESSION['director_id'])) {
                throw new \Exception('Sila log masuk untuk mengakses');
            }

            $status = $_GET['status'] ?? 'pending';
            
            // Get loans based on status
            $loans = $this->director->getLoansByStatus($status);
            
            // Get metrics for the stats cards
            $metrics = $this->director->getMetrics();

            $this->view('director/loan-list', [
                'loans' => $loans,
                'metrics' => $metrics
            ]);

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /director');
            exit;
        }
    }

    public function getFinancialTrends()
    {
        try {
            if (!isset($_SESSION['director_id'])) {
                throw new \Exception('Unauthorized access');
            }

            $trends = $this->director->getFinancialTrends();

        } catch (\Exception $e) {
            error_log('Error in getFinancialTrends: ' . $e->getMessage());
            exit;
        }
    }
} 