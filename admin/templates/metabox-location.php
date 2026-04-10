<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<table class="form-table" role="presentation">
    <tr>
        <th><label>Company Name</label></th>
        <td><input class="regular-text" type="text" name="rw_bs_loc[company_name]" value="<?php echo esc_attr($meta['company_name']); ?>" /></td>
    </tr>
    <tr>
        <th><label>Business Type</label></th>
        <td>
            <select class="rw-bs-type-select" name="rw_bs_loc[business_type]">
                <?php foreach ($types as $tk => $td) : ?>
                    <option value="<?php echo esc_attr($tk); ?>" <?php selected($meta['business_type'], $tk); ?>><?php echo esc_html($td['label']); ?></option>
                    <?php if (!empty($td['subtypes']) && is_array($td['subtypes'])) : ?>
                        <optgroup label="<?php echo esc_attr($td['label'] . ' Subtypes'); ?>">
                            <?php foreach ($td['subtypes'] as $stk => $std) : ?>
                                <option value="<?php echo esc_attr($stk); ?>" <?php selected($meta['business_type'], $stk); ?>><?php echo esc_html($std['label']); ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <p class="description rw-bs-type-description"></p>
        </td>
    </tr>
    <tr>
        <th>Address</th>
        <td>
            <p><input class="regular-text" type="text" name="rw_bs_loc[street]" value="<?php echo esc_attr($meta['street']); ?>" placeholder="Street" /></p>
            <p><input class="regular-text" type="text" name="rw_bs_loc[city]" value="<?php echo esc_attr($meta['city']); ?>" placeholder="City" /></p>
            <p><input class="regular-text" type="text" name="rw_bs_loc[state]" value="<?php echo esc_attr($meta['state']); ?>" placeholder="State" /></p>
            <p><input class="regular-text" type="text" name="rw_bs_loc[postal_code]" value="<?php echo esc_attr($meta['postal_code']); ?>" placeholder="Postal Code" /></p>
            <p><input class="regular-text" type="text" name="rw_bs_loc[country]" value="<?php echo esc_attr($meta['country']); ?>" placeholder="Country" /></p>
        </td>
    </tr>
    <tr>
        <th>Phone</th>
        <td>
            <p><input class="regular-text" type="text" name="rw_bs_loc[phone_primary]" value="<?php echo esc_attr($meta['phone_primary']); ?>" placeholder="Primary Phone" /></p>
            <p><input class="regular-text" type="text" name="rw_bs_loc[phone_secondary]" value="<?php echo esc_attr($meta['phone_secondary']); ?>" placeholder="Secondary Phone" /></p>
        </td>
    </tr>
    <tr>
        <th>Email</th>
        <td>
            <p><input class="regular-text" type="email" name="rw_bs_loc[email_primary]" value="<?php echo esc_attr($meta['email_primary']); ?>" placeholder="Primary Email" /></p>
            <p><input class="regular-text" type="email" name="rw_bs_loc[email_secondary]" value="<?php echo esc_attr($meta['email_secondary']); ?>" placeholder="Secondary Email" /></p>
        </td>
    </tr>
    <tr>
        <th><label>Website URL</label></th>
        <td><input class="regular-text" type="url" name="rw_bs_loc[website]" value="<?php echo esc_attr($meta['website']); ?>" /></td>
    </tr>
    <tr>
        <th><label>Price Range</label></th>
        <td>
            <select name="rw_bs_loc[price_range]">
                <option value="" <?php selected($meta['price_range'], ''); ?>>Select price range</option>
                <option value="$" <?php selected($meta['price_range'], '$'); ?>>$</option>
                <option value="$$" <?php selected($meta['price_range'], '$$'); ?>>$$</option>
                <option value="$$$" <?php selected($meta['price_range'], '$$$'); ?>>$$$</option>
                <option value="$$$$" <?php selected($meta['price_range'], '$$$$'); ?>>$$$$</option>
            </select>
        </td>
    </tr>
    <tr>
        <th><label for="rw_bs_loc_schema_target_page_id">Schema Output Target <span class="required" aria-hidden="true" style="color:#d63638;">*</span></label></th>
        <td class="rw-bs-target-wrap">
            <?php
            wp_dropdown_pages(array(
                'name'              => 'rw_bs_loc[schema_target_page_id]',
                'id'                => 'rw_bs_loc_schema_target_page_id',
                'class'             => 'rw-bs-target-page',
                'show_option_none'  => '— Select a page —',
                'option_none_value' => '0',
                'selected'          => (int) ($target['page_id'] ?? 0),
            ));
            ?>
            <p>
                <input class="regular-text rw-bs-target-path" type="text" name="rw_bs_loc[schema_target_path]" value="<?php echo esc_attr($target['path'] ?? ''); ?>" placeholder="/locations/downtown/" aria-required="true" />
            </p>
            <p class="description">Use the page selector when available. If the page is not available, enter a path fallback (for example, <code>/locations/downtown/</code>). <strong>At least one is required.</strong></p>
        </td>
    </tr>
