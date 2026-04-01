<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap rw-bs-admin-wrap rw-bs-help-page">
    <h1>Google Business Profile Setup Guide</h1>
    <p class="description">Use this page to connect GBP so rating and review count can auto-sync into schema.</p>

    <div class="rw-bs-help-card rw-bs-setup-box">
        <p><strong>This connection is optional.</strong> If connected, your Google rating and review count can be synced and inserted into your schema automatically.</p>

        <ol class="rw-bs-steps">
            <li>
                <strong>Create a Google Cloud Project</strong>
                <ol>
                    <li>Open <a href="https://console.cloud.google.com" target="_blank" rel="noopener noreferrer">Google Cloud Console</a>.</li>
                    <li>Create a new project for this website.</li>
                </ol>
            </li>
            <li>
                <strong>Enable APIs</strong>
                <ol>
                    <li>Enable <em>My Business Business Information API</em>.</li>
                    <li>Enable <em>My Business Account Management API</em>.</li>
                </ol>
            </li>
            <li>
                <strong>Configure OAuth Consent</strong>
                <ol>
                    <li>Create an External app.</li>
                    <li>Add scope: <code>https://www.googleapis.com/auth/business.manage</code>.</li>
                    <li>Add your Google account under Test users.</li>
                </ol>
            </li>
            <li>
                <strong>Create OAuth Client (Web Application)</strong>
                <ol>
                    <li>Go to Credentials and create an OAuth client ID.</li>
                    <li>Add this exact redirect URI:</li>
                    <li><code class="rw-bs-uri"><?php echo esc_html(admin_url('admin-post.php?action=rw_bs_gbp_callback')); ?></code></li>
                    <li>Copy Client ID and Client Secret.</li>
                </ol>
            </li>
            <li>
                <strong>Find and Paste Location Resource Name</strong>
                <ol>
                    <li>Format is exactly: <code>accounts/{accountId}/locations/{locationId}</code></li>
                    <li>Example: <code>accounts/1234567890/locations/9876543210</code></li>
                    <li>Paste it in the plugin settings, save, then click <em>Sync Now</em>.</li>
                </ol>
            </li>
        </ol>
    </div>

    <p>
        <a class="button button-primary" href="<?php echo esc_url(admin_url('admin.php?page=rw-business-schema')); ?>">Back to Plugin Settings</a>
    </p>
</div>
