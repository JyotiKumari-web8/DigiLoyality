/**
 * DigiLoyalty Pro - Terminal & Digital Pass Frontend Logic
 */

const LoyaltyApp = {
    // Current terminal state
    currentCustomer: null,

    // Initialize QR Code inside a container
    renderQRCode: function(containerId, text, size = 180) {
        const container = document.getElementById(containerId);
        if (!container) return;

        // Use QRious / standard QRCode generator or Google Chart / quick SVG
        container.innerHTML = `<img src="https://api.qrserver.com/v1/create-qr-code/?size=${size}x${size}&data=${encodeURIComponent(text)}&margin=1" alt="QR Code" style="width:${size}px; height:${size}px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.08);">`;
    },

    // Confetti celebration when customer completes stamp card
    triggerConfetti: function() {
        const colors = ['#6366f1', '#ec4899', '#f59e0b', '#10b981', '#3b82f6'];
        for (let i = 0; i < 40; i++) {
            const conf = document.createElement('div');
            conf.className = 'confetti-particle';
            conf.style.left = Math.random() * 100 + 'vw';
            conf.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            conf.style.animationDuration = (Math.random() * 2 + 1.5) + 's';
            conf.style.opacity = Math.random() + 0.5;
            conf.style.transform = `scale(${Math.random() * 0.8 + 0.4})`;
            document.body.appendChild(conf);
            setTimeout(() => conf.remove(), 3000);
        }
    },

    // Fast phone keypad typing for cashier terminal
    keypadPress: function(num) {
        const input = document.getElementById('terminal-phone-input');
        if (!input) return;
        if (input.value.length < 10) {
            input.value += num;
        }
        if (input.value.length === 10) {
            LoyaltyApp.searchCustomer(input.value);
        }
    },

    keypadBackspace: function() {
        const input = document.getElementById('terminal-phone-input');
        if (!input) return;
        input.value = input.value.slice(0, -1);
    },

    keypadClear: function() {
        const input = document.getElementById('terminal-phone-input');
        if (!input) return;
        input.value = '';
        const card = document.getElementById('customer-action-card');
        if (card) card.style.display = 'none';
    },

    // Search customer by phone or QR
    searchCustomer: async function(query) {
        if (!query) {
            query = document.getElementById('terminal-phone-input')?.value;
        }
        if (!query) return;

        const statusEl = document.getElementById('terminal-status');
        if (statusEl) statusEl.innerHTML = '<span class="text-indigo-600"><i class="fas fa-spinner fa-spin"></i> Looking up customer...</span>';

        try {
            const formData = new FormData();
            formData.append('action', 'terminal_lookup');
            formData.append('query', query);

            const res = await fetch('api.php', { method: 'POST', body: formData });
            const data = await res.json();

            if (data.success && data.found) {
                LoyaltyApp.currentCustomer = data.customer;
                LoyaltyApp.displayCustomerTerminal(data.customer);
                if (statusEl) statusEl.innerHTML = '<span class="text-green-600"><i class="fas fa-check-circle"></i> Customer Found</span>';
            } else {
                LoyaltyApp.currentCustomer = null;
                const actionCard = document.getElementById('customer-action-card');
                if (actionCard) actionCard.style.display = 'none';

                const regCard = document.getElementById('quick-register-card');
                if (regCard) {
                    regCard.style.display = 'block';
                    document.getElementById('quick-reg-mobile').value = query;
                }
                if (statusEl) statusEl.innerHTML = `<span class="text-amber-600"><i class="fas fa-user-plus"></i> New visitor! Register in 1 tap below.</span>`;
            }
        } catch (err) {
            console.error(err);
            if (statusEl) statusEl.innerHTML = '<span class="text-red-500">Error connecting to server.</span>';
        }
    },

    // Display customer in terminal
    displayCustomerTerminal: function(c) {
        const regCard = document.getElementById('quick-register-card');
        if (regCard) regCard.style.display = 'none';

        const actionCard = document.getElementById('customer-action-card');
        if (!actionCard) return;

        actionCard.style.display = 'block';
        document.getElementById('c-name').textContent = c.name;
        document.getElementById('c-mobile').textContent = '+91 ' + c.mobile;
        document.getElementById('c-visits').textContent = c.total_visits;
        document.getElementById('c-coins').textContent = c.current_coins;
        document.getElementById('c-stamps').textContent = `${c.current_stamps} / ${c.stamp_target}`;
        document.getElementById('c-coin-val').textContent = c.coin_money_value;
        
        // Tier badge
        const tierBadge = document.getElementById('c-tier');
        if (tierBadge) {
            tierBadge.textContent = `${c.tier.badge} ${c.tier.name}`;
            tierBadge.className = `badge-tier ${c.tier.tier}`;
        }

        // At-risk alert
        const atRiskBox = document.getElementById('c-at-risk-box');
        if (atRiskBox) {
            atRiskBox.style.display = c.is_at_risk ? 'block' : 'none';
        }

        // Check if stamp card ready
        const stampRewardBox = document.getElementById('stamp-reward-alert');
        if (stampRewardBox) {
            stampRewardBox.style.display = (c.current_stamps >= c.stamp_target - 1) ? 'block' : 'none';
        }

        // Vouchers list
        const vouchersList = document.getElementById('c-vouchers-list');
        if (vouchersList) {
            if (c.vouchers && c.vouchers.length > 0) {
                let html = '';
                c.vouchers.forEach(v => {
                    const isClaimed = v.status === 'claimed';
                    html += `
                        <div class="flex items-center justify-between p-3 rounded-xl border ${isClaimed ? 'border-amber-200 bg-amber-50' : 'border-gray-100 bg-gray-50'} text-xs">
                            <div>
                                <strong class="text-gray-900">${v.reward_title}</strong>
                                <div class="font-mono text-indigo-600 font-bold tracking-wider">${v.voucher_code}</div>
                            </div>
                            ${isClaimed ? 
                                `<button onclick="LoyaltyApp.redeemVoucherCode('${v.voucher_code}')" class="btn btn-sm btn-primary">Redeem</button>` : 
                                `<span class="text-gray-400">Redeemed</span>`}
                        </div>`;
                });
                vouchersList.innerHTML = html;
            } else {
                vouchersList.innerHTML = '<p class="text-xs text-gray-400">No active vouchers yet.</p>';
            }
        }
    },

    // 1-Tap: Add Stamp & Coins
    addStampAndCoins: async function() {
        if (!LoyaltyApp.currentCustomer) return;

        const billInput = document.getElementById('terminal-bill-input');
        const billAmount = billInput ? parseFloat(billInput.value) || 0 : 0;
        const stampsToAdd = 1;

        const btn = document.getElementById('btn-add-stamp');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Recording...';
        }

        try {
            const formData = new FormData();
            formData.append('action', 'terminal_add_stamp_coins');
            formData.append('customer_id', LoyaltyApp.currentCustomer.id);
            formData.append('stamps', stampsToAdd);
            formData.append('bill_amount', billAmount);

            const res = await fetch('api.php', { method: 'POST', body: formData });
            const data = await res.json();

            if (data.success) {
                if (data.reward_completed) {
                    LoyaltyApp.triggerConfetti();
                    alert(`🎉 REWARD COMPLETED! Customer earned: ${data.voucher_code}`);
                }
                alert(data.message);
                if (billInput) billInput.value = '';
                LoyaltyApp.searchCustomer(LoyaltyApp.currentCustomer.mobile);
            } else {
                alert(data.message || 'Error recording visit');
            }
        } catch (e) {
            console.error(e);
            alert('Server error.');
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-stamp mr-1"></i> +1 Visit Stamp';
            }
        }
    },

    // Redeem Voucher Code
    redeemVoucherCode: async function(code) {
        if (!confirm(`Redeem voucher code ${code}?`)) return;

        try {
            const formData = new FormData();
            formData.append('action', 'terminal_redeem_voucher');
            formData.append('voucher_code', code);

            const res = await fetch('api.php', { method: 'POST', body: formData });
            const data = await res.json();

            alert(data.message);
            if (data.success && LoyaltyApp.currentCustomer) {
                LoyaltyApp.searchCustomer(LoyaltyApp.currentCustomer.mobile);
            }
        } catch (e) {
            alert('Failed to redeem voucher.');
        }
    },

    // Burn Coins for Bill Discount
    burnCoins: async function() {
        if (!LoyaltyApp.currentCustomer) return;
        const coinsInput = document.getElementById('terminal-coins-burn-input');
        const coinsToBurn = coinsInput ? parseFloat(coinsInput.value) || 0 : 0;

        if (coinsToBurn <= 0) {
            alert('Please enter coins to redeem.');
            return;
        }

        if (!confirm(`Redeem ${coinsToBurn} coins for bill discount?`)) return;

        try {
            const formData = new FormData();
            formData.append('action', 'terminal_redeem_coins');
            formData.append('customer_id', LoyaltyApp.currentCustomer.id);
            formData.append('coins_to_burn', coinsToBurn);

            const res = await fetch('api.php', { method: 'POST', body: formData });
            const data = await res.json();

            if (data.success) {
                alert(`✅ Discount Applied: ${data.formatted_discount}`);
                if (coinsInput) coinsInput.value = '';
                LoyaltyApp.searchCustomer(LoyaltyApp.currentCustomer.mobile);
            } else {
                alert(data.message || 'Error redeeming coins');
            }
        } catch (e) {
            alert('Error redeeming coins.');
        }
    },

    // Quick Register
    quickRegister: async function() {
        const mobile = document.getElementById('quick-reg-mobile')?.value;
        const name = document.getElementById('quick-reg-name')?.value;

        if (!mobile) {
            alert('Please enter a mobile number.');
            return;
        }

        try {
            const formData = new FormData();
            formData.append('action', 'terminal_register');
            formData.append('mobile', mobile);
            formData.append('name', name);

            const res = await fetch('api.php', { method: 'POST', body: formData });
            const data = await res.json();

            if (data.success) {
                alert(data.message);
                LoyaltyApp.searchCustomer(mobile);
            } else {
                alert(data.message || 'Error registering');
            }
        } catch (e) {
            alert('Registration error.');
        }
    }
};

// CSS confetti dynamic injection
const style = document.createElement('style');
style.textContent = `
.confetti-particle {
    position: fixed;
    top: -20px;
    width: 10px;
    height: 14px;
    z-index: 9999;
    border-radius: 2px;
    pointer-events: none;
    animation: confettiFall linear forwards;
}
@keyframes confettiFall {
    0% { transform: translateY(0) rotate(0deg); opacity: 1; }
    100% { transform: translateY(105vh) rotate(720deg); opacity: 0; }
}
`;
document.head.appendChild(style);
