<?php
/**
 * Fast AJAX API Endpoints for Cashier Terminal & Digital Pass
 */

define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/core/Database.php';
require_once APP_ROOT . '/core/Auth.php';
require_once APP_ROOT . '/core/Helper.php';
require_once APP_ROOT . '/models/Business.php';
require_once APP_ROOT . '/models/Customer.php';
require_once APP_ROOT . '/models/Staff.php';
require_once APP_ROOT . '/models/Reward.php';

$config = file_exists(APP_ROOT . '/config/config.php') ? require APP_ROOT . '/config/config.php' : [];
Database::init($config['db'] ?? []);
Helper::init($config);
Auth::init();

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? $_POST['action'] ?? '';

try {
    // 1. Terminal: Lookup Customer (by phone or QR token)
    if ($action === 'terminal_lookup') {
        if (!Auth::checkStaff() && !Auth::checkOwner()) {
            throw new Exception("Unauthorized terminal access.");
        }

        $staff = Auth::currentStaff();
        $owner = Auth::currentOwner();
        $businessId = $staff ? (int) $staff['business_id'] : (int) $owner['business_id'];
        $business = Business::findById($businessId);

        $query = trim($_POST['query'] ?? '');
        if (empty($query)) {
            throw new Exception("Please provide a phone number or QR token.");
        }

        $customer = null;
        // Check if QR token
        if (strlen($query) >= 20) {
            $customer = Customer::findByQrToken($query);
        } else {
            $customer = Customer::findByMobile($businessId, $query);
        }

        if (!$customer) {
            Helper::jsonResponse(['success' => false, 'found' => false, 'message' => 'Customer not found. You can register them in 2 seconds below.']);
        }

        $tier = Helper::calcTier((int) $customer['total_visits'], (float) $customer['total_spend']);
        $daysSince = Helper::daysSince($customer['last_visit_at']);
        $vouchers = Reward::getCustomerVouchers((int) $customer['id']);
        $coinMoney = (float) $customer['current_coins'] * (float) ($business['coin_redeem_value'] ?? 0.50);

        Helper::jsonResponse([
            'success' => true,
            'found' => true,
            'customer' => [
                'id' => $customer['id'],
                'name' => $customer['name'],
                'mobile' => $customer['mobile'],
                'current_stamps' => (int) $customer['current_stamps'],
                'stamp_target' => (int) ($business['stamp_target'] ?? 8),
                'stamp_reward_title' => $business['stamp_reward_title'] ?? 'Free Item',
                'current_coins' => (float) $customer['current_coins'],
                'coin_money_value' => Helper::formatCurrency($coinMoney, $business['currency_symbol'] ?? '₹'),
                'total_visits' => (int) $customer['total_visits'],
                'days_since_last_visit' => $daysSince,
                'is_at_risk' => $daysSince >= 30,
                'tier' => $tier,
                'vouchers' => $vouchers
            ]
        ]);
    }

    // 2. Terminal: Quick Register Customer
    if ($action === 'terminal_register') {
        if (!Auth::checkStaff() && !Auth::checkOwner()) {
            throw new Exception("Unauthorized terminal access.");
        }

        $staff = Auth::currentStaff();
        $owner = Auth::currentOwner();
        $businessId = $staff ? (int) $staff['business_id'] : (int) $owner['business_id'];
        $business = Business::findById($businessId);

        $mobile = trim($_POST['mobile'] ?? '');
        $name = trim($_POST['name'] ?? '');

        if (empty($mobile)) {
            throw new Exception("Mobile number is required.");
        }

        $existing = Customer::findByMobile($businessId, $mobile);
        if ($existing) {
            throw new Exception("Customer already registered with this mobile number.");
        }

        $bonusCoins = (int) ($business['welcome_bonus_coins'] ?? 20);
        $customer = Customer::register($businessId, $mobile, $name ?: 'Member ' . substr($mobile, -4), null, $bonusCoins);

        Helper::jsonResponse([
            'success' => true,
            'message' => "Customer registered with {$bonusCoins} welcome bonus coins!",
            'customer_id' => $customer['id'],
            'mobile' => $customer['mobile']
        ]);
    }

    // 3. Terminal: Add Stamp (+1 Visit) & Credit Coins
    if ($action === 'terminal_add_stamp_coins') {
        if (!Auth::checkStaff() && !Auth::checkOwner()) {
            throw new Exception("Unauthorized.");
        }

        $staff = Auth::currentStaff();
        $owner = Auth::currentOwner();
        $businessId = $staff ? (int) $staff['business_id'] : (int) $owner['business_id'];
        $staffId = $staff ? (int) $staff['id'] : null;
        $business = Business::findById($businessId);

        $customerId = (int) ($_POST['customer_id'] ?? 0);
        $stampsToAdd = max(0, (int) ($_POST['stamps'] ?? 1));
        $billAmount = max(0.0, (float) ($_POST['bill_amount'] ?? 0.0));
        $notes = trim($_POST['notes'] ?? 'Counter Check-in');

        // Calculate coins based on bill amount
        $coinsToAdd = 0.0;
        if ($billAmount > 0 && ($business['coin_enabled'] ?? 1)) {
            $coinRate = (float) ($business['coin_rate'] ?? 10.00);
            if ($coinRate > 0) {
                $coinsToAdd = round($billAmount / $coinRate, 2);
            }
        }

        $result = Customer::addStampAndCoins(
            $customerId,
            $stampsToAdd,
            $coinsToAdd,
            $billAmount,
            $staffId,
            $notes
        );

        Helper::jsonResponse([
            'success' => true,
            'message' => "Visit recorded! Added {$stampsToAdd} stamp(s) and credited {$result['coins_added']} coins.",
            'reward_completed' => $result['reward_completed'],
            'voucher_code' => $result['voucher_code'],
            'new_stamps' => $result['customer']['current_stamps'],
            'new_coins' => $result['customer']['current_coins'],
            'total_visits' => $result['customer']['total_visits']
        ]);
    }

    // 4. Terminal: Redeem Coins for Bill Discount
    if ($action === 'terminal_redeem_coins') {
        if (!Auth::checkStaff() && !Auth::checkOwner()) {
            throw new Exception("Unauthorized.");
        }

        $staff = Auth::currentStaff();
        $owner = Auth::currentOwner();
        $businessId = $staff ? (int) $staff['business_id'] : (int) $owner['business_id'];
        $staffId = $staff ? (int) $staff['id'] : null;
        $business = Business::findById($businessId);

        $customerId = (int) ($_POST['customer_id'] ?? 0);
        $coinsToBurn = max(1, (float) ($_POST['coins_to_burn'] ?? 0));
        $minCoins = (int) ($business['min_redeem_coins'] ?? 50);

        if ($coinsToBurn < $minCoins) {
            throw new Exception("Minimum {$minCoins} coins required to redeem.");
        }

        $discountVal = $coinsToBurn * (float) ($business['coin_redeem_value'] ?? 0.50);
        $formattedDiscount = Helper::formatCurrency($discountVal, $business['currency_symbol'] ?? '₹');

        $result = Customer::redeemCoins(
            $customerId,
            $coinsToBurn,
            "Redeemed for {$formattedDiscount} bill discount",
            $staffId
        );

        Helper::jsonResponse([
            'success' => true,
            'message' => "Successfully redeemed {$coinsToBurn} coins for a discount of {$formattedDiscount}!",
            'discount_value' => $discountVal,
            'formatted_discount' => $formattedDiscount,
            'new_balance' => $result['new_balance']
        ]);
    }

    // 5. Terminal: Redeem Voucher Code
    if ($action === 'terminal_redeem_voucher') {
        if (!Auth::checkStaff() && !Auth::checkOwner()) {
            throw new Exception("Unauthorized.");
        }

        $staff = Auth::currentStaff();
        $owner = Auth::currentOwner();
        $businessId = $staff ? (int) $staff['business_id'] : (int) $owner['business_id'];
        $staffId = $staff ? (int) $staff['id'] : null;

        $code = trim($_POST['voucher_code'] ?? '');
        if (empty($code)) {
            throw new Exception("Please enter a voucher code.");
        }

        $result = Reward::redeemVoucher($code, $businessId, $staffId);

        Helper::jsonResponse([
            'success' => true,
            'message' => "Voucher {$code} ({$result['title']}) successfully redeemed!"
        ]);
    }

    throw new Exception("Unknown action: {$action}");

} catch (Exception $e) {
    Helper::jsonResponse([
        'success' => false,
        'message' => $e->getMessage()
    ], 400);
}
