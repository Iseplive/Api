<?php
/**
 * General configuration
 */

final class Config extends ConfigAbstract {

	// Absolute URL of the website
	const URL_API        = 'http://rest.iseplive.localhost/';
	// Absolute URL of the storage dir
	const URL_STORAGE         = 'http://storage.iseplive.localhost/';
	// Absolute path of the website on the domain
	const URL_ROOT            = '/';
	// Absolute path for static files
	const URL_STATIC          = '/static/';

	// Timezone
	const TIMEZONE        	  = 'Europe/Paris';

	// DB connection
	public static $DB         = array(
			'driver'          => 'mysql',
			'dsn'             => 'host=localhost;dbname=iseplive',
			'username'        => 'root',
			'password'        => ''
	);

	// LDAP
	public static $LDAP = array (
			'host'                => 'ldap.isep.fr',
			'port'                => 636,
			'basedn'              => 'ou=People,dc=isep.fr'
	);

	// Authentication mode: 'ldap' (ISEP's LDAP) or 'form' (using https://gcma.isep.fr/ form over https)
	const AUTHENTICATION_MODE   = 'ldap';

	// Encryption secret key (for Encryption class)
	const ENCRYPTION_KEY        = 'XrDy2H8Ob8';
	
	// Directories
	// relative to "app" dir
	const DIR_APP_STATIC        = 'static/';         // Fichiers statics
	// relative to "data" dir
	const DIR_DATA_LOGS         = 'logs/';           // Logs²
	const DIR_DATA_STORAGE      = 'storage/';        // Storage
	const DIR_DATA_TMP          = 'tmp/';            // Temporary files
	const DIR_DATA_ADMIN        = 'admin/';         // Admin functions files
	
	// Name of the session
	const SESS_ID               = 'PHPSESSID';

	// Cache
	public static $CACHE        = array(
			//'driver'        => 'memcache',
			'prefix'        => 'iseplive-'
	);
	
	// Contact name and mail
	const CONTACT_NAME	= 'ISEPLive';
	const CONTACT_MAIL	= 'contact@iseplive.fr';

	// SMTP server
	const SMTP_HOST		= 'smtp.iseplive.fr';

	// Google Analytics tracker ID
	const GA_TRACKER	= 'UA-2659605-1';

	// Languages
	public static $LOCALES = array("fr_FR");
	
	// Debug mode
	const DEBUG			= true;

}