</table>

<h3>Business Type Fields</h3>
<?php foreach ($groups as $group_key => $fields) : ?>
    <div class="rw-bs-type-group" data-group="<?php echo esc_attr($group_key); ?>">
        <?php foreach ($fields as $field_key => $label) : ?>
            <p>
                <label>
                    <?php echo esc_html($label); ?>&nbsp;
                    <?php if (in_array($field_key, array('petsAllowed', 'acceptsReservations'), true)) : ?>
                        <input type="checkbox" name="rw_bs_loc[type_fields][<?php echo esc_attr($field_key); ?>]" value="1" <?php checked(!empty($type_fields[$field_key])); ?> />
                    <?php else : ?>
                        <input class="regular-text" type="text" name="rw_bs_loc[type_fields][<?php echo esc_attr($field_key); ?>]" value="<?php echo esc_attr($type_fields[$field_key] ?? ''); ?>" />
                    <?php endif; ?>
                </label>
            </p>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

<h3>Schema Hours (By Day)</h3>
<table class="widefat rw-bs-hours-table">
    <thead>
        <tr><th>Day</th><th>Closed</th><th>Open</th><th>Close</th></tr>
    </thead>
    <tbody>
        <?php foreach ($meta['hours'] as $index => $row) : ?>
            <tr>
                <td><input type="text" name="rw_bs_loc[hours][<?php echo esc_attr($index); ?>][day]" value="<?php echo esc_attr($row['day']); ?>" /></td>
                <td><input type="checkbox" name="rw_bs_loc[hours][<?php echo esc_attr($index); ?>][closed]" value="1" <?php checked(!empty($row['closed'])); ?> /></td>
                <td><input type="time" name="rw_bs_loc[hours][<?php echo esc_attr($index); ?>][open]" value="<?php echo esc_attr($row['open']); ?>" /></td>
                <td><input type="time" name="rw_bs_loc[hours][<?php echo esc_attr($index); ?>][close]" value="<?php echo esc_attr($row['close']); ?>" /></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3>Display Hours (Custom Text)</h3>
<p><textarea class="large-text" rows="3" name="rw_bs_loc[hours_display]"><?php echo esc_textarea($meta['hours_display']); ?></textarea></p>

<h3>Aggregate Rating</h3>
<p>
    <label>Rating Value&nbsp;<input type="number" min="1" max="5" step="0.1" name="rw_bs_loc[rating_value]" value="<?php echo esc_attr($meta['rating_value']); ?>" /></label>&nbsp;&nbsp;
    <label>Review Count&nbsp;<input type="number" min="0" step="1" name="rw_bs_loc[rating_count]" value="<?php echo esc_attr($meta['rating_count']); ?>" /></label>
</p>
