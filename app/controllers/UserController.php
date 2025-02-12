<?php
namespace App\Controllers;

use App\Core\BaseController;
use App\Models\User;
use App\Models\Saving;
use App\Models\Loan;

class UserController extends BaseController
{
    private $user;
    private $saving;

    public function __construct()
    {
        $this->user = new User();
        $this->saving = new Saving();
    }

    // User Dashboard
    public function dashboard()
    {
        try {
            if (!isset($_SESSION['member_id'])) {
                header('Location: /auth/login');
                exit;
            }

            $memberId = $_SESSION['member_id'];
            $member = $this->user->getUserById($memberId);
            
            // Get savings account
            $savingsAccount = $this->saving->getSavingsAccount($memberId);
            $totalSavings = $savingsAccount ? $savingsAccount['current_amount'] : 0;
            
            // Get active loans and calculate total loan amount
            $loan = new Loan();
            $activeLoans = $loan->getActiveLoansByMemberId($memberId);
            $totalLoanAmount = 0;
            
            if (!empty($activeLoans)) {
                foreach ($activeLoans as $activeLoan) {
                    $totalLoanAmount += $activeLoan['amount'];
                }
            }

            $totalSavings = $this->saving->getTotalSavings($memberId);
            $recentActivities = $this->user->getRecentActivities($memberId);

            $this->view('users/dashboard', [
                'member' => $member,
                'savingsAccount' => $savingsAccount,
                'activeLoans' => $activeLoans,
                'totalSavings' => $totalSavings,
                'totalLoanAmount' => $totalLoanAmount,
                'recentActivities' => $recentActivities
            ]);

        } catch (\Exception $e) {
            error_log('Dashboard Error: ' . $e->getMessage());
            $_SESSION['error'] = $e->getMessage();
            header('Location: /auth/login');
            exit;
        }
    }

    public function profile()
    {
        try {
            if (!isset($_SESSION['member_id'])) {
                header('Location: /auth/login');
                exit();
            }

            $user = new User();
            $member = $user->getUserById($_SESSION['member_id']);

            if (!$member) {
                throw new \Exception('Member not found');
            }

            $this->view('users/profile', ['member' => $member]);
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /dashboard');
            exit();
        }
    }

    public function update()
    {
        try {
            if (!isset($_SESSION['member_id'])) {
                header('Location: /auth/login');
                exit();
            }

            $memberId = $_SESSION['member_id'];
            $data = [
                'email' => $_POST['email'],
                'home_phone' => $_POST['home_phone'],
                'office_phone' => $_POST['office_phone'],
                'marital_status' => $_POST['marital_status'],
                'home_address' => $_POST['home_address'],
                'home_postcode' => $_POST['home_postcode'],
                'home_state' => $_POST['home_state'],
                // Add other fields as needed
            ];

            $user = new User();
            if ($user->updateProfile($memberId, $data)) {
                $_SESSION['success'] = 'Profil berjaya dikemaskini';
            } else {
                throw new \Exception('Gagal mengemaskini profil');
            }

            header('Location: /users/profile');
            exit();
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /users/profile');
            exit();
        }
    }

    public function showResignForm()
    {
        try {
            if (!isset($_SESSION['member_id'])) {
                header('Location: /auth/login');
                exit();
            }

            $user = new User();
            $member = $user->getUserById($_SESSION['member_id']);

            if (!$member) {
                throw new \Exception('Member not found');
            }

            $this->view('users/resign', ['member' => $member]);
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /users/profile');
            exit();
        }
    }

    public function submitResignation()
    {
        try {
            if (!isset($_SESSION['member_id'])) {
                header('Location: /auth/login');
                exit();
            }

            if (!isset($_POST['reasons']) || empty($_POST['reasons'])) {
                throw new \Exception('Sila nyatakan sebab berhenti');
            }

            $reasons = array_filter($_POST['reasons']); // Remove empty values
            if (count($reasons) > 5) {
                throw new \Exception('Maksimum 5 sebab sahaja dibenarkan');
            }

            $user = new User();
            if ($user->submitResignation($_SESSION['member_id'], $reasons)) {
                $_SESSION['success'] = 'Permohonan berhenti telah berjaya dihantar';
                header('Location: /users/dashboard');
            } else {
                throw new \Exception('Gagal menghantar permohonan');
            }

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /users/resign');
        }
        exit();
    }
}