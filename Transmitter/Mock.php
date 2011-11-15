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

namespace emberlabs\sfslib\Transmitter;
use \emberlabs\sfslib\Library as SFS;

/**
 * StopForumSpam integration library - Transmitter object
 * 	     Provides functionality to communicate with StopForumSpam.
 *
 * @package     sfslib
 * @author      emberlabs.org
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/emberlabs/sfslib
 */
class Mock implements TransmitterInterface
{
	protected $mock_response = '';

	public function setMockResponse($response)
	{
		$this->mock_response = (string) $response;
	}

	/**
	 * Send a POST transmission to StopForumSpam
	 * @param \emberlabs\sfslib\Transmission\TransmissionInstanceInterface $transmission - The transmission to send
	 * @return \emberlabs\sfslib\Transmission\TransmissionResultInterface - The transmission result object
	 */
	public function sendPOST(\emberlabs\sfslib\Transmission\TransmissionInstanceInterface $transmission)
	{
		print_r('mock POST request to API' . "\n");
		print_r('url built for request: ');
		print_r($transmission->buildPOSTURL() . '&useragent=' . rawurlencode(SFS::getUserAgent()) . "\n");
		print_r('post data sent for request: ');
		print_r($transmission->buildPOSTData() . "\n");
		print_r('mock response: ');
		print_r($this->mock_response  . "\n");

		return $transmission->newResponse($this->mock_response);
	}

	/**
	 * Send a GET transmission to StopForumSpam
	 * @param \emberlabs\sfslib\Transmission\TransmissionInstanceInterface $transmission - The transmission to send
	 * @return \emberlabs\sfslib\Transmission\TransmissionResultInterface - The transmission result object
	 */
	public function sendGET(\emberlabs\sfslib\Transmission\TransmissionInstanceInterface $transmission)
	{
		print_r('mock GET request to API' . "\n");
		print_r('url built for request: ');
		print_r($transmission->buildGETURL() . '&useragent=' . rawurlencode(SFS::getUserAgent()) . "\n");
		print_r('mock response: ');
		print_r($this->mock_response  . "\n");

		return $transmission->newResponse($this->mock_response);
	}
}
