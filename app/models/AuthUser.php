<?php
namespace App\Models;

use App\Core\BaseModel;
use PDO;

class AuthUser extends BaseModel
{
    public function createAdmin($data)
    {
        try {
            $stmt = $this->getConnection()->prepare(
                "INSERT INTO admins (username, email, password) 
                 VALUES (:username, :email, :password)"
            );
            return $stmt->execute([
                ':username' => $data['username'],
                ':email' => $data['email'],
                ':password' => $data['password']
            ]);
        } catch (\PDOException $e) {
            // Check for duplicate entry
            if ($e->getCode() == 23000) {
                return false;
            }
            throw $e;
        }
    }

    public function findAdminByUsername($username)
    {
        $stmt = $this->getConnection()->prepare(
            "SELECT * FROM admins WHERE username = :username"
        );
        $stmt->execute([':username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isAdmin($userId)
    {
        $stmt = $this->getConnection()->prepare(
            "SELECT is_admin FROM admins WHERE id = :id"
        );
        $stmt->execute([':id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['is_admin'];
    }

    public function findMemberByIC($ic)
    {
        try {
            // Remove any dashes or spaces from the input IC
            $cleanIC = str_replace(['-', ' '], '', $ic);
            
            // Query without status filter
            $sql = "SELECT * FROM members 
                    WHERE REPLACE(REPLACE(ic_no, '-', ''), ' ', '') = :ic_no";
            
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':ic_no' => $cleanIC]);
            $member = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $member;
        } catch (\PDOException $e) {
            error_log("Database error in findMemberByIC: " . $e->getMessage());
            return false;
        }
    }

    public function setMemberPassword($memberId, $password)
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            error_log("Setting password for member ID: " . $memberId);
            
            // First verify the member exists
            $checkSql = "SELECT id FROM members WHERE id = :id";
            $checkStmt = $this->getConnection()->prepare($checkSql);
            $checkStmt->execute([':id' => $memberId]);
            $member = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$member) {
                error_log("No member found with ID: " . $memberId);
                throw new \Exception('Member not found');
            }
            
            // Update the password
            $sql = "UPDATE members 
                    SET password = :password 
                    WHERE id = :id";
                    
            $stmt = $this->getConnection()->prepare($sql);
            $result = $stmt->execute([
                ':password' => $hashedPassword,
                ':id' => $memberId
            ]);

            if ($result) {
                // Verify the update
                $verifySql = "SELECT id, password FROM members WHERE id = :id";
                $verifyStmt = $this->getConnection()->prepare($verifySql);
                $verifyStmt->execute([':id' => $memberId]);
                $updated = $verifyStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($updated && $updated['password'] === $hashedPassword) {
                    error_log("Password successfully updated for member ID: " . $memberId);
                    return true;
                } else {
                    error_log("Password verification failed for member ID: " . $memberId);
                    return false;
                }
            }

            error_log("Failed to update password for member ID: " . $memberId);
            return false;

        } catch (\PDOException $e) {
            error_log('Database Error: ' . $e->getMessage());
            throw new \Exception('Failed to set password: ' . $e->getMessage());
        }
    }

    public function setupPassword($userId, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "UPDATE users SET password = ?, status = 'active' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute([$hashedPassword, $userId])) {
            // After successful password setup, get user details for session
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            return true;
        }
        return false;
    }
}
