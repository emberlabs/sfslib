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
 * SFS Integration - SFS Result Object,
 *      Provides an interface to results obtained from a StopForumSpam lookup.
 *
 *
 * @package     sfsintegration
 * @author      Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/SFSIntegration
 */
class SFSRequestResult implements ArrayAccess
{
	/**
	 * @var SFS - The primary StopForumSpam object.
	 */
	protected $sfs;

	/**
	 * @var array - The array of raw data that we received from StopForumSpam.
	 */
	protected $raw_data = array();

	/**
	 * @var boolean - Whether or not the API request was marked as "successful" in the returned data.
	 */
	protected $successful = false;

	/**
	 * @var array - Array of returned username results from StopForumSpam.
	 */
	protected $username = array();

	/**
	 * @var string - The username we sent in our request to StopForumSpam.
	 */
	protected $username_data = '';

	/**
	 * @var boolean - Whether or not the username was found in the StopForumSpam database search.
	 */
	protected $username_appears = false;

	/**
	 * @var integer - How many times the username was found in the StopForumSpam database search.
	 */
	protected $username_frequency = 0;

	/**
	 * @var DateTime - The last time the username was reported to the StopForumSpam service.
	 */
	protected $username_lastseen = NULL;

	/**
	 * @var DateInterval - The time interval between now and the time that the username was last reported.
	 */
	protected $username_lastseen_span = NULL;

	/**
	 * @var array - Array of returned email results from StopForumSpam.
	 */
	protected $email = array();

	/**
	 * @var string - The email we sent in our request to StopForumSpam.
	 */
	protected $email_data = '';

	/**
	 * @var boolean - Whether or not the email was found in the StopForumSpam database search.
	 */
	protected $email_appears = false;

	/**
	 * @var integer - How many times the email was found in the StopForumSpam database search.
	 */
	protected $email_frequency = 0;

	/**
	 * @var DateTime - The last time the email was reported to the StopForumSpam service.
	 */
	protected $email_lastseen = NULL;

	/**
	 * @var DateInterval - The time interval between now and the time that the email was last reported.
	 */
	protected $email_lastseen_span = NULL;

	/**
	 * @var array - Array of returned IP results from StopForumSpam.
	 */
	protected $ip = array();

	/**
	 * @var string - The IP we sent in our request to StopForumSpam.
	 */
	protected $ip_data = '';

	/**
	 * @var boolean - Whether or not the IP was found in the StopForumSpam database search.
	 */
	protected $ip_appears = false;

	/**
	 * @var integer - How many times the IP was found in the StopForumSpam database search.
	 */
	protected $ip_frequency = 0;

	/**
	 * @var DateTime - The last time the IP was reported to the StopForumSpam service.
	 */
	protected $ip_lastseen = NULL;

	/**
	 * @var DateInterval - The time interval between now and the time that the username was last reported.
	 */
	protected $ip_lastseen_span = NULL;

	/**
	 * @const string - The date() format for dates returned by the StopForumSpam service.
	 */
	const SFS_DATETIME_FORMAT = 'Y-m-d H:i:s';

	/**
	 * @const string - The PHP DateTimeZone timezone string for the StopForumSpam service.
	 */
	const SFS_TIMEZONE = 'Etc/GMT-5';

	/**
	 * Constructor
	 * @param SFS $sfs - The primary SFS interaction object.
	 * @param array $data - The array of data returned by StopForumSpam.
	 * @param array $requested_data - The array of data that we sent in our API request to StopForumSpam.
	 * @return void
	 */
	public function __construct(SFS $sfs, array $data, array $requested_data)
	{
		// Store the main SFS object
		$this->sfs = $sfs;

		// Store the raw data we received...
		$this->raw_data = $data;

		$this->successful = ($data['success'] === 1) ? true : false;

		$this->username_data = $requested_data['username'];
		$this->email_data = $requested_data['email'];
		$this->ip_data = $requested_data['ip'];

		// Instantiate the DateTimeZone object for StopForumSpam's timezone.
		$timezone = new DateTimeZone(self::SFS_TIMEZONE);

		// Get the current time.
		$now = new DateTime('now', $timezone);

		// If we looked up a username, arm the username fields with the necessary data.
		if($this->username_data)
		{
			$this->username_appears = ($data['username']['appears'] === 1) ? true : false;
			$this->username_frequency = (int) $data['username']['frequency'];
			if($this->username_appears)
			{
				$this->username_lastseen = DateTime::createFromFormat(self::SFS_DATETIME_FORMAT, $data['username']['lastseen'], $timezone);
				$this->username_lastseen_span = (!defined('SFSLIB_COMPATIBILITY_MODE')) ? $this->username_lastseen->diff($now, true) : false;
			}
		}

		// If we looked up an email address, arm the email fields with the necessary data.
		if($this->email_data)
		{
			$this->email_appears = ($data['email']['appears'] === 1) ? true : false;
			$this->email_frequency = (int) $data['email']['frequency'];
			if($this->email_appears)
			{
				$this->email_lastseen = DateTime::createFromFormat(self::SFS_DATETIME_FORMAT, $data['email']['lastseen'], $timezone);
				$this->email_lastseen_span = (!defined('SFSLIB_COMPATIBILITY_MODE')) ? $this->email_lastseen->diff($now, true): false;
			}
		}

		// If we looked up an IP address, arm the IP fields with the necessary data.
		if($this->ip_data)
		{
			$this->ip_appears = ($data['ip']['appears'] === 1) ? true : false;
			$this->ip_frequency = (int) $data['ip']['frequency'];
			if($this->ip_appears)
			{
				$this->ip_lastseen = DateTime::createFromFormat(self::SFS_DATETIME_FORMAT, $data['ip']['lastseen'], $timezone);
				$this->ip_lastseen_span = (!defined('SFSLIB_COMPATIBILITY_MODE')) ? $this->ip_lastseen->diff($now, true) : false;
			}
		}

		$this->username = $this->getUsernameToArray();
		$this->email = $this->getEmailToArray();
		$this->ip = $this->getIPToArray();
	}

