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
use \emberlabs\sfslib\Transmission\TransmissionResponseInterface;
use \emberlabs\sfslib\Transmission\Request\Error as RequestError;
use \OpenFlame\Framework\Core;
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
class Response implements TransmissionResponseInterface
{
	protected $data = array();

	public static function getResponse(TransmissionInstanceInterface $transmission, $json)
	{
		// empty json = fail
		if(!$json)
		{
			return new RequestError($transmission, array());
		}

		try
		{
			// decode the json into an associative array
			$data = JSON::decode($json);
		}
		catch(\RuntimeException $e)
		{
			// handling bad json responses
			return new RequestError($transmission, array());
		}

		if(!isset($data['successful']) || $data['successful'] != 1)
		{
			// error!
			return new RequestError($transmission, $data);
		}

		// success!
		return new self($transmission, $data);
	}

	protected function __construct(TransmissionInstanceInterface $transmission, $data)
	{
		foreach($data['username'] as $entry)
		{
			$result = new Result($entry, Result::RESULT_USERNAME);
			$this->data['username'][$result->getValue()] = $result;
		}

		foreach($data['email'] as $entry)
		{
			$result = new Result($entry, Result::RESULT_EMAIL);
			$this->data['email'][$result->getValue()] = $result;
		}

		foreach($data['ip'] as $entry)
		{
			$result = new Result($entry, Result::RESULT_IP);
			$this->data['ip'][$result->getValue()] = $result;
		}
	}

	public function isError()
	{
		return false;
	}

	/**
	 * @ignore
	 */
	public function __destruct()
	{
		$this->data = array();
	}
}
