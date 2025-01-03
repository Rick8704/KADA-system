<?php
namespace App\Models;
use App\Core\Model;
use PDO;

class Payment extends Model
{
    public function createPayment($data)
    {
        try {
            $this->getConnection()->beginTransaction();

            // Create payment record
            $sql = "INSERT INTO payments (
                member_id, amount, payment_method, payment_status,
                reference_no, provider, provider_reference
            ) VALUES (
                :member_id, :amount, :payment_method, :payment_status,
                :reference_no, :provider, :provider_reference
            )";

            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute([
                ':member_id' => $data['member_id'],
                ':amount' => $data['amount'],
                ':payment_method' => $data['payment_method'],
                ':payment_status' => 'pending',
                ':reference_no' => $this->generateReference(),
                ':provider' => $data['provider'] ?? null,
                ':provider_reference' => null
            ]);

            $paymentId = $this->getConnection()->lastInsertId();
            $this->getConnection()->commit();
            return $paymentId;

        } catch (\PDOException $e) {
            $this->getConnection()->rollBack();
            error_log('Payment Error: ' . $e->getMessage());
            throw new \Exception('Gagal memproses pembayaran');
        }
    }

    private function generateReference()
    {
        return 'PAY' . date('Ymd') . rand(1000, 9999);
    }

    public function updatePaymentStatus($paymentId, $status, $providerReference = null)
    {
        try {
            $sql = "UPDATE payments 
                    SET payment_status = :status, 
                        provider_reference = :provider_ref,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE id = :id";

            $stmt = $this->getConnection()->prepare($sql);
            return $stmt->execute([
                ':status' => $status,
                ':provider_ref' => $providerReference,
                ':id' => $paymentId
            ]);
        } catch (\PDOException $e) {
            error_log('Update Payment Error: ' . $e->getMessage());
            throw new \Exception('Gagal mengemaskini status pembayaran');
        }
    }
} 