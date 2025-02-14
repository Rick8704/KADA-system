<?php
namespace App\Models;
use App\Core\Database;
use App\Core\BaseModel;
use PDO;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class User extends BaseModel
{
    public function all() 
    {
        $stmt = $this->getConnection()->query("SELECT * FROM admins"); // Use query() for SELECT statements
        return $stmt->fetchAll(); // Use fetchAll() to get all records
    }

    /*public function getTransactionByReference($referenceNo)
    {
        $stmt = $this->getConnection()->prepare("SELECT * FROM transactions WHERE reference_no = :referenceNo");
        $stmt->bindParam(':referenceNo', $referenceNo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }*/

    public function create($data)
    {
        try {
            // Clean the IC number (remove hyphens) before hashing
            $cleanIC = str_replace('-', '', $data['ic_no']);
            
            // Hash the clean IC number to use as password
            $hashedPassword = password_hash($cleanIC, PASSWORD_DEFAULT);
            
            // Debug log
            error_log('Clean IC: ' . $cleanIC);
            error_log('Hashed Password: ' . $hashedPassword);

            $sql = "INSERT INTO pendingmember (
                name, ic_no, gender, religion, race, marital_status,
                position, grade, monthly_salary,
                home_address, home_postcode, home_state,
                office_address, office_postcode,
                office_phone, home_phone, fax,
                registration_fee, share_capital, fee_capital,
                deposit_funds, welfare_fund, fixed_deposit,
                other_contributions,
                family_relationship, family_name, family_ic,
                password,
                status
            ) VALUES (
                :name, :ic_no, :gender, :religion, :race, :marital_status,
                :position, :grade, :monthly_salary,
                :home_address, :home_postcode, :home_state,
                :office_address, :office_postcode,
                :office_phone, :home_phone, :fax,
                :registration_fee, :share_capital, :fee_capital,
                :deposit_funds, :welfare_fund, :fixed_deposit,
                :other_contributions,
                :family_relationship, :family_name, :family_ic,
                :password,
                'Pending'
            )";
            $stmt = $this->getConnection()->prepare($sql);
            
            $params = [
                ':name' => $data['name'],
                ':ic_no' => $data['ic_no'],
                ':gender' => $data['gender'],
                ':religion' => $data['religion'],
                ':race' => $data['race'],
                ':marital_status' => $data['marital_status'],
                ':position' => $data['position'],
                ':grade' => $data['grade'],
                ':monthly_salary' => $data['monthly_salary'],
                ':home_address' => $data['home_address'],
                ':home_postcode' => $data['home_postcode'],
                ':home_state' => $data['home_state'],
                ':office_address' => $data['office_address'],
                ':office_postcode' => $data['office_postcode'],
                ':office_phone' => $data['office_phone'],
                ':home_phone' => $data['home_phone'],
                ':fax' => $data['fax'] ?? null,
                ':registration_fee' => $data['registration_fee'] ?? 0,
                ':share_capital' => $data['share_capital'] ?? 0,
                ':fee_capital' => $data['fee_capital'] ?? 0,
                ':deposit_funds' => $data['deposit_funds'] ?? 0,
                ':welfare_fund' => $data['welfare_fund'] ?? 0,
                ':fixed_deposit' => $data['fixed_deposit'] ?? 0,
                ':other_contributions' => $data['other_contributions'] ?? null,
                ':family_relationship' => $data['family_relationship'][0] ?? null,
                ':family_name' => $data['family_name'][0] ?? null,
                ':family_ic' => $data['family_ic'][0] ?? null,
                ':password' => $hashedPassword
            ];

            $result = $stmt->execute($params);
            
            if (!$result) {
                error_log('PDO Error: ' . print_r($stmt->errorInfo(), true));
            }
            
            return $result;

        } catch (\PDOException $e) {
            error_log('Database Error: ' . $e->getMessage());
            throw new \Exception('Database error occurred: ' . $e->getMessage());
        }
    }

    public function find($id)
    {
        $stmt = $this->getConnection()->prepare("SELECT * FROM users WHERE id = :id"); // Use prepare() for SQL statements with variables
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT); // Use bindParam() to bind variables
        $stmt->execute(); // Use execute() to run the query
        return $stmt->fetch(); // Use fetch() to get a single record
    }

    public function update($id, $data)
    {
        $stmt = $this->getConnection()->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id"); // Use prepare() for SQL statements with variables
        $stmt->execute([ // Use execute() to run the query
            ':name' => $data['name'], // Use named placeholders to prevent SQL injection
            ':email' => $data['email'], // Use named placeholders to prevent SQL injection
            ':id' => $id, // Use named placeholders to prevent SQL injection
        ]);
        return $stmt; // Return the PDOStatement object
    }

    public function getUserById($id)
    {
        try {
            $sql = "SELECT m.*, sa.account_number 
                    FROM members m 
                    LEFT JOIN savings_accounts sa ON m.id = sa.member_id 
                    WHERE m.id = :id";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            return $result;
        } catch (\PDOException $e) {
            error_log('Database Error in getUserById: ' . $e->getMessage());
            throw new \Exception('Gagal mendapatkan maklumat pengguna');
        }
    }

    public function getRecentActivities($memberId, $limit = 5)
    {
        try {
            $sql = "(SELECT 
                    'savings' as type,
                    st.type as action,
                    st.amount,
                    st.created_at,
                    CASE 
                        WHEN st.type = 'deposit' THEN 'Deposit ke akaun simpanan'
                        WHEN st.type = 'withdrawal' THEN 'Pengeluaran dari akaun simpanan'
                        WHEN st.type = 'transfer_in' THEN 'Pemindahan masuk'
                        WHEN st.type = 'transfer_out' THEN 'Pemindahan keluar'
                        ELSE 'Transaksi simpanan'
                    END as description
                FROM savings_transactions st
                JOIN savings_accounts sa ON st.savings_account_id = sa.id
                WHERE sa.member_id = :member_id)
                
                UNION ALL
                
                (SELECT 
                    'loan' as type,
                    CASE 
                        WHEN pl.status = 'pending' THEN 'pending'
                        WHEN l.status = 'approved' THEN 'approved'
                        WHEN rl.status = 'rejected' THEN 'rejected'
                    END as action,
                    COALESCE(pl.amount, l.amount, rl.amount) as amount,
                    COALESCE(pl.date_received, l.approved_at, rl.rejected_at) as created_at,
                    CASE 
                        WHEN pl.status = 'pending' THEN 'Permohonan pembiayaan sedang diproses'
                        WHEN l.status = 'approved' THEN 'Permohonan pembiayaan telah diluluskan'
                        WHEN rl.status = 'rejected' THEN CASE WHEN rl.remarks IS NULL OR rl.remarks = '' THEN 'Permohonan pembiayaan ditolak' ELSE CONCAT('Permohonan pembiayaan ditolak: ', rl.remarks) END
                        ELSE 'Status pembiayaan tidak diketahui'
                    END as description
                FROM (
                    SELECT member_id, id, 'pending' as status FROM pendingloans WHERE member_id = :member_id
                    UNION ALL 
                    SELECT member_id, id, 'approved' as status FROM loans WHERE member_id = :member_id
                    UNION ALL
                    SELECT member_id, id, 'rejected' as status FROM rejectedloans WHERE member_id = :member_id
                ) all_loans
                LEFT JOIN pendingloans pl ON pl.id = all_loans.id AND all_loans.status = 'pending'
                LEFT JOIN loans l ON l.id = all_loans.id AND all_loans.status = 'approved'
                LEFT JOIN rejectedloans rl ON rl.id = all_loans.id AND all_loans.status = 'rejected')
                
                ORDER BY created_at DESC
                LIMIT :limit";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (\PDOException $e) {
            error_log('Database Error in getRecentActivities: ' . $e->getMessage());
            return [];
        }
    }

    public function getMemberById($id)
    {
        try {
            $sql = "SELECT * FROM members WHERE id = :id";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':id' => $id]);
            
            $member = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Debug log
            error_log('Fetched member data: ' . print_r($member, true));
            
            return $member;
        } catch (\PDOException $e) {
            error_log('Database Error in getMemberById: ' . $e->getMessage());
            throw new \Exception('Gagal mendapatkan maklumat ahli');
        } 
    }

    public function updateProfile($memberId, $data)
    {
        try {
            $allowedFields = [
                'email', 'home_phone', 'mobile_phone', 'office_phone', 
                'marital_status', 'home_address', 'home_postcode', 
                'home_state', 'office_address', 'office_postcode', 
                'office_state', 'position', 'grade', 'monthly_salary',
                'family_name', 'family_ic', 'family_relationship'
            ];

            $updateFields = array_intersect_key($data, array_flip($allowedFields));
            
            if (empty($updateFields)) {
                return false;
            }

            $sql = "UPDATE members SET ";
            $updates = [];
            $params = [];

            foreach ($updateFields as $field => $value) {
                $updates[] = "$field = :$field";
                $params[":$field"] = $value;
            }

            $sql .= implode(', ', $updates);
            $sql .= " WHERE id = :member_id";
            $params[':member_id'] = $memberId;

            $stmt = $this->getConnection()->prepare($sql);
            return $stmt->execute($params);

        } catch (\PDOException $e) {
            error_log('Database Error in updateProfile: ' . $e->getMessage());
            return false;
        }
    }

    public function submitResignation($memberId, $reasons)
    {
        try {
            $this->getConnection()->beginTransaction();

            // Update member status to inactive
            $updateSql = "UPDATE members SET 
                         status = 'Inactive',
                         resigned_at = NOW()
                         WHERE id = :member_id";
            
            $stmt = $this->getConnection()->prepare($updateSql);
            $stmt->execute([':member_id' => $memberId]);

            // Combine reasons into a numbered list
            $formattedReasons = array_filter($reasons, function($reason) {
                return !empty(trim($reason));
            });
            
            $numberedReasons = '';
            foreach ($formattedReasons as $index => $reason) {
                $numberedReasons .= ($index + 1) . ". " . trim($reason) . "\n";
            }

            // Store combined resignation reasons
            $reasonSql = "INSERT INTO resignation_reasons 
                         (member_id, reasons, created_at) 
                         VALUES (:member_id, :reasons, NOW())";
            
            $reasonStmt = $this->getConnection()->prepare($reasonSql);
            $reasonStmt->execute([
                ':member_id' => $memberId,
                ':reasons' => trim($numberedReasons)
            ]);

            // Send resignation confirmation email
            $member = $this->getMemberById($memberId);
            if ($member) {
                $this->sendResignationEmail($member['email'], $member['name']);
            }

            $this->getConnection()->commit();
            return true;

        } catch (\PDOException $e) {
            $this->getConnection()->rollBack();
            error_log('Error submitting resignation: ' . $e->getMessage());
            throw new \Exception('Gagal menghantar permohonan berhenti');
        }
    }

    private function sendResignationEmail($email, $name)
    {
        $mail = new PHPMailer(true);
        try {
            $config = require __DIR__ . '/../../config/mail.php';

            $mail->isSMTP();
            $mail->Host = $config['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['smtp_username'];
            $mail->Password = $config['smtp_password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $config['smtp_port'];
            $mail->CharSet = 'UTF-8';

            $mail->setFrom($config['from_address'], $config['from_name']);
            $mail->addAddress($email, $name);

            $mail->isHTML(true);
            $mail->Subject = 'Pengesahan Permohonan Berhenti';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; padding: 20px;'>
                    <h2>Pengesahan Permohonan Berhenti</h2>
                    <p>Salam sejahtera {$name},</p>
                    
                    <p>Permohonan berhenti anda telah diterima dan akan diproses dalam tempoh 14 hari bekerja.</p>
                    
                    <p>Anda akan menerima emel pengesahan setelah permohonan diluluskan.</p>
                    
                    <p>Sekian, terima kasih.</p>
                </div>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Error sending resignation email: ' . $e->getMessage());
            return false;
        }
    }

    public function hasResignationRecord($memberId)
    {
        try {
            $sql = "SELECT created_at FROM resignation_reasons WHERE member_id = :member_id";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':member_id' => $memberId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['created_at'] : false;
        } catch (\PDOException $e) {
            error_log('Error checking resignation record: ' . $e->getMessage());
            return false;
        }
    }

    public function getResignationInfo($memberId)
    {
        try {
            $sql = "SELECT r.approved_at, r.created_at 
                    FROM resignation_reasons r 
                    WHERE r.member_id = :member_id 
                    ORDER BY r.approved_at DESC 
                    LIMIT 1";
            
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':member_id' => $memberId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('Error getting resignation info: ' . $e->getMessage());
            return null;
        }
    }

    public function authenticate($username, $password)
    {
        try {
            // Debug log
            error_log("Authenticating user with username: " . $username);
            
            $sql = "SELECT * FROM members WHERE ic_no = :username";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Debug log
            error_log("Found user: " . ($user ? json_encode($user) : "No user found"));
            
            if ($user && password_verify($password, $user['password'])) {
                // Allow login regardless of status
                return $user;
            }
            
            error_log("Authentication failed - either user not found or password mismatch");
            return false;
        } catch (\PDOException $e) {
            error_log('Authentication Error: ' . $e->getMessage());
            return false;
        }
    }

    public function submitReactivation($memberId, $reasons)
    {
        try {
            $this->getConnection()->beginTransaction();

            // Combine reasons into a numbered list
            $formattedReasons = array_filter($reasons, function($reason) {
                return !empty(trim($reason));
            });
            
            $numberedReasons = '';
            foreach ($formattedReasons as $index => $reason) {
                $numberedReasons .= ($index + 1) . ". " . trim($reason) . "\n";
            }

            // Insert reactivation request
            $sql = "INSERT INTO reactivation_requests 
                    (member_id, reasons, created_at) 
                    VALUES (:member_id, :reasons, NOW())";
            
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([
                ':member_id' => $memberId,
                ':reasons' => trim($numberedReasons)
            ]);

            // Send confirmation email
            $member = $this->getMemberById($memberId);
            if ($member) {
                $this->sendReactivationEmail($member['email'], $member['name']);
            }

            $this->getConnection()->commit();
            return true;

        } catch (\PDOException $e) {
            $this->getConnection()->rollBack();
            error_log('Error submitting reactivation: ' . $e->getMessage());
            throw new \Exception('Gagal menghantar permohonan semula');
        }
    }

    private function sendReactivationEmail($email, $name)
    {
        $mail = new PHPMailer(true);
        try {
            $config = require __DIR__ . '/../../config/mail.php';

            $mail->isSMTP();
            $mail->Host = $config['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $config['smtp_username'];
            $mail->Password = $config['smtp_password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $config['smtp_port'];
            $mail->CharSet = 'UTF-8';

            $mail->setFrom($config['from_address'], $config['from_name']);
            $mail->addAddress($email, $name);

            $mail->isHTML(true);
            $mail->Subject = 'Pengesahan Permohonan Semula';
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; padding: 20px;'>
                    <h2>Pengesahan Permohonan Semula</h2>
                    <p>Salam sejahtera {$name},</p>
                    
                    <p>Permohonan semula anda telah diterima dan akan diproses dalam tempoh 14 hari bekerja.</p>
                    
                    <p>Anda akan menerima emel pengesahan setelah permohonan diluluskan.</p>
                    
                    <p>Sekian, terima kasih.</p>
                </div>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Error sending reactivation email: ' . $e->getMessage());
            return false;
        }
    }
}



