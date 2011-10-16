<?php
/**
 *
 *===================================================================
 *
 *  StopForumSpam integration library
 *-------------------------------------------------------------------
 * @package     sfslib
 * @author      emberlabs.org
 * @copyright   (c) 2010 - 2011 Damian Bushong
 * @license     MIT License
 * @link        https://github.com/emberlabs/sfslib
 *
 *===================================================================
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 */

namespace emberlabs\sfslib;

/**
 * StopForumSpam Integration - Manager object
 * 	     Provides quick and easy access to the library's functionality.
 *
 * @package     sfsintegration
 * @author      emberlabs.org
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/emberlabs/sfslib
 */
class Core extends \OpenFlame\Framework\Core
{
	const LIB_VERSION = '0.5.0-dev';

	protected static $instance;

	protected $timeout = 30;

	protected $transmitter;

	final public static function getInstance()
	{
		if(!static::$instance)
		{
			static::$instance = new static();
		}

		return static::$instance;
	}

	final protected function __construct()
	{
		// set default options...
		$defaults = array(
			'sfs.timeout'				=> 30,
			'sfs.transmitter'			=> 'file',
			'sfs.transmitterclass.file'	=> '\\emberlabs\\sfslib\\Transmitter\\File',
			'sfs.transmitterclass.curl'	=> '\\emberlabs\\sfslib\\Transmitter\\cURL',
		);

		foreach($configs as $name => $config)
		{
			if(self::getConfig($name) === NULL)
			{
				self::setConfig($name, $config);
			}
		}

	}

	public function declareTransmitter()
	{
		// asdf
	}

	public function getTransmitter()
	{
		// asdf
	}

	public function getUserAgent()
	{
		return sprintf('emberlabs.sfslib:%1$s;PHP:%2$s', self::LIB_VERSION, PHP_VERSION);
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
