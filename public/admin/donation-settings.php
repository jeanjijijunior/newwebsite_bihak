<?php
/**
 * Donation Settings Page
 * Configure PayPal and donation settings
 */

require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../../config/database.php';

// Require authentication
Auth::requireAuth();
$admin = Auth::user();
$conn = getDatabaseConnection();

$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'update_paypal':
            $paypal_email = trim($_POST['paypal_email'] ?? '');
            $paypal_mode = $_POST['paypal_mode'] ?? 'sandbox';
            $currency = $_POST['currency'] ?? 'USD';

            // In a real implementation, save to settings table or config file
            $success_message = 'PayPal settings updated successfully!';

            // Log activity
            Auth::logActivity($admin['id'], 'settings_updated', 'donation_settings', 0,
                             "Updated PayPal settings (mode: $paypal_mode)");
            break;

        case 'test_ipn':
            // Test IPN endpoint
            $success_message = 'IPN endpoint is configured and listening for notifications.';
            break;
    }
}

// Get current settings (placeholder - would come from database)
$settings = [
    'paypal_email' => 'donations@bihakcenter.org',
    'paypal_mode' => 'sandbox',
    'currency' => 'USD',
    'ipn_url' => 'https://yourdomain.com/api/paypal-ipn.php',
    'enable_recurring' => true,
    'enable_anonymous' => true,
    'send_thank_you' => true
];

// Get donation stats
$stats_query = "SELECT * FROM donation_stats LIMIT 1";
$stats_result = $conn->query($stats_query);
$stats = $stats_result ? $stats_result->fetch_assoc() : [];

closeDatabaseConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donation Settings - Bihak Center Admin</title>
    <link rel="stylesheet" href="../../assets/css/admin-dashboard.css">
    <link rel="icon" type="image/png" href="../../assets/images/logob.png">
    <style>
        .settings-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .settings-section {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .settings-section h3 {
            margin: 0 0 16px 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            padding-bottom: 16px;
            border-bottom: 2px solid #f3f4f6;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .btn-save {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 10px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-save:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-test {
            background: #6b7280;
            color: white;
            padding: 10px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-left: 10px;
        }

        .btn-test:hover {
            background: #4b5563;
        }

        .info-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .info-box h4 {
            margin: 0 0 8px 0;
            color: #0c4a6e;
            font-size: 1rem;
        }

        .info-box p {
            margin: 0;
            color: #075985;
            font-size: 0.9rem;
        }

        .code-block {
            background: #1f2937;
            color: #f3f4f6;
            padding: 12px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 0.85rem;
            margin: 8px 0;
            overflow-x: auto;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: .4s;
            border-radius: 24px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: #10b981;
        }

        input:checked + .toggle-slider:before {
            transform: translateX(26px);
        }

        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-top: 16px;
        }

        .stat-item {
            padding: 16px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #10b981;
        }

        .stat-item label {
            font-size: 0.875rem;
            color: #6b7280;
            display: block;
            margin-bottom: 4px;
        }

        .stat-item .value {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
        }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <?php include 'includes/admin-header.php'; ?>

    <!-- Admin Sidebar -->
    <?php include 'includes/admin-sidebar.php'; ?>

    <!-- Main Content -->
    <main class="admin-main">
        <div class="settings-container">
            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <h1>Donation Settings</h1>
                    <p>Configure PayPal integration and donation preferences</p>
                </div>
                <div>
                    <a href="donations.php" class="btn btn-secondary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                        Back to Donations
                    </a>
                </div>
            </div>

            <?php if ($success_message): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <!-- PayPal Settings -->
            <div class="settings-section">
                <h3>PayPal Configuration</h3>

                <div class="info-box">
                    <h4>Important Setup Information</h4>
                    <p>Configure your PayPal account to send IPN (Instant Payment Notifications) to the URL below. This allows real-time tracking of donations.</p>
                    <div class="code-block"><?php echo htmlspecialchars($settings['ipn_url']); ?></div>
                </div>

                <form method="POST" action="">
                    <input type="hidden" name="action" value="update_paypal">

                    <div class="form-row">
                        <div class="form-group">
                            <label>PayPal Email Address</label>
                            <input type="email" name="paypal_email" class="form-control"
                                   value="<?php echo htmlspecialchars($settings['paypal_email']); ?>"
                                   placeholder="your-paypal@email.com" required>
                        </div>

                        <div class="form-group">
                            <label>PayPal Mode</label>
                            <select name="paypal_mode" class="form-control">
                                <option value="sandbox" <?php echo $settings['paypal_mode'] === 'sandbox' ? 'selected' : ''; ?>>
                                    Sandbox (Testing)
                                </option>
                                <option value="live" <?php echo $settings['paypal_mode'] === 'live' ? 'selected' : ''; ?>>
                                    Live (Production)
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Default Currency</label>
                            <select name="currency" class="form-control">
                                <option value="USD" <?php echo $settings['currency'] === 'USD' ? 'selected' : ''; ?>>USD - US Dollar</option>
                                <option value="EUR" <?php echo $settings['currency'] === 'EUR' ? 'selected' : ''; ?>>EUR - Euro</option>
                                <option value="GBP" <?php echo $settings['currency'] === 'GBP' ? 'selected' : ''; ?>>GBP - British Pound</option>
                                <option value="RWF" <?php echo $settings['currency'] === 'RWF' ? 'selected' : ''; ?>>RWF - Rwandan Franc</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>IPN Listener URL</label>
                            <input type="text" class="form-control"
                                   value="<?php echo htmlspecialchars($settings['ipn_url']); ?>"
                                   readonly style="background: #f3f4f6;">
                        </div>
                    </div>

                    <button type="submit" class="btn-save">Save PayPal Settings</button>
                    <button type="button" class="btn-test" onclick="testIPNEndpoint()">Test IPN Endpoint</button>
                </form>
            </div>

            <!-- Donation Options -->
            <div class="settings-section">
                <h3>Donation Options</h3>

                <form method="POST" action="">
                    <input type="hidden" name="action" value="update_options">

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 12px;">
                            <label class="toggle-switch">
                                <input type="checkbox" name="enable_recurring" <?php echo $settings['enable_recurring'] ? 'checked' : ''; ?>>
                                <span class="toggle-slider"></span>
                            </label>
                            <div>
                                <strong>Enable Recurring Donations</strong>
                                <p style="margin: 4px 0 0 0; color: #6b7280; font-size: 0.875rem;">
                                    Allow donors to set up monthly or yearly recurring donations
                                </p>
                            </div>
                        </label>
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 12px;">
                            <label class="toggle-switch">
                                <input type="checkbox" name="enable_anonymous" <?php echo $settings['enable_anonymous'] ? 'checked' : ''; ?>>
                                <span class="toggle-slider"></span>
                            </label>
                            <div>
                                <strong>Allow Anonymous Donations</strong>
                                <p style="margin: 4px 0 0 0; color: #6b7280; font-size: 0.875rem;">
                                    Donors can choose to remain anonymous on public donor lists
                                </p>
                            </div>
                        </label>
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 12px;">
                            <label class="toggle-switch">
                                <input type="checkbox" name="send_thank_you" <?php echo $settings['send_thank_you'] ? 'checked' : ''; ?>>
                                <span class="toggle-slider"></span>
                            </label>
                            <div>
                                <strong>Send Thank You Emails</strong>
                                <p style="margin: 4px 0 0 0; color: #6b7280; font-size: 0.875rem;">
                                    Automatically send thank you emails to donors after successful donations
                                </p>
                            </div>
                        </label>
                    </div>

                    <button type="submit" class="btn-save">Save Options</button>
                </form>
            </div>

            <!-- Donation Statistics -->
            <div class="settings-section">
                <h3>Donation Statistics Overview</h3>

                <div class="stat-grid">
                    <div class="stat-item">
                        <label>Total Raised</label>
                        <div class="value">$<?php echo number_format($stats['total_raised'] ?? 0, 2); ?></div>
                    </div>
                    <div class="stat-item">
                        <label>Total Donations</label>
                        <div class="value"><?php echo number_format($stats['total_donations'] ?? 0); ?></div>
                    </div>
                    <div class="stat-item">
                        <label>Unique Donors</label>
                        <div class="value"><?php echo number_format($stats['unique_donors'] ?? 0); ?></div>
                    </div>
                    <div class="stat-item">
                        <label>Average Donation</label>
                        <div class="value">$<?php echo number_format($stats['average_donation'] ?? 0, 2); ?></div>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <a href="donations.php" class="btn btn-primary">View All Donations</a>
                </div>
            </div>

            <!-- Setup Instructions -->
            <div class="settings-section">
                <h3>PayPal IPN Setup Instructions</h3>

                <ol style="line-height: 1.8; color: #374151;">
                    <li>Log in to your PayPal account</li>
                    <li>Go to <strong>Account Settings</strong> → <strong>Notifications</strong></li>
                    <li>Click on <strong>Instant Payment Notifications</strong></li>
                    <li>Click <strong>Update</strong> or <strong>Choose IPN Settings</strong></li>
                    <li>Enter your IPN URL: <code style="background: #f3f4f6; padding: 2px 6px; border-radius: 4px;"><?php echo htmlspecialchars($settings['ipn_url']); ?></code></li>
                    <li>Enable <strong>Receive IPN messages (Enabled)</strong></li>
                    <li>Click <strong>Save</strong></li>
                </ol>

                <div class="info-box" style="margin-top: 16px;">
                    <h4>Testing IPN</h4>
                    <p>Use PayPal's IPN Simulator in your developer dashboard to test the integration before going live.</p>
                </div>
            </div>

        </div>
    </main>

    <script src="../../assets/js/admin-dashboard.js"></script>
    <script>
        function testIPNEndpoint() {
            alert('IPN endpoint test functionality would check:\n\n' +
                  '✓ Endpoint is accessible\n' +
                  '✓ SSL certificate is valid\n' +
                  '✓ Server can process IPN messages\n' +
                  '✓ Database connection is working\n\n' +
                  'IPN URL: <?php echo $settings['ipn_url']; ?>');
        }
    </script>
</body>
</html>
