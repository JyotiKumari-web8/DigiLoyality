<?php
/**
 * Customer Loyalty Pass Controller
 */

class CustomerController {
    /**
     * Show Join / Login page or Digital Pass
     */
    public static function view(string $slug): void {
        $business = Business::findBySlug($slug);
        if (!$business) {
            http_response_code(404);
            require APP_ROOT . '/views/404.php';
            return;
        }

        $sessionCustomer = Auth::currentCustomer((int) $business['id']);
        $customer = null;

        if ($sessionCustomer) {
            $customer = Customer::findById((int) $sessionCustomer['id']);
        }

        // If customer is logged in, show their digital card pass
        if ($customer) {
            $tier = Helper::calcTier((int) $customer['total_visits'], (float) $customer['total_spend']);
            $rewards = Reward::getByBusiness((int) $business['id']);
            $vouchers = Reward::getCustomerVouchers((int) $customer['id']);
            $history = Customer::getRecentHistory((int) $customer['id'], 6);
            $coinMoneyValue = (float) $customer['current_coins'] * (float) ($business['coin_redeem_value'] ?? 0.50);

            require APP_ROOT . '/views/customer/pass.php';
            return;
        }

        // Otherwise show the Join / Login screen (matching business.php?slug=food-bites)
        require APP_ROOT . '/views/customer/join.php';
    }

    /**
     * Handle Customer Join / Login form submission
     */
    public static function handleJoin(string $slug): void {
        $business = Business::findBySlug($slug);
        if (!$business) {
            Helper::flash('error', 'Business not found.');
            header('Location: ' . Helper::url(''));
            exit;
        }

        $mobile = trim($_POST['mobile'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($mobile)) {
            Helper::flash('error', 'Please enter your mobile number.');
            header("Location: " . Helper::url("business.php?slug={$slug}"));
            exit;
        }

        // Check if customer already exists for this business
        $customer = Customer::findByMobile((int) $business['id'], $mobile);

        if (!$customer) {
            // New Registration - award welcome bonus coins!
            $welcomeCoins = (int) ($business['welcome_bonus_coins'] ?? 20);
            $customerName = !empty($name) ? $name : 'Member ' . substr(preg_replace('/[^0-9]/', '', $mobile), -4);
            $customer = Customer::register((int) $business['id'], $mobile, $customerName, $email ?: null, $welcomeCoins);
            Helper::flash('success', "Welcome! You received {$welcomeCoins} bonus reward coins!");
        } else {
            // Existing customer login
            if (!empty($name) && $customer['name'] !== $name) {
                Database::query("UPDATE customers SET name = ? WHERE id = ?", [$name, $customer['id']]);
                $customer['name'] = $name;
            }
            Helper::flash('success', "Welcome back, {$customer['name']}!");
        }

        // Set customer session
        Auth::loginCustomer($customer);

        header("Location: " . Helper::url("business.php?slug={$slug}"));
        exit;
    }

    /**
     * Customer Logout from card
     */
    public static function logout(string $slug): void {
        $business = Business::findBySlug($slug);
        if ($business) {
            unset($_SESSION['customer_' . $business['id']]);
        }
        header("Location: " . Helper::url("business.php?slug={$slug}"));
        exit;
    }
}
