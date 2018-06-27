<?php function paypalexpresscheckout_admin_menu() { ?>

  <div class="wrap">
    <h2>PayPal ExpressCheckout Settings Page</h2>
    <form method="post" action="options.php">
      <?php settings_fields( 'paypal-settings-group' ); ?>
      <?php do_settings_sections( 'paypal-settings-group' ); ?>
      <table class="form-table">
        <tr>
          <th scope="row">Develop Enviroment</th>
          <td>
            <p>
              <select name="env" size="1">
                <option value="sandbox" <?php selected( get_option( 'env' ), 'sandbox' ); ?>>sandbox</option>
                <option value="production" <?php selected( get_option( 'env' ), 'production' ); ?>>production</option>
              </select>
            </p>
          </td>
        </tr>
        <tr valign="top">
        <th scope="row">Client ID</th>
        <td><input type="text" name="client" size="90" value="<?php echo esc_attr( get_option('client') ); ?>" /></td>
        </tr>
      </table>
      <?php submit_button(); ?>
    </form>
  </div>
<?php } ?>
