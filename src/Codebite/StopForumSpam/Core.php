<?php
/**
 *
 *===================================================================
 *
 *  StopForumSpam integration library
 *-------------------------------------------------------------------
 * @package     sfsintegration
 * @author      Damian Bushong
 * @copyright   (c) 2010 - 2011 Damian Bushong
 * @license     MIT License
 * @link        https://github.com/damianb/SFSIntegration
 *
 *===================================================================
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 */

namespace Codebite\StopForumSpam;

if(!defined('Codebite\\StopForumSpam\\ROOT_PATH')) exit;

/**
 * StopForumSpam Integration - Main class
 * 	     Contains the objects that power the library.
 *
 * @package     sfsintegration
 * @author      OpenFlame Development Team, Damian Bushong
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/damianb/SFSIntegration
 *
 * @note        This class should not be instantiated; it should only be statically accessed.
 *
 * @note        This class uses code taken from the OpenFlame Framework, which is licensed under
 *              the MIT License and available at https://github.com/OpenFlame/OpenFlame-Framework
 */
class Core
{
	/**
	 * @var string - The version for the Framework
	 */
	private static $version = '0.4.0-dev';

	/**
	 * @var string - The commit ID for phar-packaged forms of the framework (considering "unstable" development builds)
	 */
	private static $commit;

	/**
	 * @var array - Array of settings we have loaded and stored
	 */
	protected static $config = array();

	/**
	 * @var array - Array of objects we have instantiated and stored
	 */
	protected static $objects = array();

	/**
	 * Initiates the library.
	 * @param array $config - Array of application-specific settings to store in the OpenFlame Framework core.
	 * @return void
	 */
	public static function init(array $config = NULL)
	{
		if($config !== NULL)
		{
			// Yay lambdas!
			array_walk($config, function($value, $key) {
				self::setConfig($key, $value);
			});
		}
	}

	/**
	 * Get the version string for the current instance of the library
	 * @return string - The framework's version.
	 */
	public static function getVersion()
	{
		return self::$version;
	}

	/**
	 * Get the commit ID (if there is one known) for this packaging of the framework
	 * @return string - The commit ID of the framework package, or an empty string if no commit ID could be determined
	 */
	public static function getCommit()
	{
		if(self::$commit === NULL)
		{
			self::$commit = file_exists(\Codebite\StopForumSpam\ROOT_PATH . '/COMMIT_ID') ? file_get_contents(\Codebite\StopForumSpam\ROOT_PATH) : '';
		}

		return self::$commit;
	}

	/**
	 * Set a configuration entry.
	 * @param string $config_name - The name of the configuration entry.
	 * @param mixed $config_value - The value to store in the configuration entry
	 * @return void
	 */
	public static function setConfig($config_name, $config_value)
	{
		// check to see if this is a namespaced config
		$config_name = explode('.', $config_name, 2);
		if(sizeof($config_name) > 1)
		{
			// it is namespaced, we need to store under said namespace
			self::$config["_{$config_name[0]}"][$config_name[1]] = $config_value;
		}
		else
		{
			// if no namespace was declared, we store it in the global namespace
			self::$config['global'][$config_name[0]] = $config_value;
		}
	}

	/**
	 * Get a specific configuration entry
	 * @param string $config_name - The name of the configuration entry to grab.
	 * @return mixed - The contents of the specified configuration entry.
	 */
	public static function getConfig($config_name)
	{
		// check to see if this is a namespaced config
		$config_name_array = explode('.', $config_name, 2);
		if(sizeof($config_name_array) > 1)
		{
			// it is namespaced, we need to grab from that specific namespace.
			if(!isset(self::$config["_{$config_name_array[0]}"][$config_name_array[1]]))
			{
				return NULL;
			}

			return self::$config["_{$config_name_array[0]}"][$config_name_array[1]];
		}
		else
		{
			// not namespaced, so we use the global namespace for this. :)
			if(!isset(self::$config['global'][$config_name]))
			{
				return NULL;
			}

			return self::$config['global'][$config_name];
		}
	}

	/**
	 * Get all configurations under a certain namespace.
	 * @param string $namespace - The namespace to retrieve (or an empty string, to retrieve the global config namespace contents)
	 * @return array - The array of configurations stored under the specified namespace.
	 */
	public static function getConfigNamespace($namespace)
	{
		// If an empty string is used as the namespace, we assume the global namespace.
		if($namespace === '')
		{
			if(!isset(self::$config['global']))
			{
				return NULL;
			}

			return self::$config['global'];
		}

		if(!isset(self::$config["_{$namespace}"]))
		{
			return NULL;
		}

		return self::$config["_{$namespace}"];
	}

	/**
	 * Store an object for easy global access.
	 * @param string $slot - The slot to store in.
	 * @param object $object - The object to store.
	 * @return void
	 */
	public static function setObject($slot, $object)
	{
		self::$objects[(string) $slot] = $object;

		return $object;
	}

	/**
	 * Grab a stored object.
	 * @param string $slot - The slot to grab from.
	 * @return mixed - NULL if no object in specified slot, or the desired object if the slot exists.
	 */
	public static function getObject($slot)
	{
		if(!isset(self::$objects[(string) $slot]))
		{
			return NULL;
		}

		return self::$objects[(string) $slot];
	}
}
