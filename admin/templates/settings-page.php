<?php
if (!defined('ABSPATH')) {
    exit;
}

$type_fields = isset($settings['type_fields']) && is_array($settings['type_fields']) ? $settings['type_fields'] : array();
$token_data = get_option(RW_BS_GBP_TOKEN_KEY, array());
$connected = !empty($token_data['refresh_token']) || !empty($token_data['access_token']);
?>
<div class="wrap rw-bs-admin-wrap">
    <h1>RW Business Schema</h1>

    <div class="rw-bs-help-links">
        <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=rw-bs-gbp-help')); ?>">Open GBP Setup Guide</a>
        <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=rw-bs-shortcodes-help')); ?>">Open Shortcodes &amp; Dynamic Tags Guide</a>
    </div>

    <?php if (!empty($_GET['rw_bs_notice'])) : ?>
        <div class="notice notice-success is-dismissible"><p><?php echo esc_html(sanitize_text_field(wp_unslash($_GET['rw_bs_notice']))); ?></p></div>
    <?php endif; ?>

    <div class="rw-bs-wizard-banner notice notice-info">
        <h2>Welcome to RW Business Schema</h2>
        <p>Follow these five steps to publish your business schema to search engines:</p>
        <ol>
            <li>Choose your <strong>Business Type</strong> from the dropdown below</li>
            <li>Fill in <strong>Company Name</strong>, <strong>Address</strong>, <strong>Phone</strong>, and <strong>Email</strong></li>
            <li>Set your <strong>Schema Hours</strong> for each day of the week</li>
            <li>Click <strong>Save Settings</strong> &mdash; your <code>LocalBusiness</code> JSON-LD is now live in every page&rsquo;s <code>&lt;head&gt;</code></li>
            <li>Optionally, connect <strong>Google Business Profile</strong> to auto-sync your rating and review count</li>
        </ol>
    </div>

    <form method="post" action="options.php">
        <?php settings_fields('rw_bs_group'); ?>

        <table class="form-table" role="presentation">
            <tr>
                <th scope="row"><label for="rw_bs_company_name">Company Name</label></th>
                <td><input id="rw_bs_company_name" class="regular-text" type="text" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[company_name]" value="<?php echo esc_attr($settings['company_name']); ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="rw_bs_business_type">Business Type</label></th>
                <td>
                    <select id="rw_bs_business_type" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[business_type]">
                        <?php foreach ($types as $type_key => $type_data) : ?>
                            <option value="<?php echo esc_attr($type_key); ?>" <?php selected($settings['business_type'], $type_key); ?>><?php echo esc_html($type_data['label']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">Address</th>
                <td>
                    <p><input class="regular-text" type="text" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[street]" value="<?php echo esc_attr($settings['street']); ?>" placeholder="Street" /></p>
                    <p><input class="regular-text" type="text" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[city]" value="<?php echo esc_attr($settings['city']); ?>" placeholder="City" /></p>
                    <p><input class="regular-text" type="text" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[state]" value="<?php echo esc_attr($settings['state']); ?>" placeholder="State" /></p>
                    <p><input class="regular-text" type="text" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[postal_code]" value="<?php echo esc_attr($settings['postal_code']); ?>" placeholder="Postal Code" /></p>
                    <p><input class="regular-text" type="text" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[country]" value="<?php echo esc_attr($settings['country']); ?>" placeholder="Country" /></p>
                </td>
            </tr>
            <tr>
                <th scope="row">Phone</th>
                <td>
                    <p><input class="regular-text" type="text" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[phone_primary]" value="<?php echo esc_attr($settings['phone_primary']); ?>" placeholder="Primary Phone" /></p>
                    <p><input class="regular-text" type="text" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[phone_secondary]" value="<?php echo esc_attr($settings['phone_secondary']); ?>" placeholder="Secondary Phone" /></p>
                </td>
            </tr>
            <tr>
                <th scope="row">Email</th>
                <td>
                    <p><input class="regular-text" type="email" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[email_primary]" value="<?php echo esc_attr($settings['email_primary']); ?>" placeholder="Primary Email" /></p>
                    <p><input class="regular-text" type="email" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[email_secondary]" value="<?php echo esc_attr($settings['email_secondary']); ?>" placeholder="Secondary Email" /></p>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="rw_bs_website">Website URL</label></th>
                <td><input id="rw_bs_website" class="regular-text" type="url" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[website]" value="<?php echo esc_attr($settings['website']); ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="rw_bs_price_range">Price Range</label></th>
                <td>
                    <select id="rw_bs_price_range" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[price_range]">
                        <option value="" <?php selected($settings['price_range'], ''); ?>>Select price range</option>
                        <option value="$" <?php selected($settings['price_range'], '$'); ?>>$</option>
                        <option value="$$" <?php selected($settings['price_range'], '$$'); ?>>$$</option>
                        <option value="$$$" <?php selected($settings['price_range'], '$$$'); ?>>$$$</option>
                        <option value="$$$$" <?php selected($settings['price_range'], '$$$$'); ?>>$$$$</option>
                    </select>
                </td>
            </tr>
        </table>

        <h2>Business Type Fields</h2>
        <?php foreach ($groups as $group_key => $fields) : ?>
            <div class="rw-bs-type-group" data-group="<?php echo esc_attr($group_key); ?>">
                <?php foreach ($fields as $field_key => $label) : ?>
                    <p>
                        <label>
                            <?php echo esc_html($label); ?>
                            <?php if (in_array($field_key, array('petsAllowed', 'acceptsReservations'), true)) : ?>
                                <input type="checkbox" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[type_fields][<?php echo esc_attr($field_key); ?>]" value="1" <?php checked(!empty($type_fields[$field_key])); ?> />
                            <?php else : ?>
                                <input class="regular-text" type="text" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[type_fields][<?php echo esc_attr($field_key); ?>]" value="<?php echo esc_attr($type_fields[$field_key] ?? ''); ?>" />
                            <?php endif; ?>
                        </label>
                    </p>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <h2>Schema Hours (By Day)</h2>
        <table class="widefat rw-bs-hours-table">
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Closed</th>
                    <th>Open</th>
                    <th>Close</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($settings['hours'] as $index => $row) : ?>
                    <tr>
                        <td>
                            <input type="text" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[hours][<?php echo esc_attr($index); ?>][day]" value="<?php echo esc_attr($row['day']); ?>" />
                        </td>
                        <td>
                            <input type="checkbox" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[hours][<?php echo esc_attr($index); ?>][closed]" value="1" <?php checked(!empty($row['closed'])); ?> />
                        </td>
                        <td>
                            <input type="time" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[hours][<?php echo esc_attr($index); ?>][open]" value="<?php echo esc_attr($row['open']); ?>" />
                        </td>
                        <td>
                            <input type="time" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[hours][<?php echo esc_attr($index); ?>][close]" value="<?php echo esc_attr($row['close']); ?>" />
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Display Hours (Custom Text)</h2>
        <p>
            <textarea class="large-text" rows="4" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[hours_display]"><?php echo esc_textarea($settings['hours_display']); ?></textarea>
        </p>

        <h2>Aggregate Rating</h2>
        <p>
            <label>Rating Value <input type="number" min="1" max="5" step="0.1" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[rating_value]" value="<?php echo esc_attr($settings['rating_value']); ?>" /></label>
            <label>Review Count <input type="number" min="0" step="1" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[rating_count]" value="<?php echo esc_attr($settings['rating_count']); ?>" /></label>
        </p>

        <h2>Multi-Location</h2>
        <p>
            <label>
                <input type="checkbox" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[multi_location]" value="1" <?php checked(!empty($settings['multi_location'])); ?> />
                Enable Multi-Location Mode
            </label>
            <span class="description"> &mdash; Adds a <strong>Business Locations</strong> post type to the admin menu. Each published location gets its own schema block in the page <code>&lt;head&gt;</code>.</span>
        </p>

        <table class="form-table" role="presentation">
            <tr>
                <th scope="row">Client ID</th>
                <td><input class="regular-text" type="text" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[gbp_client_id]" value="<?php echo esc_attr($settings['gbp_client_id']); ?>" /></td>
            </tr>
            <tr>
                <th scope="row">Client Secret</th>
                <td><input class="regular-text" type="text" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[gbp_client_secret]" value="<?php echo esc_attr($settings['gbp_client_secret']); ?>" /></td>
            </tr>
            <tr>
                <th scope="row">Location Resource Name</th>
                <td>
                    <input class="regular-text" type="text" name="<?php echo esc_attr(RW_BS_OPTION_KEY); ?>[gbp_location]" value="<?php echo esc_attr($settings['gbp_location']); ?>" placeholder="accounts/1234567890/locations/9876543210" />
                    <p class="description">Format: <code>accounts/{accountId}/locations/{locationId}</code> &mdash; both IDs are numeric. See Step 5 above.</p>
                </td>
            </tr>
        </table>

        <?php submit_button('Save Settings'); ?>
    </form>

    <div class="rw-bs-actions">
        <h2>Google Sync Actions</h2>
        <p>Status: <strong><?php echo $connected ? 'Connected' : 'Not Connected'; ?></strong></p>
        <p>Last Sync: <strong><?php echo !empty($settings['gbp_last_sync']) ? esc_html($settings['gbp_last_sync']) : 'Never'; ?></strong></p>

        <p>
            <a class="button button-primary" href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=rw_bs_gbp_connect'), 'rw_bs_gbp_connect')); ?>">Connect Google Business Profile</a>
            <a class="button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=rw_bs_gbp_sync'), 'rw_bs_gbp_sync')); ?>">Sync Now</a>
            <a class="button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=rw_bs_gbp_disconnect'), 'rw_bs_gbp_disconnect')); ?>">Disconnect</a>
        </p>
    </div>

</div>
