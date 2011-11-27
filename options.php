<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php echo __( 'Google Reader', 'google-reader' );?></h2>
	<form method="post" action="options.php">
		<?php wp_nonce_field('update-options'); ?>
		<!-- Google Account -->
		<h3><?php echo __('Your Google Account', 'google-reader' ); ?></h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php echo __('Your Google Login', 'google-reader' ); ?></th>
				<td><input type="text" name="googlereaderlogin" value="<?php echo get_option('googlereaderlogin'); ?>" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo __('Your Google Password', 'google-reader' ); ?></th>
				<td><input type="password" name="googlereaderpassword" value="<?php echo get_option('googlereaderpassword'); ?>" /></td>
			</tr>
		</table>
		<!-- Cache Management -->
		<h3><?php echo __('Manage Cache', 'google-reader' ); ?></h3>
		<table class="form-table">
			<tr valign="top">
				<th scope="row"><?php echo __('Cache Directory', 'google-reader' ); ?></th>
				<td>
					<input type="text" name="googlereadercachedir" value="<?php echo get_option('googlereadercachedir'); ?>" />
					<span class="description"><?php echo __('Remember : The cache directory must be writable ! ');?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo __('Cache Lifetime', 'google-reader' ); ?></th>
				<td>
					<input type="text" name="googlereadercachelifetime" value="<?php echo get_option('googlereadercachelifetime'); ?>" />
					<span class="description">
						<?php echo __('Integer : the number of seconds cache is not updated, 7200 means 2 hours');?><br />
					</span>
				</td>
			</tr>
		</table>
		<?php settings_fields( 'google-reader' ); ?>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>