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
	 * @var integer - The time to store SFS data for, in seconds.  Default is 21600 seconds (or 6 hours).
	 */
	protected $cache_ttl = 21600;

	/**
	 *
	 */
	public function requestCheck($username = '', $email = '', $ip = '')
	{
		/* @var $cache OfCache */
		$cache = Of::obj('cache');

		if(!$username && !$email && !$ip)
			return false;

		$cache_data = $cache->loadData(hash('md5', "$username/$email/$ip"));

		if(!is_null($cache_data))
		{
			return new SFSResult($cache_data);
		}
		else
		{
			$request = new SFSRequest();
			$result = $request->setUsername($username)->setEmail($email)->setIP($ip)->send();

			$cache->storeData(hash('md5', "$username/$email/$ip"), $result->toArray(), $this->cache_ttl);

			return $result;
		}
	}

	public function loader()
	{
		// asdf
	}
}
