# DigiLoyalty Pro — Customer Loyalty & Repeat-Visit Management System

A mobile-first, high-converting digital loyalty card and repeat-visit retention platform developed for local businesses like cafes, restaurants, salons, barbershops, and retail boutiques.

Built with native PHP 8.3 (Clean MVC), modern CSS design tokens, glassmorphism, and Apple Wallet aesthetic. **Zero app installation required for customers.**

---

## 🚀 Quick Start (Running Locally)

Open PowerShell inside this folder (`C:\Users\Jyoti Kumari\Desktop\New folder`):

```bash
php -S localhost:8000 -t public
```

Then open your browser to **[http://localhost:8000](http://localhost:8000)**.

---

## 📌 System Workflow & Key Modules

### 1. Database Setup
* **URL:** `http://localhost:8000/install`
* Connect your local MySQL database with your credentials.
* Cleanly initializes all database tables with zero fake data.

### 2. Business Owner Registration & Dashboard
* **Registration:** `http://localhost:8000/register`
* **Login:** `http://localhost:8000/login`
* **Features:**
  * Configure loyalty rules (stamp targets, coin earn/redeem rates)
  * Repeat Visit Rate (%) & 7-day visit trend chart
  * At-Risk Customer alerts (customers absent 30+ days)
  * 1-Click WhatsApp Win-Back message generator
  * Card Visual Builder (custom colors, stamp icon, rewards)
  * Printable Counter Tent Standee card (`/owner/standee`)
  * Add Cashier / Staff members with 4-digit PINs

### 3. Customer Mobile Digital Pass
* **URL:** `http://localhost:8000/card/<your-business-slug>`
* Customers join in 5 seconds with their mobile number (zero app downloads).
* **Features:**
  * Live Apple Wallet style card
  * Interactive visit stamp punch circles
  * Live Reward Coins balance (with ₹ equivalent value)
  * Customer personal QR code
  * Tier badge (Bronze 🥉, Silver 🥈, Gold 🥇)
  * 1-Click WhatsApp Referral share link
  * Rewards catalog & claimed vouchers

### 4. Cashier Speed Terminal (Counter Screen)
* **URL:** `http://localhost:8000/staff/login`
* Staff signs in with their email and 4-digit PIN.
* **Features:**
  * Fast 10-digit phone keypad lookup (3 seconds per customer)
  * +1 Visit Stamp with celebratory confetti animation
  * Enter bill amount -> auto-credits coins with tier multipliers
  * 1-click reward voucher redemption
  * Fast customer enrollment for walk-ins

### 5. Agency Reseller Console
* **URL:** `http://localhost:8000/admin/login`
* Manage multiple client merchants and subscriptions.

---

## 📂 Project Architecture

```
New folder/
├── config/
│   ├── config.php          # Database credentials & app settings
│   └── schema.sql          # Relational database schema
├── core/
│   ├── Database.php        # Safe PDO connection & queries
│   ├── Auth.php            # Role-based sessions (Admin, Owner, Staff, Customer)
│   └── Helper.php          # Currency formatter, tier calculator, flash alerts
├── models/
│   ├── Business.php        # Merchant data & analytics
│   ├── Customer.php        # Customer wallet, stamps, and coin ledger
│   ├── Staff.php           # Cashier accounts & PINs
│   └── Reward.php          # Milestone rewards & single-use vouchers
├── controllers/
│   ├── CustomerController.php # Mobile digital pass
│   ├── StaffController.php    # Cashier speed terminal
│   ├── OwnerController.php    # Business dashboard & CRM
│   ├── AdminController.php    # Reseller management
│   └── InstallController.php  # Auto-migrator & demo seeder
├── views/
│   ├── customer/           # Mobile pass views
│   ├── staff/              # Cashier terminal
│   ├── owner/              # Dashboard, CRM, standee, settings
│   ├── admin/              # Reseller console
│   └── landing.php         # SaaS marketing homepage
└── public/
    ├── index.php           # Front controller
    ├── business.php        # Direct compatibility entry point
    ├── api.php             # Fast AJAX API for terminal
    ├── css/app.css         # Modern design system & wallet aesthetics
    └── js/app.js           # Keypad, QR rendering & confetti animation
```
