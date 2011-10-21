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
use \OpenFlame\Framework\Dependency\Injector;

/**
 * StopForumSpam integration library - Manager object
 * 	     Provides quick and easy access to the library's functionality.
 *
 * @package     sfslib
 * @author      emberlabs.org
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/emberlabs/sfslib
 */
class Library
{
	/**
	 * @const string - The date() format for dates returned by the StopForumSpam API.
	 */
	const SFS_DATETIME_FORMAT = 'Y-m-d H:i:s';

	/**
	 * @const string - The PHP DateTimeZone timezone string for the StopForumSpam API.
	 */
	const SFS_TIMEZONE = 'Etc/GMT-5';

	protected static $instance;

	public static function getInstance()
	{
		if(!self::$instance)
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	protected function __construct()
	{
		// set default options...
		$defaults = array(
			'sfs.timeout'				=> 30,
			'sfs.transmitter'			=> 'file',
		);

		Core::declareTransmitter('file', '\\emberlabs\\sfslib\\Transmitter\\File');
		Core::declareTransmitter('curl', '\\emberlabs\\sfslib\\Transmitter\\cURL');

		$injector = Injector::getInstance();
		$injector->setInjector('sfs.timezone', function() {
			return new \DateTimeZone(self::SFS_TIMEZONE);
		});
		$injector->setInjector('sfs.now', function() use($injector) {
			return new \DateTime('now', $injector->get('sfs.timezone'));
		});

		foreach($configs as $name => $config)
		{
			if(Core::getConfig($name) === NULL)
			{
				Core::setConfig($name, $config);
			}
		}
	}

	public function setKey()
	{
		// asdf
	}

	public function getKey()
	{
		// asdf
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
