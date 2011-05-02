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
use \Codebite\StopForumSpam\Error\InternalException;

if(!defined('Codebite\\StopForumSpam\\ROOT_PATH')) exit;

/**
 * StopForumSpam Integration - Manager object
 * 	     Provides quick and easy access to the library's functionality.
 *
 * @package     sfsintegration
 * @author      OpenFlame Development Team, Damian Bushong
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/damianb/SFSIntegration
 *
 * @note        This class uses code taken from the OpenFlame Framework, which is licensed under
 *              the MIT License and available at https://github.com/OpenFlame/OpenFlame-Framework
 */
class Manager
{
	/**
	 * @var \Codebite\StopForumSpam\Cache\CacheInterface - A cache object that fulfills the cache interface.
	 */
	protected $cache;

	protected $timeout = 30;

	protected $transmitter;

	public function getCache()
	{
		return $this->cache;
	}

	public function setCache(\Codebite\StopForumSpam\Cache\CacheInterface $cache)
	{
		$this->cache = $cache;

		return $this;
	}

	public function getTimeout()
	{
		return $this->timeout;
	}

	public function setTimeout($timeout)
	{
		if($timeout < 0)
		{
			$timeout = 0;
		}

		$this->timeout = (int) $timeout;

		return $this;
	}

	public function getTransmitter()
	{
		// asdf
	}

	public function setTransmitter($transmission_method)
	{
		$class = '\\Codebite\\StopForumSpam\\Transmission\\Method\\' . strtolower($transmission_method);
		if(!class_exists($class))
		{
			throw new InternalException('Specified transmission method does not exist');
		}

		try
		{
			$transmitter = new $class();
			if(is_a($transmitter, '\\Codebite\\StopForumSpam\\Transmission\\Method\\MethodInterface'))
			{
				throw new InternalException('The specified trasmitter does not implement the required transmitter method interface.');
			}

			if($transmitter->checkRequirements())
			{
				throw new InternalException('Transmitter reports that the runtime environment does not meet its requirements');
			}

			$transmitter->setManager($this);
		}
		catch(\Exception $e)
		{
			throw new InternalException('Exception encountered during transmitter instantiation', 0, $e);
		}

		return $this;
	}

	public function getUserAgent()
	{
		$version = \Codebite\StopForumSpam\Core::getVersion();
		$commit = \Codebite\StopForumSpam\Core::getCommit();
		if($commit != '')
		{
			return sprintf('PHP-SFSIntegration:%1$s;phar:%2$s;PHP:%3$s', $version, $commit, PHP_VERSION);
		}
		else
		{
			return sprintf('PHP-SFSIntegration:%1$s;PHP:%2$s', $version, PHP_VERSION);
		}
	}

	public function newRequest($username, $email, $ip)
	{
		// asdf
	}

	public function newReport($username, $email, $ip)
	{
		// asdf
	}
}
