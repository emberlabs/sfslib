<?php
/**
 *
 *===================================================================
 *
 *  StopForumSpam integration library
 *-------------------------------------------------------------------
 * @package     sfslib
 * @author      emberlabs.org
 * @copyright   (c) 2010 - 2011 emberlabs.org
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
 * StopForumSpam integration library - Manager object
 * 	     Provides quick and easy access to the library's functionality.
 *
 * @package     sfslib
 * @author      emberlabs.org
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/emberlabs/sfslib
 */
abstract class Core extends \OpenFlame\Framework\Core
{
	const LIB_VERSION = '0.5.0-dev';

	protected static $transmitter;

	protected static $transmitters = array();

	public static function declareTransmitter()
	{
		if(!is_callable($class, true))
		{
			throw new \Exception(); // @todo exception - invalid transmitter callback specified
		}
		self::$transmitters[$name] = $class;
	}

	public static function getTransmitter()
	{
		if(self::$transmitter !== NULL)
		{
			return self::$transmitter;
		}

		if(!isset(self::$transmitters[self::getConfig('sfs.transmitter')]))
		{
			throw new \Exception(); // @todo exception - invalid transmitter specified
		}

		$class = self::$transmitters[self::getConfig('sfs.transmitter')];
		$transmitter = new $class();

		if(!$transmitter instanceof \emberlabs\sfslib\Transmission\TransmitterInterface)
		{
			throw new \Exception();
		}

		self::$transmitter = $transmitter;

		return $transmitter;
	}

	public static function getUserAgent()
	{
		return sprintf('emberlabs.sfslib:%1$s;PHP:%2$s', self::LIB_VERSION, PHP_VERSION);
	}
}
