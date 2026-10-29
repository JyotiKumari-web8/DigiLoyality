<?php
/**
 * Staff / Cashier Terminal Controller
 */

class StaffController {
    /**
     * Staff Login Screen & Submission
     */
    public static function login(): void {
        if (Auth::checkStaff()) {
            header('Location: ' . Helper::url('staff/terminal'));
            exit;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $pin = trim($_POST['pin'] ?? '');

            $result = Staff::authenticate($email, $pin);
            if ($result) {
                Auth::loginStaff($result['staff'], $result['business']);
                header('Location: ' . Helper::url('staff/terminal'));
                exit;
            } else {
                $error = 'Invalid staff email or 4-digit PIN.';
            }
        }

        require APP_ROOT . '/views/auth/staff_login.php';
    }

    /**
     * Counter Speed Terminal
     */
    public static function terminal(): void {
        Auth::requireStaff();
        $staffUser = Auth::currentStaff();
        $ownerUser = Auth::currentOwner();

        $businessId = $staffUser ? (int) $staffUser['business_id'] : (int) $ownerUser['business_id'];
        $business = Business::findById($businessId);

        // Fetch recent visits today at this terminal
        $recentVisits = Database::fetchAll(
            "SELECT v.*, c.name as customer_name, c.mobile as customer_mobile 
             FROM visits v 
             JOIN customers c ON v.customer_id = c.id 
             WHERE v.business_id = ? AND DATE(v.created_at) = ? 
             ORDER BY v.created_at DESC LIMIT 8",
            [$businessId, date('Y-m-d')]
        );

        require APP_ROOT . '/views/staff/terminal.php';
    }

    /**
     * Staff Logout
     */
    public static function logout(): void {
        unset($_SESSION['staff_user']);
        header('Location: ' . Helper::url('staff/login'));
        exit;
    }
}
