<?php
/**
 * Reseller / Super Admin Controller
 */

class AdminController {
    public static function login(): void {
        if (Auth::checkAdmin()) {
            header('Location: ' . Helper::url('admin/dashboard'));
            exit;
        }

        $adminCount = (int) (Database::fetch("SELECT COUNT(*) as cnt FROM admins")['cnt'] ?? 0);
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isSetup = isset($_POST['is_setup']) && $_POST['is_setup'] === '1';

            if ($isSetup || $adminCount === 0) {
                // First-time Master Admin Setup
                $agencyName = trim($_POST['agency_name'] ?? 'Loyalty Card Agency');
                $name = trim($_POST['name'] ?? 'Super Admin');
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';
                $phone = trim($_POST['phone'] ?? '');

                if (empty($email) || empty($password)) {
                    $error = 'Email and password are required.';
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    Database::query(
                        "INSERT INTO admins (name, email, password_hash, role, agency_name, agency_phone) VALUES (?, ?, ?, 'superadmin', ?, ?)",
                        [$name, $email, $hash, $agencyName, $phone]
                    );
                    $admin = Database::fetch("SELECT * FROM admins WHERE email = ?", [$email]);
                    Auth::loginAdmin($admin);
                    header('Location: ' . Helper::url('admin/dashboard'));
                    exit;
                }
            } else {
                $email = trim($_POST['email'] ?? '');
                $password = $_POST['password'] ?? '';

                $admin = Database::fetch("SELECT * FROM admins WHERE email = ?", [$email]);
                if ($admin && password_verify($password, $admin['password_hash'])) {
                    Auth::loginAdmin($admin);
                    header('Location: ' . Helper::url('admin/dashboard'));
                    exit;
                } else {
                    $error = 'Invalid reseller administrator credentials.';
                }
            }
        }

        require APP_ROOT . '/views/admin/login.php';
    }

    public static function dashboard(): void {
        Auth::requireAdmin();
        $admin = Auth::currentAdmin();

        $businesses = Business::getAll();
        $totalCustomers = (int) (Database::fetch("SELECT COUNT(*) as cnt FROM customers")['cnt'] ?? 0);
        $totalVisits = (int) (Database::fetch("SELECT COUNT(*) as cnt FROM visits")['cnt'] ?? 0);
        $totalCoinsIssued = (float) (Database::fetch("SELECT SUM(total_coins_earned) as sum_coins FROM customers")['sum_coins'] ?? 0.0);

        require APP_ROOT . '/views/admin/dashboard.php';
    }

    public static function createBusiness(): void {
        Auth::requireAdmin();
        $admin = Auth::currentAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bizName = trim($_POST['business_name'] ?? '');
            $category = trim($_POST['category'] ?? 'Cafe & Restaurant');
            $ownerName = trim($_POST['owner_name'] ?? '');
            $ownerEmail = trim($_POST['owner_email'] ?? '');
            $ownerPassword = $_POST['owner_password'] ?? '123456';
            $ownerPhone = trim($_POST['owner_phone'] ?? '');
            $planDays = (int) ($_POST['plan_days'] ?? 365);
            $planName = trim($_POST['plan_name'] ?? 'Annual Growth');
            $stampTarget = (int) ($_POST['stamp_target'] ?? 8);
            $stampReward = trim($_POST['stamp_reward_title'] ?? 'Free Item / ₹150 Off');

            if (empty($bizName) || empty($ownerEmail) || empty($ownerPassword)) {
                Helper::flash('error', 'Business name, owner email and password are required.');
                header('Location: ' . Helper::url('admin/dashboard'));
                exit;
            }

            if (Business::findByEmail($ownerEmail)) {
                Helper::flash('error', 'A business with this owner email already exists.');
                header('Location: ' . Helper::url('admin/dashboard'));
                exit;
            }

            $slug = Helper::slugify($bizName);
            if (Business::findBySlug($slug)) {
                $slug .= '-' . rand(100, 999);
            }

            $expiry = date('Y-m-d', strtotime("+{$planDays} days"));

            $bizId = Business::create([
                'admin_id' => $admin['id'] ?? 1,
                'name' => $bizName,
                'slug' => $slug,
                'category' => $category,
                'owner_name' => $ownerName,
                'owner_email' => $ownerEmail,
                'owner_password' => $ownerPassword,
                'owner_phone' => $ownerPhone,
                'tagline' => 'Earn stamps & unlock member rewards every visit',
                'stamp_target' => $stampTarget,
                'stamp_reward_title' => $stampReward,
                'plan_name' => $planName,
                'plan_expires_at' => $expiry,
                'status' => 'active'
            ]);

            Helper::flash('success', "Business '{$bizName}' created successfully! Credentials: {$ownerEmail}");
        }

        header('Location: ' . Helper::url('admin/dashboard'));
        exit;
    }

    public static function loginAsOwner(int $businessId): void {
        Auth::requireAdmin();
        $business = Business::findById($businessId);
        if ($business) {
            Auth::loginOwner($business);
            header('Location: ' . Helper::url('owner/dashboard'));
            exit;
        }
        header('Location: ' . Helper::url('admin/dashboard'));
        exit;
    }

    public static function businessAction(): void {
        Auth::requireAdmin();

        $action = $_POST['action'] ?? '';
        $id = (int) ($_POST['business_id'] ?? 0);

        if ($action === 'extend_plan') {
            $days = (int) ($_POST['days'] ?? 30);
            $biz = Business::findById($id);
            $baseTime = ($biz && $biz['plan_expires_at'] && strtotime($biz['plan_expires_at']) > time()) 
                ? strtotime($biz['plan_expires_at']) 
                : time();
            $newExpiry = date('Y-m-d', strtotime("+{$days} days", $baseTime));
            
            Database::query("UPDATE businesses SET plan_expires_at = ?, status = 'active' WHERE id = ?", [$newExpiry, $id]);
            Helper::flash('success', "Plan extended by {$days} days until " . date('M d, Y', strtotime($newExpiry)));
        } elseif ($action === 'toggle_status') {
            $status = $_POST['status'] === 'active' ? 'suspended' : 'active';
            Database::query("UPDATE businesses SET status = ? WHERE id = ?", [$status, $id]);
            Helper::flash('success', "Business status updated to {$status}.");
        } elseif ($action === 'delete') {
            Database::query("DELETE FROM visits WHERE business_id = ?", [$id]);
            Database::query("DELETE FROM customers WHERE business_id = ?", [$id]);
            Database::query("DELETE FROM staff WHERE business_id = ?", [$id]);
            Database::query("DELETE FROM businesses WHERE id = ?", [$id]);
            Helper::flash('success', "Business deleted successfully.");
        }

        header('Location: ' . Helper::url('admin/dashboard'));
        exit;
    }

    public static function logout(): void {
        unset($_SESSION['admin_user']);
        header('Location: ' . Helper::url('admin/login'));
        exit;
    }
}
