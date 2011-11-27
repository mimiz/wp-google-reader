<?php
//require_once(ABSPATH . 'wp-admin/admin.php');
class GoogleReaderSettingsValidator
{
	
	const NOT_EMPTY = 0;
	const NOT_A_DIRECTORY = 1;
	const DIRECTORY_NOT_WRITABLE = 2;
	const NOT_INTEGER = 3;

	
	public static function login($input)
	{
		self::_isEmpty($input, 'googlereaderpassword');
		$value = sanitize_email($input);
		if ( !is_email($value) ) {
			$value = get_option( 'googlereaderlogin' ); // Resets option to stored value in the case of failed sanitization
			if ( function_exists('add_settings_error') )
				add_settings_error('googlereaderlogin', 'invalid_google_reader_login', __('Your Google Login must be a valid email address. Please enter a valid email address.'));
		}
		
		return $value;
	}
	
	public static function password($input)
	{
		self::_isEmpty($input, 'googlereaderpassword');
		return $input;
	}
	
	public static function cachelifetime($input)
	{
		if($input == '')
		{
			return $input;
		}
		if(preg_match('/^[0-9]{1,}$/', $input) == 0)
		{
			self::_setError('googlereadercachelifetime', $input, self::NOT_INTEGER);
		}
		return $input;
	}
	
	public static function cachedir($input)
	{
		if($input == '')
		{
			return $input;
		}
		if(is_dir($input))
		{
			if(!is_writable($input)){
				self::_setError('googlereadercachedir', $input ,self::DIRECTORY_NOT_WRITABLE);
			}
		}else{
			self::_setError('googlereadercachedir', $input, self::NOT_A_DIRECTORY);
		}
		return $input;
	}
	
	private static function _isEmpty($value, $field)
	{
		if(trim($value) == '')
		{
			self::_setError($field, $value ,self::NOT_EMPTY);
		}
		return true;
	}
	
	

	
	private static function _setError($field, $value, $message)
	{
		if ( function_exists('add_settings_error') )
		{
			switch($message)
			{
				case self::NOT_A_DIRECTORY :
					$message2 = sprintf(__('%s is not a valid directory', 'google-reader'), "'".$value."'");
					
					break;
				case self::NOT_EMPTY :
					if($field == 'googlereaderlogin'){$value="Your login";}
					if($field == 'googlereaderpassword'){$value="Your Password";}
					if($field == 'googlereadercachedir'){$value="Cache directory";}
					if($field == 'googlereadercachelifetime'){$value="Cache lifetime";}
					$message2 = sprintf(__('%s is required', 'google-reader'), $value);
					break;
				case self::DIRECTORY_NOT_WRITABLE :
					$message2 = sprintf(__('Directory %s is not writable, please set permission at least to 755', 'google-reader'), $value);
					break;
				case self::NOT_INTEGER :
					$message2 = sprintf(__('%s is not a valid integer', 'google-reader'), $value);
					break;
			}
			add_settings_error($field, 'invalid_google_reader_'.$field, $message2);
		}
		
	}
}