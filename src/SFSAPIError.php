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
 * SFS Integration - API Error class,
 *      Used to wrap around API errors and give an easy-to-use interface to handle them.
 *
 *
 * @package     sfsintegration
 * @author      Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/SFSIntegration
 */
class SFSAPIError implements Iterator
{
	/**
	 * @const - Error flag used when an unknown error is found in the returned API error code.
	 */
	const ERR_WTF = -1;

	/**
	 * @const - Bitwise error flag used when the email address submitted is invalid.
	 */
	const API_ERR_EMAIL_INVALID = 1;

	/**
	 * @const - Bitwise error flag used when the IP submitted is invalid.
	 */
	const API_ERR_IP_INVALID = 2;

	/**
	 * @const - Bitwise error flag used when the username submitted is invalid.
	 */
	const API_ERR_USERNAME_INVALID = 4;

	/**
	 * @const - Bitwise error flag used when the API key submitted to StopForumSpam is considered "invalid".
	 */
	const API_ERR_API_KEY_INVALID = 8;

	/**
	 * @const - Bitwise error flag used when the email address submitted is rejected because StopForumSpam thinks it is our own.
	 */
	const API_ERR_OWN_EMAIL_SUBMIT = 16;

	/**
	 * @const - Bitwise error flag used when the IP address submitted is rejected because StopForumSpam thinks it is our own.
	 */
	const API_ERR_OWN_IP_SUBMIT = 32;

	/**
	 * @const - The maximum range of known errors that the StopForumSpam API can return.
	 */
	const KNOWN_ERROR_MAX = 63;

	/**
	 * @var array - An array containing all of the bitwise error flags to check for in the returned error code, intended for use with foreach()
	 */
	protected $error_codes = array(
		self::API_ERR_EMAIL_INVALID,
		self::API_ERR_IP_INVALID,
		self::API_ERR_USERNAME_INVALID,
		self::API_ERR_API_KEY_INVALID,
		self::API_ERR_OWN_EMAIL_SUBMIT,
		self::API_ERR_OWN_IP_SUBMIT,
	);

	/**
	 * @var array - An array containing all of the error descriptions associated to each bitwise error flag.
	 */
	protected $descriptions = array(
		self::ERR_WTF					=> 'Unknown error',
		self::API_ERR_EMAIL_INVALID		=> 'The email address provided was rejected by the StopForumSpam API.',
		self::API_ERR_IP_INVALID		=> 'The IP address provided was rejected by the StopForumSpam API.',
		self::API_ERR_USERNAME_INVALID	=> 'The username provided was rejected by the StopForumSpam API.',
		self::API_ERR_API_KEY_INVALID	=> 'The StopForumSpam API rejected the API key provided.',
		self::API_ERR_OWN_EMAIL_SUBMIT	=> 'The StopForumSpam API rejected the email address provided as it does not allow reporting your own email address.',
		self::API_ERR_OWN_IP_SUBMIT		=> 'The StopForumSpam API rejected the IP address provided as it does not allow reporting your own IP address.',
	);

	/**
	 * @var integer - The error code returned by the StopForumSpam API.
	 */
	protected $api_error = 0;

	/**
	 * @var array - An array that will contain all of the bitwise error flags that are encountered in the error code.
	 */
	protected $errors = array();

	public function __construct($code = false)
	{
		if($code !== false)
			$this->setAPIErrorCode($code);
	}

	public function setAPIErrorCode($code)
	{
		if(!ctype_digit($code))
			$this->api_error = 0;
		$this->api_error = (int) $code;
	}

	public function extractErrors()
	{
		foreach($this->error_codes as $error)
		{
			if(($this->api_error & $error) === $error)
				$this->errors[] = $error;
		}

		// Check to see if unknown errors are also present.
		if(($code - self::KNOWN_ERROR_MAX) > 0)
		{
			// wat
			$this->errors[] = self::ERR_WTF;
		}
	}

	public function getDescription($error)
	{
		if(!isset($this->descriptions[(int) $error]))
			return NULL;
		return $this->descriptions[(int) $error];
	}

	public function getErrorsFound()
	{
		// asdf
	}

	public function checkError($error)
	{
		// check to see if a certain error was encountered
		// asdf
	}

	// @todo iterator methods
}
