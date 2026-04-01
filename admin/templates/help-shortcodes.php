<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="wrap rw-bs-admin-wrap rw-bs-help-page">
    <h1>Shortcodes & Elementor Dynamic Tag Guide</h1>
    <p class="description">Quick reference for inserting business data anywhere on your site.</p>

    <div class="rw-bs-help-card rw-bs-shortcode-ref">
        <h2>Available Shortcodes</h2>
        <table class="widefat rw-bs-ref-table">
            <thead>
                <tr>
                    <th>Shortcode</th>
                    <th>What It Displays</th>
                    <th>Attributes</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>[rw_business_card]</code></td>
                    <td>Company card with address, phones, and emails.</td>
                    <td><em>None</em></td>
                </tr>
                <tr>
                    <td><code>[rw_business_hours]</code></td>
                    <td>Display hours text. Uses custom Display Hours first, otherwise auto-generated from schema hours.</td>
                    <td><em>None</em></td>
                </tr>
                <tr>
                    <td><code>[rw_business_field field="company_name"]</code></td>
                    <td>Single field value.</td>
                    <td><code>field</code> (required)</td>
                </tr>
                <tr>
                    <td><code>[rw_business_reviews count="5"]</code></td>
                    <td>Google review cards (requires GBP connected and synced).</td>
                    <td><code>count</code> (optional, 1-20)</td>
                </tr>
            </tbody>
        </table>

        <h2>Elementor Dynamic Tag Details</h2>
        <ol>
            <li>Edit any Elementor text-capable widget.</li>
            <li>Click the Dynamic Tags icon.</li>
            <li>Choose <strong>RW Business Field</strong>.</li>
            <li>In tag settings, choose the field you want.</li>
        </ol>

        <p><strong>Most used field:</strong> <code>Address (Full)</code> for a single-widget full address output.</p>
    </div>

    <p>
        <a class="button button-primary" href="<?php echo esc_url(admin_url('admin.php?page=rw-business-schema')); ?>">Back to Plugin Settings</a>
    </p>
</div>
