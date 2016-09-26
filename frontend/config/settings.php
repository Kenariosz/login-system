<?php
/*
|--------------------------------------------------------------------------
| Template settings
|--------------------------------------------------------------------------
|
|   template_dir:       template directory name.
|
*/
$config['template_dir']     = 'default';
/*
|--------------------------------------------------------------------------
| Database settings
|--------------------------------------------------------------------------
|
|   db_prefix:          table prefix
|   db_config_type:     Admin or frontend config will be used.
|						config|config_admin
|   db_settings_table:  settings table name.
|
*/
$config['db_prefix']        = 'ke_';
$config['db_config_type']   = 'config';
$config['db_settings_table']= 'settings';

/*
|-------------------------------------------------------------------------
| Hash Method (sha1 or bcrypt)
|-------------------------------------------------------------------------
|
|   hash_method:        Hash method type: sha1 or bcrypt
|   default_rounds      Deafult rounds. This does not apply if random_rounds is set to true.
|   random_rounds       Is random: TRUE|FALSE
|   min_rounds
|   max_rounds
|   salt_prefix         Used for bcrypt. Versions of PHP before 5.3.7 only support "$2a$" as the salt prefix Versions 5.3.7 or greater should use the default of "$2y$".
|
*/
$config['hash_method']          = 'bcrypt';
$config['hash_default_rounds']  = 8;
$config['hash_random_rounds']   = FALSE;
$config['hash_min_rounds']      = 5;
$config['hash_max_rounds']      = 9;
$config['hash_salt_prefix']     = version_compare(PHP_VERSION, '5.3.7', '<') ? '$2a$' : '$2y$';
/*
|--------------------------------------------------------------------------
| Authentication settings
|--------------------------------------------------------------------------
*/
$config['auth_manual_activation']           = FALSE;
$config['auth_email_activation']            = TRUE;
$config['auth_remember_users']              = TRUE;
$config['auth_one_login_only']              = FALSE;
$config['auth_user_expire']                 = 86500;
$config['auth_remember_cookie']             = 'remember_code';
$config['auth_identity_cookie']             = 'identity';
$config['auth_track_login_attempts']        = TRUE;
$config['auth_max_login_attempts']          = 3;
$config['auth_max_login_attempts_ip_24']    = 500;
$config['auth_max_login_attempts_ip_16']    = 1000;
$config['auth_captcha_time']                = 3600;
/*
|-------------------------------------------------------------------------
| Salt options
|-------------------------------------------------------------------------
|
|   salt_length:        Default: 22
|   store_salt:         Should the salt be stored in the database?
*/
$config['salt_length']  = 22;
$config['store_salt']   = FALSE;

/*
|-------------------------------------------------------------------------
| Email options.
|-------------------------------------------------------------------------
*/
$config['email_no_replay']  = 'no-replay@vmi.hu';
$config['email_from_name']  = 'www.vmi.hu';
$config['email_config']     = array(
	'mailtype'  => 'html',
	);

/*
|-------------------------------------------------------------------------
| Email templates.
|-------------------------------------------------------------------------
*/
$config['email_templates']  = 'templates/email/';
$config['email_activate']   = 'activate.tpl.php';

/*
|-------------------------------------------------------------------------
| Message Delimiters.
|-------------------------------------------------------------------------
|
*/
$config['delimiters_source']        = 'config';
$config['message_start_delimiter']  = '';
$config['message_end_delimiter']    = '';
$config['error_start_delimiter']    = '';
$config['error_end_delimiter']      = '';