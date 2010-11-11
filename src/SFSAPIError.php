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

	/**
	 * Constructor
	 * @param integer $code - The compiled error code returned from the API.
	 * @return void
	 */
	public function __construct($code = false)
	{
		if($code !== false)
			$this->setAPIErrorCode($code);
	}

	/**
	 * Set the compiled error code that was returned from the StopForumSpam API.
	 * @param integer $code - The error code returned from the API.
	 * @return SFSAPIError - Provides a fluent interface.
	 */
	public function setAPIErrorCode($code)
	{
		if(!ctype_digit($code))
			$this->api_error = 0;
		$this->api_error = (int) $code;

		return $this;
	}

	/**
	 * Extract all errors that were returned from the StopForumSpam API.
	 * @return SFSAPIError - Provides a fluent interface.
	 */
	public function extractErrors()
	{
		foreach($this->error_codes as $error)
		{
			if(($this->api_error & $error) === $error)
				$this->errors[] = $error;
		}

		// Check to see if unknown errors are also present.
		if(($code - self::KNOWN_ERROR_MAX) > 0)
			$this->errors[] = self::ERR_WTF; // wat

		return $this;
	}

	/**
	 * Get an error's description
	 * @param integer $error - The constant for the error code we are looking up.
	 * @return string - The error code description.
	 */
	public function getDescription($error)
	{
		if(!isset($this->descriptions[(int) $error]))
			return NULL;
		return $this->descriptions[(int) $error];
	}

	/**
	 * Get the array of errors
	 * @return array - Array of errors extracted.
	 */
	public function getErrors()
	{
		return (array) $this->errors;
	}

	/**
	 * Check to see if a specific error was returned from the StopForumSpam API.
	 * @param integer $error - The error to check for.
	 * @return boolean - Was the error encountered?
	 */
	public function checkError($error)
	{
		// If no error code at all, and no errors, return false to save time.
		if($this->api_error === 0 && empty($this->errors))
			return false;

		return in_array((int) $error, $this->errors);
	}

	/**
	 * Iterator methods
	 */

	/**
	 * Iterator method, rewinds the array back to the first element.
	 * @return void
	 */
	public function rewind()
	{
		return reset($this->errors);
	}

	/**
	 * Iterator method, returns the key of the current element
	 * @return scalar - The key of the current element.
	 */
	public function key()
	{
		return key($this->errors);
	}

	/**
	 * Iterator method, checks to see if the current position is valid.
	 * @return boolean - Whether or not the current array position is valid.
	 */
	public function valid()
	{
		return (!is_null(key($this->errors)));
	}

	/**
	 * Iterator method, gets the current element
	 * @return mixed - The current array element.
	 */
	public function current()
	{
		return current($this->errors);
	}

	/**
	 * Iterator method, moves to the next session available.
	 * @return void
	 */
	public function next()
	{
		next($this->errors);
	}
}
