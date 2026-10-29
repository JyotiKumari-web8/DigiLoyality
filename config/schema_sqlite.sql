-- Clean SQLite Schema for Digital Loyalty Card Maker & Reseller Panel
-- Zero fake data, fully dynamic

CREATE TABLE IF NOT EXISTS admins (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    role TEXT DEFAULT 'superadmin',
    agency_name TEXT DEFAULT 'DigiLoyalty Agency',
    agency_phone TEXT DEFAULT '',
    agency_logo TEXT DEFAULT '',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS businesses (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    admin_id INTEGER DEFAULT 1,
    name TEXT NOT NULL,
    slug TEXT NOT NULL UNIQUE,
    category TEXT DEFAULT 'Food & Beverage',
    owner_name TEXT NOT NULL,
    owner_email TEXT NOT NULL UNIQUE,
    owner_password TEXT NOT NULL,
    owner_phone TEXT DEFAULT '',
    logo_url TEXT DEFAULT '',
    tagline TEXT DEFAULT 'Earn points & enjoy rewards every visit!',
    theme_color TEXT DEFAULT '#6366f1',
    secondary_color TEXT DEFAULT '#ec4899',
    currency_symbol TEXT DEFAULT '₹',
    address TEXT DEFAULT '',
    
    -- Stamp Card Configuration
    stamp_target INTEGER DEFAULT 8,
    stamp_reward_title TEXT DEFAULT 'Free Item / ₹150 Off',
    stamp_icon TEXT DEFAULT 'fa-star',
    
    -- Coin / Spend-based Configuration
    coin_enabled INTEGER DEFAULT 1,
    coin_rate REAL DEFAULT 10.00,
    coin_redeem_value REAL DEFAULT 0.50,
    welcome_bonus_coins INTEGER DEFAULT 20,
    min_redeem_coins INTEGER DEFAULT 50,
    
    -- Status & Reseller plan
    plan_name TEXT DEFAULT 'Standard Growth',
    plan_expires_at DATE DEFAULT NULL,
    status TEXT DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS staff (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    business_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    email TEXT NOT NULL,
    pin TEXT NOT NULL DEFAULT '1234',
    role TEXT DEFAULT 'cashier',
    is_active INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS customers (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    business_id INTEGER NOT NULL,
    mobile TEXT NOT NULL,
    name TEXT NOT NULL,
    email TEXT DEFAULT NULL,
    birthday DATE DEFAULT NULL,
    qr_token TEXT NOT NULL,
    
    -- Stamps Balance
    current_stamps INTEGER DEFAULT 0,
    total_stamps_earned INTEGER DEFAULT 0,
    total_rewards_claimed INTEGER DEFAULT 0,
    
    -- Coins Balance
    current_coins REAL DEFAULT 0.00,
    total_coins_earned REAL DEFAULT 0.00,
    total_coins_spent REAL DEFAULT 0.00,
    
    -- Visit stats
    total_visits INTEGER DEFAULT 0,
    total_spend REAL DEFAULT 0.00,
    tier TEXT DEFAULT 'bronze',
    last_visit_at DATETIME DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(business_id, mobile)
);

CREATE TABLE IF NOT EXISTS visits (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    business_id INTEGER NOT NULL,
    customer_id INTEGER NOT NULL,
    staff_id INTEGER DEFAULT NULL,
    bill_amount REAL DEFAULT 0.00,
    stamps_added INTEGER DEFAULT 1,
    coins_earned REAL DEFAULT 0.00,
    reward_completed INTEGER DEFAULT 0,
    notes TEXT DEFAULT '',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS coin_transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    business_id INTEGER NOT NULL,
    customer_id INTEGER NOT NULL,
    staff_id INTEGER DEFAULT NULL,
    type TEXT NOT NULL,
    amount REAL NOT NULL,
    balance_after REAL NOT NULL,
    description TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS rewards (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    business_id INTEGER NOT NULL,
    title TEXT NOT NULL,
    description TEXT DEFAULT '',
    reward_type TEXT DEFAULT 'coins',
    cost INTEGER NOT NULL DEFAULT 50,
    discount_value REAL DEFAULT 0.00,
    is_active INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS reward_redemptions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    business_id INTEGER NOT NULL,
    customer_id INTEGER NOT NULL,
    reward_id INTEGER DEFAULT NULL,
    staff_id INTEGER DEFAULT NULL,
    reward_title TEXT NOT NULL,
    voucher_code TEXT NOT NULL UNIQUE,
    status TEXT DEFAULT 'claimed',
    claimed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    redeemed_at DATETIME DEFAULT NULL
);
