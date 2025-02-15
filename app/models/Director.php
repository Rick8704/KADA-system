<?php
namespace App\Models;

use App\Core\BaseModel;
use PDO;
use DateTime;
use DateInterval;
use DatePeriod;

class Director extends BaseModel
{
    public function getMetrics()
    {
        try {
            // Total Members
            $sql = "SELECT COUNT(*) as total FROM members WHERE status = 'Active'";
            $stmt = $this->getConnection()->query($sql);
            $totalMembers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Total Savings
            $sql = "SELECT SUM(current_amount) as total FROM savings_accounts WHERE status = 'active'";
            $stmt = $this->getConnection()->query($sql);
            $totalSavings = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            // Get loan statistics
            $sql = "SELECT 
                    (SELECT COUNT(*) FROM loans WHERE status = 'approved') as approved_loans,
                    (SELECT COUNT(*) FROM rejectedloans) as rejected_count,
                    (SELECT COUNT(*) FROM pendingloans WHERE status = 'pending') as pending_count,
                    (SELECT SUM(amount) FROM loans WHERE status = 'approved') as total_amount";
            
            $stmt = $this->getConnection()->query($sql);
            $loanStats = $stmt->fetch(PDO::FETCH_ASSOC);

            // Calculate total loans (including all statuses)
            $loanStats['total_loans'] = $loanStats['approved_loans'];
            
            // Ensure we have numeric values
            $loanStats['approved_loans'] = (int)($loanStats['approved_loans'] ?? 0);
            $loanStats['rejected_count'] = (int)($loanStats['rejected_count'] ?? 0);
            $loanStats['pending_count'] = (int)($loanStats['pending_count'] ?? 0);
            $loanStats['total_amount'] = (float)($loanStats['total_amount'] ?? 0);

            // New Members This Month
            $sql = "SELECT COUNT(*) as total FROM members 
                    WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) 
                    AND YEAR(created_at) = YEAR(CURRENT_DATE())";
            $stmt = $this->getConnection()->query($sql);
            $newMembers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            return [
                'total_members' => $totalMembers,
                'total_savings' => $totalSavings,
                'loan_stats' => $loanStats,
                'new_members' => $newMembers
            ];

        } catch (\PDOException $e) {
            error_log('Database Error in getMetrics: ' . $e->getMessage());
            throw new \Exception('Error retrieving metrics');
        }
    }

    public function getRecentActivities($limit = 10)
    {
        try {
            $sql = "SELECT 
                    'savings' as type,
                    st.transaction_type,
                    st.amount,
                    st.created_at,
                    m.name as member_name
                FROM savings_transactions st
                JOIN savings_accounts sa ON st.savings_account_id = sa.id
                JOIN members m ON sa.member_id = m.id
                
                UNION ALL
                
                SELECT 
                    'loan' as type,
                    status as transaction_type,
                    amount,
                    created_at,
                    m.name as member_name
                FROM loans l
                JOIN members m ON l.member_id = m.id
                
                ORDER BY created_at DESC
                LIMIT :limit";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('Database Error in getRecentActivities: ' . $e->getMessage());
            return [];
        }
    }

    public function getMembershipTrends()
    {
        try {
            $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as new_members
                FROM members
                WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month ASC";
            
            $stmt = $this->getConnection()->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('Database Error in getMembershipTrends: ' . $e->getMessage());
            return [];
        }
    }

    public function getFinancialMetrics()
    {
        try {
            // Add this temporary debug code at the start of getFinancialMetrics()
            try {
                $debugSql = "SELECT COUNT(*) as count, status FROM rejectedloans GROUP BY status";
                $debugStmt = $this->getConnection()->query($debugSql);
                $debugResults = $debugStmt->fetchAll(PDO::FETCH_ASSOC);
                error_log('Debug - Rejected loans by status:');
                error_log(print_r($debugResults, true));
            } catch (\PDOException $e) {
                error_log('Debug query error: ' . $e->getMessage());
            }

            // Get total savings with error handling
            try {
                $sql = "SELECT COALESCE(SUM(current_amount), 0) as total FROM savings_accounts WHERE status = 'active'";
                $stmt = $this->getConnection()->query($sql);
                $totalSavings = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            } catch (\PDOException $e) {
                error_log('Error getting savings: ' . $e->getMessage());
                $totalSavings = 0;
            }
            
            try {
                $sql = "SELECT 
                    (SELECT COUNT(*) FROM loans) as approved_count,
                    (SELECT COUNT(*) FROM rejectedloans) as rejected_count,
                    (SELECT COUNT(*) FROM pendingloans) as pending_count,
                    (
                        SELECT COUNT(*) 
                        FROM (
                            SELECT id FROM loans
                            UNION ALL
                            SELECT id FROM rejectedloans
                            UNION ALL
                            SELECT id FROM pendingloans
                        ) as all_loans
                    ) as total_count";
                
                $stmt = $this->getConnection()->query($sql);
                $loanStats = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Detailed debug logging
                error_log('Detailed Loan Statistics:');
                error_log('- Approved loans: ' . $loanStats['approved_count']);
                error_log('- Rejected loans: ' . $loanStats['rejected_count']);
                error_log('- Pending loans: ' . $loanStats['pending_count']);
                error_log('- Total count: ' . $loanStats['total_count']);
                
                // Calculate total loans (all loans from all tables)
                $totalLoans = $loanStats['total_count'];
                
                // Calculate approval rate
                $approvalRate = $totalLoans > 0 
                    ? ($loanStats['approved_count'] / $totalLoans) * 100 
                    : 0;
                
                // Debug: Log the final calculations
                error_log('Final calculations:');
                error_log('Total Loans: ' . $totalLoans);
                error_log('Approval Rate: ' . $approvalRate . '%');
                
            } catch (\PDOException $e) {
                error_log('Error getting loans: ' . $e->getMessage());
                $totalLoans = 0;
                $approvalRate = 0;
            }

            // Get total fees with error handling
            try {
                $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM fees WHERE status = 'paid'";
                $stmt = $this->getConnection()->query($sql);
                $totalFees = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            } catch (\PDOException $e) {
                error_log('Error getting fees: ' . $e->getMessage());
                $totalFees = 0;
            }

            // Get other amounts with error handling
            try {
                $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM other_transactions WHERE status = 'completed'";
                $stmt = $this->getConnection()->query($sql);
                $totalOther = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
            } catch (\PDOException $e) {
                error_log('Error getting other amounts: ' . $e->getMessage());
                $totalOther = 0;
            }

            return [
                'total_savings' => $totalSavings,
                'total_loans' => $totalLoans,
                'total_fees' => $totalFees,
                'total_other' => $totalOther,
                'loan_approval_rate' => round($approvalRate, 1)
            ];

        } catch (\PDOException $e) {
            error_log('Database Error in getFinancialMetrics: ' . $e->getMessage());
            return [
                'total_savings' => 0,
                'total_loans' => 0,
                'total_fees' => 0,
                'total_other' => 0,
                'loan_approval_rate' => 0
            ];
        }
    }

    public function findByUsername($username)
    {
        try {
            $sql = "SELECT * FROM directors WHERE username = :username AND status = 'active'";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':username' => $username]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log('Database Error in findByUsername: ' . $e->getMessage());
            return null;
        }
    }

    public function updateLastLogin($id)
    {
        try {
            $sql = "UPDATE directors SET last_login = NOW() WHERE id = :id";
            $stmt = $this->getConnection()->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (\PDOException $e) {
            error_log('Database Error in updateLastLogin: ' . $e->getMessage());
            return false;
        }
    }

    public function create($data)
    {
        try {
            $this->getConnection()->beginTransaction();

            $sql = "INSERT INTO directors (
                director_id,
                username,
                name,
                email,
                password,
                position,
                department,
                phone_number,
                status,
                created_at
            ) VALUES (
                :director_id,
                :username,
                :name,
                :email,
                :password,
                :position,
                :department,
                :phone_number,
                :status,
                NOW()
            )";

            $stmt = $this->getConnection()->prepare($sql);
            
            // Debug: Log prepared statement
            error_log('Prepared SQL: ' . $sql);

            $result = $stmt->execute([
                ':director_id' => $data['director_id'],
                ':username' => $data['username'],
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':password' => $data['password'],
                ':position' => $data['position'],
                ':department' => $data['department'],
                ':phone_number' => $data['phone_number'],
                ':status' => $data['status']
            ]);

            if (!$result) {
                // Debug: Log any SQL errors
                error_log('SQL Error: ' . print_r($stmt->errorInfo(), true));
                throw new \PDOException('Failed to execute SQL');
            }

            $this->getConnection()->commit();
            return true;

        } catch (\PDOException $e) {
            if ($this->getConnection()->inTransaction()) {
                $this->getConnection()->rollBack();
            }
            error_log('Database Error in create: ' . $e->getMessage());
            throw new \Exception('Failed to create director account: ' . $e->getMessage());
        }
    }

    public function generateDirectorId()
    {
        try {
            $year = date('Y');
            
            // Debug: Log the year
            error_log('Generating director ID for year: ' . $year);
            
            // Get the latest director number for the current year
            $sql = "SELECT director_id FROM directors 
                    WHERE director_id LIKE :year 
                    ORDER BY director_id DESC LIMIT 1";
            
            $stmt = $this->getConnection()->prepare($sql);
            $searchPattern = 'DIR' . $year . '%';
            $stmt->execute([':year' => $searchPattern]);
            
            // Debug: Log the search pattern
            error_log('Searching with pattern: ' . $searchPattern);
            
            $lastId = $stmt->fetchColumn();
            
            // Debug: Log the last ID found
            error_log('Last director ID found: ' . ($lastId ?: 'none'));
            
            if ($lastId) {
                // Extract the sequence number and increment
                $sequence = intval(substr($lastId, -4)) + 1;
            } else {
                // Start with 0001 if no existing directors for this year
                $sequence = 1;
            }
            
            // Format: DIRYYYY0001
            $newId = 'DIR' . $year . str_pad($sequence, 4, '0', STR_PAD_LEFT);
            
            // Debug: Log the generated ID
            error_log('Generated new director ID: ' . $newId);
            
            return $newId;
            
        } catch (\Exception $e) {
            error_log('Error generating director ID: ' . $e->getMessage());
            throw new \Exception('Failed to generate director ID: ' . $e->getMessage());
        }
    }

    public function getMembershipStats()
    {
        try {
            $sql = "SELECT 
                    SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) as active_count,
                    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) as rejected_count
                    FROM members";
            
            $stmt = $this->getConnection()->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'memberCount' => (int)($result['active_count'] ?? 0),
                'pendingCount' => (int)($result['pending_count'] ?? 0),
                'rejectedCount' => (int)($result['rejected_count'] ?? 0)
            ];
        } catch (\PDOException $e) {
            error_log('Database Error in getMembershipStats: ' . $e->getMessage());
            return [
                'memberCount' => 0,
                'pendingCount' => 0,
                'rejectedCount' => 0
            ];
        }
    }

    public function updateLoanStatus($data)
    {
        try {
            $this->getConnection()->beginTransaction();

            // Get loan details first
            $sql = "SELECT * FROM pendingloans WHERE id = :id";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':id' => $data['id']]);
            $loan = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$loan) {
                throw new \Exception('Permohonan pembiayaan tidak dijumpai');
            }

            if ($data['status'] === 'approved') {
                // Insert into approved loans with correct status
                $sql = "INSERT INTO loans (
                    member_id, reference_no, loan_type, amount, duration,
                    monthly_payment, bank_name, bank_account, approved_at,
                    approved_by, status
                ) VALUES (
                    :member_id, :reference_no, :loan_type, :amount, :duration,
                    :monthly_payment, :bank_name, :bank_account, :approved_at,
                    :approved_by, 'approved'
                )";

                $stmt = $this->getConnection()->prepare($sql);
                $params = [
                    ':member_id' => $loan['member_id'],
                    ':reference_no' => $loan['reference_no'],
                    ':loan_type' => $loan['loan_type'],
                    ':amount' => $loan['amount'],
                    ':duration' => $loan['duration'],
                    ':monthly_payment' => $loan['monthly_payment'],
                    ':bank_name' => $loan['bank_name'],
                    ':bank_account' => $loan['bank_account'],
                    ':approved_at' => $data['updated_at'],
                    ':approved_by' => $data['updated_by']
                ];
                $stmt->execute($params);

            } else if ($data['status'] === 'rejected') {
                // Insert into rejected loans with correct status
                $sql = "INSERT INTO rejectedloans (
                    member_id, reference_no, loan_type, amount, duration,
                    monthly_payment, bank_name, bank_account, date_received,
                    rejected_by, rejected_at, remarks, status
                ) VALUES (
                    :member_id, :reference_no, :loan_type, :amount, :duration,
                    :monthly_payment, :bank_name, :bank_account, :date_received,
                    :rejected_by, :rejected_at, :remarks, 'rejected'
                )";

                $stmt = $this->getConnection()->prepare($sql);
                $params = [
                    ':member_id' => $loan['member_id'],
                    ':reference_no' => $loan['reference_no'],
                    ':loan_type' => $loan['loan_type'],
                    ':amount' => $loan['amount'],
                    ':duration' => $loan['duration'],
                    ':monthly_payment' => $loan['monthly_payment'],
                    ':bank_name' => $loan['bank_name'],
                    ':bank_account' => $loan['bank_account'],
                    ':date_received' => $loan['date_received'],
                    ':rejected_by' => $data['updated_by'],
                    ':rejected_at' => $data['updated_at'],
                    ':remarks' => $data['remarks']
                ];
                $stmt->execute($params);
            }

            // Delete from pending loans
            $sql = "DELETE FROM pendingloans WHERE id = :id";
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([':id' => $data['id']]);

            $this->getConnection()->commit();
            return true;

        } catch (\PDOException $e) {
            if ($this->getConnection()->inTransaction()) {
                $this->getConnection()->rollBack();
            }
            throw new \Exception('Gagal mengemaskini status pembiayaan: ' . $e->getMessage());
        }
    }

    public function getPendingLoans()
    {
        try {
            $sql = "SELECT l.*, m.name as member_name, m.ic_no 
                    FROM pendingloans l
                    JOIN members m ON l.member_id = m.id
                    WHERE l.status = 'pending'
                    ORDER BY l.date_received DESC";
            
            $stmt = $this->getConnection()->prepare($sql);
            $success = $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;

        } catch (\PDOException $e) {
            error_log('Database Error in getPendingLoans: ' . $e->getMessage());
            error_log('Error code: ' . $e->getCode());
            error_log('Error info: ' . print_r($e->errorInfo, true));
            throw new \Exception('Gagal mendapatkan senarai pembiayaan: ' . $e->getMessage());
        }
    }

    public function getLoansByStatus($status)
    {
        try {
            switch ($status) {
                case 'pending':
                    $sql = "SELECT l.*, m.name as member_name, m.ic_no 
                            FROM pendingloans l
                            JOIN members m ON l.member_id = m.id
                            WHERE l.status = 'pending'
                            ORDER BY l.date_received DESC";
                    break;
                    
                case 'approved':
                    $sql = "SELECT l.*, m.name as member_name, m.ic_no 
                            FROM loans l
                            JOIN members m ON l.member_id = m.id
                            WHERE l.status = 'approved'
                            ORDER BY l.date_received DESC";
                    break;
                    
                case 'rejected':
                    $sql = "SELECT l.*, m.name as member_name, m.ic_no 
                            FROM rejectedloans l
                            JOIN members m ON l.member_id = m.id
                            ORDER BY l.rejected_at DESC";
                    break;
                    
                default:
                    throw new \Exception('Invalid status');
            }
            
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (\PDOException $e) {
            error_log('Database Error in getLoansByStatus: ' . $e->getMessage());
            throw new \Exception('Gagal mendapatkan senarai pembiayaan');
        }
    }

    public function getFinancialTrends() 
    {
        try {
            // Get monthly loan totals - fixed GROUP BY clause
            $loanSql = "SELECT 
                DATE_FORMAT(updated_at, '%b %Y') as month,
                SUM(payment_amount) as loan_amount,
                MIN(DATE_FORMAT(updated_at, '%Y-%m')) as sort_date
                FROM loan_payments
                WHERE updated_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(updated_at, '%b %Y')
                ORDER BY sort_date ASC";

            // Get monthly savings totals - fixed GROUP BY clause
            $savingsSql = "SELECT 
                DATE_FORMAT(created_at, '%b %Y') as month,
                SUM(amount) as savings_amount,
                MIN(DATE_FORMAT(created_at, '%Y-%m')) as sort_date
                FROM savings_transactions 
                WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%b %Y')
                ORDER BY sort_date ASC";

            // Execute loan query
            $stmt = $this->getConnection()->prepare($loanSql);
            $stmt->execute();
            $loanResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Execute savings query
            $stmt = $this->getConnection()->prepare($savingsSql);
            $stmt->execute();
            $savingsResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Initialize arrays for all months
            $monthlyData = [];
            $startDate = new \DateTime("-6 months");
            $endDate = new \DateTime();
            $period = new \DatePeriod($startDate, new \DateInterval('P1M'), $endDate);

            // Initialize all months with zero values
            foreach ($period as $date) {
                $monthKey = $date->format('M Y');
                $monthlyData[$monthKey] = ['loans' => 0, 'savings' => 0];
            }

            // Fill in loan data
            foreach ($loanResults as $row) {
                $monthlyData[$row['month']]['loans'] = (float)$row['loan_amount'];
            }

            // Fill in savings data
            foreach ($savingsResults as $row) {
                $monthlyData[$row['month']]['savings'] = (float)$row['savings_amount'];
            }

            // Convert to arrays for Chart.js
            $trends = [
                'labels' => array_keys($monthlyData),
                'loans' => array_values(array_column($monthlyData, 'loans')),
                'savings' => array_values(array_column($monthlyData, 'savings'))
            ];

            return $trends;

        } catch (\PDOException $e) {
            error_log('Database Error in getFinancialTrends: ' . $e->getMessage());
            throw new \Exception('Failed to fetch financial trends: ' . $e->getMessage());
        }
    }

    public function getTotalSavings() {
        try {
            $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM savings_transactions";
            $stmt = $this->getConnection()->query($sql);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            return $result->total ?? 0;
        } catch (\PDOException $e) {
            error_log('Error in getTotalSavings: ' . $e->getMessage());
            return 0;
        }
    }

    public function getTotalSavingsByMonth($month, $year) {
        try {
            $sql = "SELECT COALESCE(SUM(amount), 0) as total 
                    FROM savings_transactions 
                    WHERE MONTH(created_at) = :month 
                    AND YEAR(created_at) = :year";
            
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([
                ':month' => $month,
                ':year' => $year
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            return $result->total ?? 0;
        } catch (\PDOException $e) {
            error_log('Error in getTotalSavingsByMonth: ' . $e->getMessage());
            return 0;
        }
    }
} 