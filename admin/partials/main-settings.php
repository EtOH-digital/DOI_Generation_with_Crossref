<?php
$options = get_option('doi_creator_options', []);
?>
<div class="wrap">
    <form action="options.php" method="post" id="doi-main-settings">
        <?php
        settings_fields( 'doi-creator-settings' );
        do_settings_sections( 'doi-creator-settings' );
        ?>
        <h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <a class="page-title-action" href="<?php echo admin_url('options-general.php?page=doi-creator&tab=logs'); ?>">View Submission Logs</a>
        <table class="form-table widefat">
            <tbody>

            <tr>
                <td><label for="deposit_user_name"><?php _e('Deposit Username'); ?></label></td>
                <td colspan="2"><input type="text" name="doi_creator_options[deposit_user_name]" placeholder="Deposit Username" id="deposit_user_name" value="<?php echo isset($options['deposit_user_name']) ? esc_attr($options['deposit_user_name']) : ''; ?>"></td>
            </tr>

            <tr>
                <td><label for="deposit_password"><?php _e('Deposit Password'); ?></label></td>
                <td colspan="2"><input type="password" name="doi_creator_options[deposit_password]" id="deposit_password" value="<?php echo isset($options['deposit_password']) ? esc_attr($options['deposit_password']) : ''; ?>"></td>
            </tr>

            <tr>
                <td><label for="issn"><?php _e('ISSN'); ?></label></td>
                <td colspan="2"><input type="text" name="doi_creator_options[issn]" placeholder="ISSN" id="issn" value="<?php echo isset($options['issn']) ? esc_attr($options['issn']) : ''; ?>"></td>
            </tr>


            <tr>
                <td><label for="login_id"><?php _e('Test Username'); ?></label></td>
                <td colspan="2"><input type="text" name="doi_creator_options[login_id]" id="login_id" value="<?php echo isset($options['login_id']) ? esc_attr($options['login_id']) : ''; ?>"></td>
            </tr>
            <tr>
                <td><label for="api_password"><?php _e('Test Password'); ?></label></td>
                <td colspan="2"><input type="password" name="doi_creator_options[api_password]" id="api_password" value="<?php echo isset($options['api_password']) ? esc_attr($options['api_password']) : ''; ?>"></td>
            </tr>
            <tr>
                <td><label for="deposit_endpoint"><?php _e('Deposit Endpoint URL'); ?></label></td>
                <td colspan="2"><input type="text" name="doi_creator_options[deposit_endpoint]" id="deposit_endpoint" value="<?php echo isset($options['deposit_endpoint']) ? esc_attr($options['deposit_endpoint']) : ''; ?>"></td>
            </tr>
            <tr>
                <td><label for="test_endpoint"><?php _e('Test Endpoint URL'); ?></label></td>
                <td colspan="2"><input type="text" name="doi_creator_options[test_endpoint]" id="test_endpoint" value="<?php echo isset($options['test_endpoint']) ? esc_attr($options['test_endpoint']) : ''; ?>"></td>
            </tr>
            <tr>
                <td><label for="doi_prefix"><?php _e('DOI Prefix'); ?></label></td>
                <td colspan="2"><input type="text" name="doi_creator_options[doi_prefix]" id="doi_prefix" value="<?php echo isset($options['doi_prefix']) ? esc_attr($options['doi_prefix']) : ''; ?>"></td>
            </tr>
            <tr>
                <td><label><?php _e('Enable Mode'); ?></label></td>
                <td>
                    <label class="api_env">
                        <input type="radio" name="doi_creator_options[api_env]" value="test" <?php echo ((isset($options['api_env']) && $options['api_env'] == 'test') || ! isset($options['api_env'])) ? 'checked' : ''; ?>>
                        <?php _e('Test'); ?>
                    </label>
                    <label class="api_env">
                        <input type="radio" name="doi_creator_options[api_env]" value="live" <?php echo (isset($options['api_env']) && $options['api_env'] == 'live') ? 'checked' : ''; ?>>
                        <?php _e('Live'); ?>
                    </label>
                </td>
            </tr>
            <tr>
                <td><label for="auto_submit"><?php _e('Enable Automatic Submission'); ?></label></td>
                <td colspan="2"><input type="checkbox" name="doi_creator_options[auto_submit]" id="auto_submit" value="yes" <?php echo (isset($options['auto_submit']) && $options['auto_submit'] == 'yes') ? 'checked' : ''; ?> ></td>
            </tr>
            <tr>
                <td><label for="email"><?php _e('Error Notification Email'); ?></label></td>
                <td colspan="2"><input type="email" name="doi_creator_options[email]" id="email" value="<?php echo isset($options['email']) ? esc_attr($options['email']) : ''; ?>" placeholder="Enter your email address for error notifications" required></td>
            </tr>
            </tbody>
        </table>
        <?php submit_button( 'Save Settings' ); ?>
    </form>
</div>