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

namespace emberlabs\sfslib\Transmission\Request;
use \emberlabs\sfslib\Error\APIError;
use \emberlabs\sfslib\Internal\RequestException;
use \emberlabs\sfslib\Transmission\TransmissionInstanceInterface;
use \OpenFlame\Framework\Utility\JSON;
use \InvalidArgumentException;

/**
 * StopForumSpam integration library - Request response object
 * 	     Represents the response from the StopForumSpam API.
 *
 * @package     sfslib
 * @author      emberlabs.org
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/emberlabs/sfslib
 */
class Response implements \emberlabs\sfslib\Transmission\TransmissionResponseInterface
{
	/**
	 * @const string - The date() format for dates returned by the StopForumSpam API.
	 */
	const SFS_DATETIME_FORMAT = 'Y-m-d H:i:s';

	/**
	 * @const string - The PHP DateTimeZone timezone string for the StopForumSpam API.
	 */
	const SFS_TIMEZONE = 'Etc/GMT-5';

	protected $data = array();

	public static function getResponse(TransmissionInstanceInterface $transmission, $json)
	{
		// empty json = fail
		if(!$json)
		{
			throw new RequestException('No response received from StopForumSpam API');
		}

		try
		{
			// decode the json into an associative array
			$data = JSON::decode($json);
		}
		catch(\RuntimeException $e)
		{
			// handling bad json responses
			throw new RequestException('Invalid JSON received from StopForumSpam API');
		}

		if(!isset($data['successful']) || $data['successful'] != 1)
		{
			// error!

			$response = new APIError($transmission, $data);
			return $response;
		}

		// success!

		$response = new self($transmission, $data);
		return $response;
	}

	protected function __construct(TransmissionInstanceInterface $transmission, $data)
	{
		foreach($data['username'] as $entry)
		{
			$result = new Result($entry);
			$this->data['username'][$result->getValue()] = $result;
		}

		foreach($data['email'] as $entry)
		{
			$result = new Result($entry);
			$this->data['email'][$result->getValue()] = $result;
		}

		foreach($data['ip'] as $entry)
		{
			$result = new Result($entry);
			$this->data['ip'][$result->getValue()] = $result;
		}
	}

	public function isError()
	{
		return false;
	}
}
