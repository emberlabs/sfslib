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
 * SFS Integration - SFS Report Object,
 *      Reports a user to the StopForumSpam API.
 *
 *
 * @package     sfsintegration
 * @author      Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/SFSIntegration
 */
class SFSReport extends SFSTransmission
{
	/**
	 * @const - Constant defining what API response serialization method we are using here.
	 */
	const SFS_API_METHOD = 'json';

	/**
	 * @const - Constant defining the base URL of the StopForumSpam API.
	 */
	const SFS_API_URL = 'http://www.stopforumspam.com/add.php';

	/**
	 * @var string - The API key to submit with.
	 */
	protected $api_key;

	/**
	 * @var string - The evidence to send for the spammer report.
	 */
	protected $evidence;

	/**
	 * asdf
	 */
	public function setEvidence($evidence)
	{
		// asdf
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
		// Make sure this API key they are giving us is a valid key by way of regexp (we can't check if it is an actual key, though).
		if(filter_var($api_key, FILTER_VALIDATE_REGEXP, array('regexp' => self::API_KEY_REGEX)) === false)
			throw new SFSReportException("Invalid API key entered", SFSReportException::ERR_INVALID_API_KEY);

		$this->api_key = $api_key;

		return $this;
	}

	/**
	 * Builds the URL for our StopForumSpam API _GET request, based on the chunks of information we are looking for.
	 * @return string - The URL to use for the _GET request.
	 */
	protected function buildURL()
	{
		$url = self::SFS_API_URL  . '?';
		$url .= ($this->username) ? 'username=' . $this->prepareAPIData($this->username) . '&' : '';
		$url .= ($this->email) ? 'email=' . $this->prepareAPIData($this->email) . '&' : '';
		$url .= ($this->ip) ? 'ip=' . $this->prepareAPIData($this->ip) . '&' : '';
		$url .= ($this->api_key) ? 'api_key=' . $this->prepareAPIData($this->api_key) . '&' : '';
		$url .= 'f=' . self::SFS_API_METHOD;

		return $url;
	}

	public function send()
	{
		// asdf
	}
}