	/**
	 * Return the array of raw data that was provided to this object
	 * @return array - The array of raw data.
	 */
	public function toArray()
	{
		return $this->raw_data;
	}

	/**
	 * Grab all of the username result data as an array.
	 * @return array - An array of username result data.
	 */
	protected function getUsernameToArray()
	{
		$return = array(
			'data'			=> &$this->username_data,
			'appears'		=> &$this->username_appears,
		);
		if($this->username_appears)
		{
			$return = array_merge($return, array(
				'frequency'		=> &$this->username_frequency,
				'lastseen'		=> &$this->username_lastseen,
				'lastseen_span'	=> &$this->username_lastseen_span,
			));
		}
		return $return;
	}

	/**
	 * Grab all of the email result data as an array.
	 * @return array - An array of email result data.
	 */
	protected function getEmailToArray()
	{
		$return = array(
			'data'			=> &$this->email_data,
			'appears'		=> &$this->email_appears,
		);
		if($this->email_appears)
		{
			$return = array_merge($return, array(
				'frequency'		=> &$this->email_frequency,
				'lastseen'		=> &$this->email_lastseen,
				'lastseen_span'	=> &$this->email_lastseen_span,
			));
		}
		return $return;
	}

	/**
	 * Grab all of the IP result data as an array.
	 * @return array - An array of IP result data.
	 */
	protected function getIPToArray()
	{
		$return = array(
			'data'			=> &$this->ip_data,
			'appears'		=> &$this->ip_appears,
		);
		if($this->ip_appears)
		{
			$return = array_merge($return, array(
				'frequency'		=> &$this->ip_frequency,
				'lastseen'		=> &$this->ip_lastseen,
				'lastseen_span'	=> &$this->ip_lastseen_span,
			));
		}
		return $return;
	}

	/**
	 * Retrieve the username we looked up.
	 * @return string - The username we looked up.
	 */
	public function getUsernameData()
	{
		return (string) $this->username_data;
	}

	/**
	 * Retrieve whether or not the username appears in the StopForumSpam database.
	 * @return boolean - Was the data found in the API query?
	 */
	public function getUsernameAppears()
	{
		return (bool) $this->username_appears;
	}

	/**
	 * Retrieve how many times the data was found in the StopForumSpam database.
	 * @return integer - How many times was the data found in the API query?
	 */
	public function getUsernameFrequency()
	{
		return (int) $this->username_frequency;
	}

	/**
	 * Retrieve the DateTime object representing when the username was last reported to StopForumSpam.
	 * @return mixed - NULL if username was never found in the request, or a DateTime object representing when the username was last reported.
	 */
	public function getUsernameLastseen()
	{
		return $this->username_lastseen;
	}

	/**
	 * Retrieve the DateInterval object representing the interval of time between now and the last time the username was reported.
	 * @return mixed - NULL if username was never found in the request, false if PHP 5.3 is not present, or a DateInterval object representing the interval of time since the username was last reported.
	 */
	public function getUsernameLastseenSpan()
	{
		return $this->username_lastseen_span;
	}

	/**
	 * Retrieve the email we looked up.
	 * @return string - The email we looked up.
	 */
	public function getEmailData()
	{
		return (string) $this->email_data;
	}

