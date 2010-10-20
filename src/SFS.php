<?php
/**
 *
 *===================================================================
 *
 *  StopForumSpam integration library
 *-------------------------------------------------------------------
 * @package     sfsintegration
 * @author      Damian Bushong
 * @copyright   (c) 2010 Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/SFSIntegration
 *
 *===================================================================
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 */

/**
 * SFS Integration - Primary interface class,
 *      Provides quick access to common tasks performed with the library.
 *
 *
 * @package     sfsintegration
 * @author      Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/SFSIntegration
 */
class SFS
{
	/**
	 * @const string - The version stamp for the library.
	 */
	const VERSION = '0.1.0';

	/**
	 * @var integer - The timeout (in seconds) to use when submitting a request to StopForumSpam.
	 */
	protected $request_timeout = 2;

	/**
	 * @var integer - The time to store SFS data for, in seconds.  Default is 21600 seconds (or 6 hours).
	 */
	protected $cache_ttl = 21600;

	/**
	 * Constructor
	 * @return void
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		// If the "Of" class hasn't been loaded, let's load the basics and set the autoloader.
		if(!class_exists('Of', false))
		{
			require OF_ROOT . 'Of.php';
			require OF_ROOT . 'OfException.php';

			// Register the OpenFlame Framework autoloader
			spl_autoload_register('Of::loader');
		}

		// If our cache object isn't present, we want to instantiate it.
		if(is_null(Of::getObject('cache')))
		{
			try
			{
				Of::storeObject('cache', new OfCache('JSON', dirname(__FILE__) . '/../data/cache'));
			}
			catch(OfException $e)
			{
				throw new Exception(sprintf('Failed to initialize cache object - "%1$s"', $e->getMessage()));
			}
		}
	}

	/**
	 * Request a database check from the StopForumSpam service
	 * @param string $username - The username to check
	 * @param string $email - The email to check
	 * @param string $ip - The IP to check
	 * @return SFSResult - The result data from StopForumSpam.
	 *
	 * @note use try/catch around this method as methods called from this method will throw an exception on error
	 */
	public function requestCheck($username = '', $email = '', $ip = '')
	{
		/* @var $cache OfCache */
		$cache = Of::obj('cache');

		// If no items to check were provided, then why are we running this method?
		if(!$username && !$email && !$ip)
			return false;

		// Check to see if we have the result data cached already...
		$cache_data = $cache->loadData(hash('md5', "$username/$email/$ip"));

		if(!is_null($cache_data))
		{
			$requested_data = array('username' => $username, 'email' => $email, 'ip' => $ip);
			return new SFSResult($this, $cache_data, $requested_data);
		}
		else
		{
			// Create a new SFSRequest object, and arm it with the details we are looking for
			$request = new SFSRequest($this);
			$result = $request
				->setUsername($username)
				->setEmail($email)
				->setIP($ip)
				->send();

			// Store the data from StopForumSpam in the cache for now.
			$cache->storeData(hash('md5', "$username/$email/$ip"), $result->toArray(), $this->cache_ttl);

			return $result;
		}
	}

	/**
	 * Autoloader method
	 * @param string $class - The class to load up
	 * @return boolean - True if load successful, false if we could not find the file to load
	 *
	 * @throws Exception
	 *
	 * @note - We use the class Exception here instead of SFSException to make the class "SFS" standalone
	 */
	public static function loader($class)
	{
		if(file_exists(SFSLIB . basename($class) . '.php'))
		{
			require SFSLIB . basename($class) . '.php';
			if(!class_exists($class, false))
				throw new Exception(sprintf('SFS Integration Library Autoloader failed to load correct class file for class "%1$s"', $class));
			return true;
		}
		return false;
	}

	/**
	 * Get the stream timeout setting.
	 * @return integer - The current stream timeout, in seconds.
	 */
	public function getRequestTimeout()
	{
		return (int) $this->request_timeout;
	}

	/**
	 * Set the current stream timeout.
	 * @param integer $timeout - The stream timeout to set, in seconds.
	 * @return SFS - Provides a fluent interface.
	 */
	public function setRequestTimeout($timeout)
	{
		$this->request_timeout = (int) $timeout;
		return $this;
	}

	/**
	 * Get the cache TTL for requesting data
	 * @return integer - The cache TTL for request data, in seconds.
	 */
	public function getCacheTTL()
	{
		return (int) $this->cache_ttl;
	}

	/**
	 * Set the cache TTL for requesting data
	 * @param integer $ttl - The cache TTL to set, in seconds
	 * @return SFS - Provides a fluent interface.
	 */
	public function setCacheTTL($ttl)
	{
		$this->cache_ttl = (int) $ttl;
		return $this;
	}
}
