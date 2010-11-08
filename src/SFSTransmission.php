<?php
/**
 *
 *===================================================================
 *
 *  StopForumSpam integration library
 *-------------------------------------------------------------------
 * @package     sfsintegration
 * @author      Damian Bushong
 * @copyright   (c) 2010 Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/SFSIntegration
 *
 *===================================================================
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 */

/**
 * SFS Integration - SFS Request Object,
 *      Requests a user check against the StopForumSpam database using the JSON API.
 *
 *
 * @package     sfsintegration
 * @author      Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/SFSIntegration
 */
abstract class SFSTransmission
{
	/**
	 * @var string - The username to look up.
	 */
	protected $username = '';

	/**
	 * @var string - The email to look up.
	 */
	protected $email = '';

	/**
	 * @var string - The IP to look up.
	 */
	protected $ip = '';

	/**
	 * @var SFS - The primary StopForumSpam object.
	 */
	protected $sfs;

	/**
	 * Constructor
	 * @param SFS $sfs - The primary SFS interaction object.
	 * @return void
	 */
	final public function __construct(SFS $sfs)
	{
		$this->sfs = $sfs;
	}

	/**
	 * Sets the username that we are checking.
	 * @var string $username - The username to check.
	 * @return SFSRequest - Provides a fluid interface.
	 */
	final public function setUsername($username)
	{
		$this->username = $username;
		return $this;
	}

	/**
	 * Sets the email that we are checking.
	 * @var string $email - The email address to check.
	 * @return SFSRequest - Provides a fluid interface.
	 *
	 * @throws SFSRequestException
	 */
	final public function setEmail($email)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL) === false)
			throw new SFSTransmissionException('Invalid email address supplied', SFSTransmissionException::ERR_INVALID_EMAIL_SUPPLIED);

		$this->email = $email;
		return $this;
	}

	/**
	 * Sets the IP that we are checking.
	 * @var string $ip - The IP to check.
	 * @return SFSRequest - Provides a fluid interface.
	 *
	 * @throws SFSRequestException
	 */
	final public function setIP($ip)
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
			throw new SFSTransmissionException('Invalid IP address supplied', SFSTransmissionException::ERR_INVALID_IP_SUPPLIED);

		$this->ip = $ip;
		return $this;
	}

	/**
	 * Prepare data for the API request.  This is cut out into its own method in case it needs changed later, for a more thorough encode later on.
	 * @param string $data - The information to prepare for submission...
	 * @return string - The encoded data.
	 */
	final protected function prepareAPIData($data)
	{
		return urlencode($data);
	}

	/**
	 * Build our UserAgent string, and be sure to include the library version plus the PHP version we are running.
	 * @return string - The UserAgent string to send.
	 *
	 * @note useragent will look like the following:  PHP-SFSIntegration::0.3.0-DEV_PHP::5.3.2
	 */
	final protected function buildUserAgent()
	{
		return sprintf('PHP-SFSIntegration::%1$s_PHP::%2$s', SFS::VERSION, PHP_VERSION);
	}

	/**
	 * Sends the StopForumSpam API transmissions, based on the chunks of information we are looking for.
	 */
	abstract public function send();
}