	/**
	 * Retrieve whether or not the IP appears in the StopForumSpam database.
	 * @return boolean - Was the data found in the API query?
	 */
	public function getEmailAppears()
	{
		return (bool) $this->email_appears;
	}

	/**
	 * Retrieve how many times the data was found in the StopForumSpam database.
	 * @return integer - How many times was the data found in the API query?
	 */
	public function getEmailFrequency()
	{
		return (int) $this->email_frequency;
	}

	/**
	 * Retrieve the DateTime object representing when the email was last reported to StopForumSpam.
	 * @return mixed - NULL if email was never found in the request, or a DateTime object representing when the email was last reported.
	 */
	public function getEmailLasteen()
	{
		return $this->email_lastseen;
	}

	/**
	 * Retrieve the DateInterval object representing the interval of time between now and the last time the email was reported.
	 * @return mixed - NULL if email was never found in the request, false if PHP 5.3 is not present, or a DateInterval object representing the interval of time since the email was last reported.
	 */
	public function getEmailLastseenSpan()
	{
		return $this->email_lastseen_span;
	}

	/**
	 * Retrieve the IP we looked up.
	 * @return string - The IP we looked up.
	 */
	public function getIPData()
	{
		return (string) $this->ip_data;
	}

	/**
	 * Retrieve whether or not the IP appears in the StopForumSpam database.
	 * @return boolean - Was the data found in the API query?
	 */
	public function getIPAppears()
	{
		return (bool) $this->ip_appears;
	}

	/**
	 * Retrieve how many times the data was found in the StopForumSpam database.
	 * @return integer - How many times was the data found in the API query?
	 */
	public function getIPFrequency()
	{
		return (int) $this->ip_frequency;
	}

	/**
	 * Retrieve the DateTime object representing when the IP was last reported to StopForumSpam.
	 * @return mixed - NULL if IP was never found in the request, or a DateTime object representing when the IP was last reported.
	 */
	public function getIPLastseen()
	{
		return $this->ip_lastseen;
	}

	/**
	 * Retrieve the DateInterval object representing the interval of time between now and the last time the IP was reported.
	 * @return mixed - NULL if IP was never found in the request, false if PHP 5.3 is not present, or a DateInterval object representing the interval of time since the IP was last reported.
	 */
	public function getIPLastseenSpan()
	{
		return $this->ip_lastseen_span;
	}

	/**
	 * Retrieve whether or not the lookup was successful.
	 * @return boolean - Was the lookup successful?
	 */
	public function getSuccessful()
	{
		return $this->successful;
	}

	/**
	 * Check to see if the offset we are accessing via ArrayAccess is something we're allowed to look at.
	 * @param string $offset - The offset we want to access.
	 * @return boolean - Are we allowed to access this offset?
	 */
	protected function checkAllowArrayAccess($offset)
	{
		return in_array($offset, array('successful', 'username', 'email', 'ip'));
	}

	/**
	 * ArrayAccess methods
	 */

	/**
	 * Check if an "array" offset exists in this object.
	 * @param mixed $offset - The offset to check.
	 * @return boolean - Does anything exist for this offset?
	 */
	public function offsetExists($offset)
	{
		return (property_exists($this, $offset) && !$this->checkAllowArrayAccess($offset));
	}

	/**
	 * Get an "array" offset for this object.
	 * @param mixed $offset - The offset to grab from.
	 * @return mixed - The value of the offset, or null if the offset does not exist.
	 *
	 * @throws SFSRequestException
	 */
	public function offsetGet($offset)
	{
		if(!property_exists($this, $offset))
			return NULL;

		if(!$this->checkAllowArrayAccess($offset))
			throw new SFSRequestException('Access of protected values in instantiated SFSResult object is not permitted', SFSRequestException::ERR_NO_MODIFY_DATA_ARRAYACCESS);

		return $this->$offset;
	}

	/**
	 * Set an "array" offset to a certain value, if the offset exists
	 * @param mixed $offset - The offset to set.
	 * @param mixed $value - The value to set to the offset.
	 * @return void
	 *
	 * @throws SFSRequestException
	 */
	public function offsetSet($offset, $value)
	{
		throw new SFSRequestException('Modification of values in instantiated SFSResult object is not permitted', SFSRequestException::ERR_NO_MODIFY_DATA_ARRAYACCESS);
	}

	/**
	 * Unset an "array" offset.
	 * @param mixed $offset - The offset to clear out.
	 * @return void
	 *
	 * @throws SFSRequestException
	 */
	public function offsetUnset($offset)
	{
		throw new SFSRequestException('Modification of values in instantiated SFSResult object is not permitted', SFSRequestException::ERR_NO_MODIFY_DATA_ARRAYACCESS);
	}
}
