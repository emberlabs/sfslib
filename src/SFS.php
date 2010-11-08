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
 * SFS Integration - Primary interface class,
 *      Provides quick access to common tasks performed with the library.
 *
 *
 * @package     sfsintegration
 * @author      Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/SFSIntegration
 */
class SFS
{
	/**
	 * @const - The version stamp for the library.
	 */
	const VERSION = '0.3.0-DEV';

	/**
	 * @const - Flag to state that the curl GET method should only be used for reporting spammers.
	 * @note This disables the ability to send the data from the "evidence" field.
	 */
	const TRX_REPORT_GET_CURL = 1;

	/**
	 * @const - Flag to state that the file_get_contents() GET method should only be used for reporting spammers.
	 * @note This disables the ability to send the data from the "evidence" field.
	 */
	const TRX_REPORT_GET_FILE = 2;

	/**
	 * @const - Flag to state that the curl POST method should only be used for reporting spammers.
	 */
	const TRX_REPORT_POST_CURL = 3;

	/**
	 * @const - Flag to state that the socket-based POST method should only be used for reporting spammers.
	 */
	const TRX_REPORT_POST_SOCKET = 4;

	/**
	 * @const - Flag to state that the curl GET method should only be used for StopForumSpam database queries.
	 */
	const TRX_REQUEST_CURL = 1;

	/**
	 * @const - Flag to state that the file_get_contents GET method should only be used for StopForumSpam database queries.
	 */
	const TRX_REQUEST_FILE = 2;

	/**
	 * @var integer - The timeout (in seconds) to use when submitting a request to StopForumSpam.
	 */
	protected $timeout = 2;

	/**
	 * @var mixed - The transmission method to force on requests if desired.  NULL if no override desired.
	 */
	protected $request_method;

	/**
	 * @var mixed - The transmission method to force on reports if desired.  NULL if no override desired.
	 */
	protected $report_method;

	/**
	 * @var string - The API key to use for reports to StopForumSpam.
	 */
	protected $api_key = '';

	/**
	 * Autoloader method
	 * @param string $class - The class to load up
	 * @return boolean - True if load successful, false if we could not find the file to load
	 *
	 * @throws Exception
	 *
	 * @note - We use the exception class Exception here instead of SFSException to prevent loader failure if the SFSException class is not present.
	 */
	public static function loader($class)
	{
		if(file_exists(SFSLIB . basename($class) . '.php'))
		{
			require SFSLIB . basename($class) . '.php';
			if(!class_exists($class, false))
				throw new Exception(sprintf('SFS Integration Library Autoloader failed to load correct class file for class "%1$s"', $class));
			return true;
		}
		return false;
	}

	/**
	 * Create a new StopForumSpam request instance.
	 * @return SFSRequest - The StopForumSpam request instance.
	 */
	public function newRequest()
	{
		return new SFSRequest($this);
	}

	/**
	 * Create a new StopForumSpam report instance.
	 * @return SFSReport - The StopForumSpam report instance.
	 */
	public function newReport()
	{
		// If we have an API key set, use it.
		if(!is_null($this->api_key))
		{
			return new SFSReport($this);
		}
		else
		{
			$report = new SFSReport($this);
			$report->setAPIKey($this->api_key);
			return $report;
		}
	}

	/**
	 * Get the stream timeout setting.
	 * @return integer - The current stream timeout, in seconds.
	 */
	public function getTimeout()
	{
		return (int) $this->timeout;
	}

	/**
	 * Set the current stream timeout.
	 * @param integer $timeout - The stream timeout to set, in seconds.
	 * @return SFS - Provides a fluent interface.
	 */
	public function setTimeout($timeout)
	{
		$this->timeout = (int) $timeout;
		return $this;
	}

	/**
	 * Set the API key to use for reporting spammers.
	 * @param string $api_key - The API key to use.
	 * @return SFS - Provides a fluent interface.
	 *
	 * @throws SFSException
	 */
	public function setAPIKey($api_key)
	{
		// validate API key here
		$this->api_key = $api_key;

		return $this;
	}

	/**
	 * Get the method currently overriding the request transmission method.
	 * @return mixed - NULL if no override present, or the method's constant value if present.
	 */
	public function getRequestMethod()
	{
		return $this->request_method;
	}

	/**
	 * Overrride the transmission method to use for requesting a StopForumSpam database query.
	 * @param integer $method - The transmission method to use for requests.
	 * @return SFS - Provides a fluent interface.
	 *
	 * @throws SFSException
	 */
	public function setRequestMethod($method)
	{
		if(!in_array($method, array(self::TRX_REQUEST_CURL, self::TRX_REQUEST_FILE)))
			throw new SFSException('Invalid request transmission method specified', SFSException::ERR_REQUEST_METHOD_OVERRIDE_INVALID);

		$this->request_method = $method;
		return $this;
	}

	/**
	 * Get the method currently overriding the report transmission method.
	 * @return mixed - NULL if no override present, or the method's constant value if present.
	 */
	public function getReportMethod()
	{
		return $this->report_method;
	}

	/**
	 * Overrride the transmission method to use for reporting spammers.
	 * @param integer $method - The transmission method to use for reports.
	 * @return SFS - Provides a fluent interface.
	 *
	 * @throws SFSException
	 */
	public function setReportMethod($method)
	{
		if(!in_array($method, array(self::TRX_REPORT_GET_CURL, self::TRX_REPORT_GET_FILE, self::TRX_REPORT_POST_CURL, self::TRX_REPORT_POST_SOCKET)))
			throw new SFSException('Invalid report transmission method specified', SFSException::ERR_REPORT_METHOD_OVERRIDE_INVALID);

		$this->report_method = $method;
		return $this;
	}
}
