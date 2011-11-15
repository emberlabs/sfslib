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

namespace emberlabs\sfslib\Transmission\Report;
use \emberlabs\sfslib\Internal\ReportException;
use \emberlabs\sfslib\Transmission\TransmissionInstanceInterface;
use \emberlabs\sfslib\Transmission\TransmissionResponseInterface;
use \emberlabs\sfslib\Transmission\Report\Error as ReportError;
use \emberlabs\sfslib\Transmission\Report\Error as ReportResult;
use \OpenFlame\Framework\Utility\JSON;
use \InvalidArgumentException;

/**
 * StopForumSpam integration library - Report response object
 * 	     Represents the response from the StopForumSpam API.
 *
 * @package     sfslib
 * @author      emberlabs.org
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/emberlabs/sfslib
 */
class Response implements TransmissionResponseInterface
{
	/**
	 * @var array - The array of result objects representing the data returned by the API.
	 */
	protected $data = array();

	/**
	 * Get the response object to represent the response to our report to the StopForumSpam API.
	 * @param TransmissionInstanceInterface $transmission - The transmission object that we sent to the API.
	 * @param string $json - The json string received from the API.
	 * @return self|ReportError - The response object representing data received from the API, or the error object representing the request error that occurred.
	 */
	public static function getResponse(TransmissionInstanceInterface $transmission, $json)
	{
		// empty json = fail
		if(!$json)
		{
			return new ReportError($transmission, array());
		}

		try
		{
			// decode the json into an associative array
			$data = JSON::decode($json);
		}
		catch(\RuntimeException $e)
		{
			// handling bad json responses
			return new ReportError($transmission, array());
		}

		if(!isset($data['success']) || $data['success'] != 1)
		{
			// error!
			return new ReportError($transmission, $data);
		}

		// success!
		return new self($transmission, $data);
	}
}
