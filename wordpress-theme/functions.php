<?php
// functions.php
if (!defined('SITE_VERSION')) {
	/*
	 * Set the theme’s version number.
	 *
	 * This is used primarily for cache busting. If you use `npm run bundle`
	 * to create your production build, the value below will be replaced in the
	 * generated zip file with a timestamp, converted to base 36.
	 */
	define('SITE_VERSION', '0.1.0');
}
/**
* Load all required php files from '/required' directory 
*/
function require_all_files($dir)
{
	foreach (glob("$dir/*") as $path) {
		if (preg_match('/\.php$/', $path)) {
			require_once $path;  // it's a PHP file so just require it
		} elseif (is_dir($path)) {
			require_all_files($path);  // it's a subdir, so call the same function for this subdir
		}
	}
}

require_all_files(get_template_directory() . "/required");