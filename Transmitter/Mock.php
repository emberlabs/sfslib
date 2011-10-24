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

	public function send(\emberlabs\sfslib\Transmission\TransmissionInstanceInterface $transmission)
	{
		print_r('url built for request:');
		print_r($transmission->buildURL() . '&useragent=' . rawurlencode(SFS::getUserAgent()));
		print_r('mock response:');
		print_r($this->mock_response);

		return $transmission->newResponse($this->mock_response);
	}
}
