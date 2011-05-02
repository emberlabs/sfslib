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

namespace Codebite\StopForumSpam\Request;
use \Codebite\StopForumSpam\Core;
use \Codebite\StopForumSpam\Error\InternalException;

if(!defined('Codebite\\StopForumSpam\\ROOT_PATH')) exit;

/**
 * StopForumSpam Integration - Request Instance object
 * 	     Represents the request to be made of the StopForumSpam API..
 *
 * @package     sfsintegration
 * @author      Damian Bushong
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/damianb/SFSIntegration
 */
class Instance
{

	/**
	 * @const - Constant defining what API response serialization method we are using here.
	 */
	const API_METHOD = 'json';

	/**
	 * @const - Constant defining the base URL of the StopForumSpam API.
	 */
	const API_URL = 'http://www.stopforumspam.com/api';

	protected $username;

	protected $email;

	protected $ip;

	public function newRequest()
	{
		$self = new static();

		return $self;
	}

	/**
	 * Sets the username that we are transmitting.
	 * @var string $username - The username to check.
	 * @return \Codebite\StopForumSpam\Request\Instance - Provides a fluid interface.
	 */
	public function setUsername($username)
	{
		$this->username = $username;

		return $this;
	}

	/**
	 * Sets the email that we are transmitting.
	 * @var string $email - The email address to check.
	 * @return \Codebite\StopForumSpam\Request\Instance - Provides a fluid interface.
	 *
	 * @throws \Codebite\StopForumSpam\InternalException
	 */
	public function setEmail($email)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL) === false)
		{
			throw new InternalException('Invalid email address supplied');
		}

		$this->email = $email;

		return $this;
	}

	/**
	 * Sets the IP that we are transmitting.
	 * @var string $ip - The IP to check.
	 * @return \Codebite\StopForumSpam\Request\Instance - Provides a fluid interface.
	 *
	 * @throws \Codebite\StopForumSpam\InternalException
	 */
	public function setIP($ip)
	{
		/**
		 * Validation will check for IPv4 only, and no reserved or private IP ranges
		 *
		 * Validation will fail on the following IP ranges:
		 * 0.0.0.0/8
		 * 10.0.0.0/8
		 * 169.254.0.0/16
		 * 172.16.0.0/12
		 * 192.0.2.0/24
		 * 192.168.0.0/16
		 * 224.0.0.0/4
		 */
		if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 & FILTER_FLAG_NO_PRIV_RANGE & FILTER_FLAG_NO_RES_RANGE) === false)
		{
			throw new InternalException('Invalid IP address supplied');
		}

		$this->ip = $ip;

		return $this;
	}
}
