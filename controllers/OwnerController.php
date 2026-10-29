<?php
/**
 * Business Owner Dashboard & Settings Controller
 */

class OwnerController {
    public static function login(): void {
        if (Auth::checkOwner()) {
            header('Location: ' . Helper::url('owner/dashboard'));
            exit;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $business = Business::findByEmail($email);
            if ($business && password_verify($password, $business['owner_password'])) {
                if ($business['status'] === 'suspended') {
                    $error = 'Your business account is suspended. Please contact your agency reseller.';
                } else {
                    Auth::loginOwner($business);
                    header('Location: ' . Helper::url('owner/dashboard'));
                    exit;
                }
            } else {
                $error = 'Invalid email or password.';
            }
        }

        require APP_ROOT . '/views/auth/login.php';
    }

    public static function register(): void {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bizName = trim($_POST['business_name'] ?? '');
            $ownerName = trim($_POST['owner_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $phone = trim($_POST['phone'] ?? '');
            $category = trim($_POST['category'] ?? 'General');

            if (empty($bizName) || empty($email) || empty($password)) {
                $error = 'Please fill in all required fields.';
            } elseif (Business::findByEmail($email)) {
                $error = 'An account with this email already exists.';
            } else {
                $slug = Helper::slugify($bizName);
                if (Business::findBySlug($slug)) {
                    $slug .= '-' . rand(100, 999);
                }

                $bizId = Business::create([
                    'admin_id' => 1,
                    'name' => $bizName,
                    'slug' => $slug,
                    'category' => $category,
                    'owner_name' => $ownerName,
                    'owner_email' => $email,
                    'owner_password' => $password,
                    'owner_phone' => $phone,
                    'tagline' => 'Complete the points to claim reward',
                    'currency_symbol' => '₹',
                    'stamp_target' => 6,
                    'stamp_reward_title' => 'Free Welcome Reward / Item',
                    'coin_enabled' => 1,
                    'coin_rate' => 10.00,
                    'coin_redeem_value' => 0.50,
                    'welcome_bonus_coins' => 20
                ]);

                // Auto-create default cashier for the owner
                Staff::create($bizId, $ownerName . ' (Owner)', $email, '1234', 'manager');

                $business = Business::findById($bizId);
                Auth::loginOwner($business);
                Helper::flash('success', 'Your loyalty program has been successfully created!');
                header('Location: ' . Helper::url('owner/dashboard'));
                exit;
            }
        }

        require APP_ROOT . '/views/auth/register.php';
    }

    public static function dashboard(): void {
        Auth::requireOwner();
        $owner = Auth::currentOwner();
        $businessId = (int) $owner['business_id'];
        $business = Business::findById($businessId);
        $stats = Business::getStats($businessId);

        // Fetch recent customer signups
        $recentCustomers = Database::fetchAll(
            "SELECT * FROM customers WHERE business_id = ? ORDER BY id DESC LIMIT 5",
            [$businessId]
        );

        // Fetch recent visits
        $recentVisits = Database::fetchAll(
            "SELECT v.*, c.name as customer_name, c.mobile as customer_mobile 
             FROM visits v 
             JOIN customers c ON v.customer_id = c.id 
             WHERE v.business_id = ? 
             ORDER BY v.created_at DESC LIMIT 6",
            [$businessId]
        );

        require APP_ROOT . '/views/owner/dashboard.php';
    }

    public static function cardSettings(): void {
        Auth::requireOwner();
        $owner = Auth::currentOwner();
        $businessId = (int) $owner['business_id'];
        $business = Business::findById($businessId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updateData = [
                'name' => trim($_POST['name'] ?? $business['name']),
                'tagline' => trim($_POST['tagline'] ?? $business['tagline']),
                'theme_color' => $_POST['theme_color'] ?? $business['theme_color'],
                'secondary_color' => $_POST['secondary_color'] ?? $business['secondary_color'],
                'currency_symbol' => trim($_POST['currency_symbol'] ?? '₹'),
                'stamp_target' => max(3, min(20, (int) ($_POST['stamp_target'] ?? 8))),
                'stamp_reward_title' => trim($_POST['stamp_reward_title'] ?? 'Free Item'),
                'coin_enabled' => isset($_POST['coin_enabled']) ? 1 : 0,
                'coin_rate' => max(1, (float) ($_POST['coin_rate'] ?? 10.00)),
                'coin_redeem_value' => max(0.01, (float) ($_POST['coin_redeem_value'] ?? 0.50)),
                'welcome_bonus_coins' => max(0, (int) ($_POST['welcome_bonus_coins'] ?? 20)),
                'min_redeem_coins' => max(10, (int) ($_POST['min_redeem_coins'] ?? 50)),
                'address' => trim($_POST['address'] ?? '')
            ];

            Business::update($businessId, $updateData);
            Helper::flash('success', 'Loyalty card settings updated successfully!');
            header('Location: ' . Helper::url('owner/card-settings'));
            exit;
        }

        require APP_ROOT . '/views/owner/card_settings.php';
    }

    public static function customers(): void {
        Auth::requireOwner();
        $owner = Auth::currentOwner();
        $businessId = (int) $owner['business_id'];
        $business = Business::findById($businessId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mobile = trim($_POST['mobile'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $bonusCoins = (int)($_POST['bonus_coins'] ?? ($business['welcome_bonus_coins'] ?? 20));

            if (empty($mobile) || empty($name)) {
                Helper::flash('error', 'Customer mobile number and name are required.');
            } else {
                $existing = Customer::findByMobile($businessId, $mobile);
                if ($existing) {
                    Helper::flash('error', "A customer with mobile {$mobile} already exists.");
                } else {
                    $newCust = Customer::register($businessId, $mobile, $name, $email ?: null, $bonusCoins);
                    Helper::flash('success', "Customer '{$name}' (+91 {$mobile}) successfully enrolled with {$bonusCoins} welcome coins!");
                }
            }
            header('Location: ' . Helper::url('owner/customers'));
            exit;
        }

        $filter = $_GET['filter'] ?? 'all';
        $customersList = Customer::getByBusiness($businessId, $filter);

        require APP_ROOT . '/views/owner/customers.php';
    }

    public static function staff(): void {
        Auth::requireOwner();
        $owner = Auth::currentOwner();
        $businessId = (int) $owner['business_id'];
        $business = Business::findById($businessId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'create') {
                $name = trim($_POST['name'] ?? '');
                $email = trim($_POST['email'] ?? '');
                $pin = trim($_POST['pin'] ?? '1234');
                $role = $_POST['role'] ?? 'cashier';

                if (!empty($name) && !empty($email)) {
                    Staff::create($businessId, $name, $email, $pin, $role);
                    Helper::flash('success', "Staff member {$name} added!");
                }
            } elseif ($action === 'toggle') {
                $staffId = (int) ($_POST['staff_id'] ?? 0);
                Staff::toggleStatus($staffId, $businessId);
            }
            header('Location: ' . Helper::url('owner/staff'));
            exit;
        }

        $staffList = Staff::getByBusiness($businessId);
        require APP_ROOT . '/views/owner/staff.php';
    }

    public static function rewards(): void {
        Auth::requireOwner();
        $owner = Auth::currentOwner();
        $businessId = (int) $owner['business_id'];
        $business = Business::findById($businessId);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'create') {
                $title = trim($_POST['title'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $cost = (int) ($_POST['cost'] ?? 50);
                $discountValue = (float) ($_POST['discount_value'] ?? 0.00);

                if (!empty($title)) {
                    Reward::create([
                        'business_id' => $businessId,
                        'title' => $title,
                        'description' => $description,
                        'reward_type' => 'coins',
                        'cost' => $cost,
                        'discount_value' => $discountValue
                    ]);
                    Helper::flash('success', 'Reward added to catalog!');
                }
            } elseif ($action === 'delete') {
                $rewardId = (int) ($_POST['reward_id'] ?? 0);
                Reward::delete($rewardId, $businessId);
                Helper::flash('success', 'Reward deleted.');
            }
            header('Location: ' . Helper::url('owner/rewards'));
            exit;
        }

        $rewardsList = Reward::getByBusiness($businessId);
        require APP_ROOT . '/views/owner/rewards.php';
    }

    public static function standee(): void {
        Auth::requireOwner();
        $owner = Auth::currentOwner();
        $businessId = (int) $owner['business_id'];
        $business = Business::findById($businessId);

        $customerUrl = Helper::url("business.php?slug={$business['slug']}");
        require APP_ROOT . '/views/owner/standee.php';
    }

    public static function logout(): void {
        unset($_SESSION['owner_user']);
        header('Location: ' . Helper::url('login'));
        exit;
    }
}
