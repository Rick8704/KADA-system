<?php
namespace App\Controllers;
use App\Core\BaseController;
use App\Models\AuthUser;
use App\Models\User;
use App\Models\Director;
use PDO;

class AuthController extends BaseController
{
    private $authUser;
    private $director;
    private $user;

    public function __construct()
    {
        $this->authUser = new AuthUser();
        $this->director = new Director();
        $this->user = new User();
    }

    public function showLogin()
    {
        $this->view('auth/login', ['title' => 'Login - KADA System']);
    }

    public function login()
    {
        try {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            // Auto identify user type
            $userType = \App\Middleware\AuthMiddleware::identifyUserType($username);

            // Director login
            if ($userType === 'director') {
                $director = $this->director->findByUsername($username);
                
                if ($director && password_verify($password, $director['password'])) {
                    $_SESSION['director_id'] = $director['id'];
                    $_SESSION['director_name'] = $director['name'];
                    $_SESSION['user_type'] = 'director';
                    
                    // Update last login
                    $this->director->updateLastLogin($director['id']);
                    
                    header('Location: /director');
                    exit;
                }
                throw new \Exception('ID Pengarah atau kata laluan tidak sah');
            }

            // Member login
            if ($userType === 'member') {
                try {
                    $cleanIC = str_replace('-', '', $username);
                    error_log("Login attempt - IC: " . $cleanIC);
                    
                    if (empty($password)) {
                        throw new \Exception('Kata laluan diperlukan untuk log masuk');
                    }

                    $member = $this->authUser->findMemberByIC($cleanIC);
                    error_log("Found member from AuthUser: " . ($member ? json_encode($member) : "No member found"));

                    if (!$member) {
                        error_log("No member found with IC: " . $cleanIC);
                        throw new \Exception('No. K/P atau kata laluan tidak sah');
                    }

                    if (empty($member['password'])) {
                        error_log("Member has no password set");
                        throw new \Exception('Kata laluan belum ditetapkan. Sila semak emel anda untuk pautan penetapan kata laluan.');
                    }

                    if (password_verify($password, $member['password'])) {
                        error_log("Password verified successfully");
                        $_SESSION['member_id'] = $member['id'];
                        $_SESSION['member_name'] = $member['name'];
                        $_SESSION['user_type'] = 'member';
                        
                        if ($member['status'] === 'Resigned') {
                            error_log("Member is resigned, checking resignation info");
                            $resignationInfo = $this->user->getResignationInfo($member['id']);
                            error_log("Resignation info: " . json_encode($resignationInfo));
                            if ($resignationInfo) {
                                header('Location: /users/resigned?date=' . urlencode($resignationInfo['approved_at']));
                                exit();
                            }
                        }

                        header('Location: /users/dashboard');
                        exit;
                    } else {
                        error_log("Password verification failed");
                        throw new \Exception('No. K/P atau kata laluan tidak sah');
                    }
                } catch (\Exception $e) {
                    error_log("Login error: " . $e->getMessage());
                    $_SESSION['error'] = $e->getMessage();
                    header('Location: /auth/login');
                    exit;
                }
            }

            // Admin login
            if ($userType === 'admin') {
                if (empty($password)) {
                    throw new \Exception('Kata laluan diperlukan untuk log masuk admin');
                }

                $admin = $this->authUser->findAdminByUsername($username);
                if ($admin && password_verify($password, $admin['password'])) {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['user_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    $_SESSION['username'] = $admin['username'];
                    $_SESSION['user_type'] = 'admin';
                    
                    header('Location: /admin');
                    exit;
                }
                throw new \Exception('ID Admin atau kata laluan tidak sah');
            }

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /auth/login');
            exit();
        }
    }

    public function logout()
    {
        $_SESSION = array();
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
        
        session_destroy();
        header('Location: /');
        exit;
    }

    public function showSetupPassword()
    {
        $this->view('auth/setup-password', []);
    }

    public function setupPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ic = $_POST['ic'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validate inputs
            if (empty($ic) || empty($password) || empty($confirmPassword)) {
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['success' => false, 'message' => 'Sila isi semua maklumat yang diperlukan']);
                    return;
                }
                $_SESSION['error'] = 'Sila isi semua maklumat yang diperlukan';
                $this->redirect('/auth/setup-password');
                return;
            }
            
            if ($password !== $confirmPassword) {
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['success' => false, 'message' => 'Kata laluan tidak sepadan']);
                    return;
                }
                $_SESSION['error'] = 'Kata laluan tidak sepadan';
                $this->redirect('/auth/setup-password');
                return;
            }
            
            // Find member by IC
            $member = $this->authUser->findMemberByIC($ic);
            if (!$member) {
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['success' => false, 'message' => 'No. K/P tidak dijumpai']);
                    return;
                }
                $_SESSION['error'] = 'No. K/P tidak dijumpai';
                $this->redirect('/auth/setup-password');
                return;
            }
            
            // Set the password
            try {
                $this->authUser->setMemberPassword($member['id'], $password);
                
                // Set proper session variables for member login
                $_SESSION['member_id'] = $member['id'];
                $_SESSION['member_name'] = $member['name'];
                $_SESSION['user_type'] = 'member';
                
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['success' => true]);
                    return;
                }
                $this->redirect('/users/fees/initial');
                
            } catch (\Exception $e) {
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['success' => false, 'message' => 'Ralat menetapkan kata laluan']);
                    return;
                }
                $_SESSION['error'] = 'Ralat menetapkan kata laluan';
                $this->redirect('/auth/setup-password');
            }
        }
        
        // Show the setup password form
        $this->view('auth/setup-password');
    }

    protected function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    protected function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}