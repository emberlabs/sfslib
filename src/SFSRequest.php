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
class SFSRequest
{
	/**
	 * @var string - Constant defining what API retrieval method we are using here
	 */
	const SFS_API_METHOD = 'json';

	/**
	 * @var string - Constant defining the base URL of the StopForumSpam API.
	 */
	const SFS_API_URL = 'http://www.stopforumspam.com/api';

	/**
	 * @var string - The username to look up
	 */
	protected $username = '';

	/**
	 * @var string - The email to look up
	 */
	protected $email = '';

	/**
	 * @var string - The IP to look up
	 */
	protected $ip = '';

	/**
	 * @var SFS - The primary StopForumSpam object
	 */
	protected $sfs;

	/**
	 * Constructor
	 * @param SFS $sfs - The primary SFS object.
	 * @return void
	 */
	public function __construct(SFS $sfs)
	{
		$this->sfs = $sfs;
	}

	/**
	 * Sets the username that we are checking.
	 * @var string $username - The username to check.
	 * @return SFSRequest - Provides a fluid interface.
	 */
	public function setUsername($username)
	{
		$this->username = $username;
		return $this;
	}

	/**
	 * Sets the email that we are checking.
	 * @var string $email - The email address to check.
	 * @return SFSRequest - Provides a fluid interface.
	 */
	public function setEmail($email)
	{
		// @todo validate email here
		$this->email = $email;
		return $this;
	}

	/**
	 * Sets the IP that we are checking.
	 * @var string $ip - The IP to check.
	 * @return SFSRequest - Provides a fluid interface.
	 */
	public function setIP($ip)
	{
		// @todo validate IP here
		$this->ip = $ip;
		return $this;
	}

	/**
	 * Prepare data for the API request.  This is cut out into its own method in case it needs changed later, for a more thorough encode later on.
	 * @param string $data - The information to prepare for submission...
	 * @return string - The encoded data.
	 */
	protected function prepareAPIData($data)
	{
		return urlencode($data);
	}

	/**
	 * Builds the URL for our StopForumSpam API _GET request, based on the chunks of information we are looking for.
	 * @return string - The URL to use for the _GET request.
	 */
	protected function buildURL()
	{
		$url = self::SFS_API_URL . '?';
		$url .= ($this->username) ? 'username=' . $this->prepareAPIData($this->username) : '';
		$url .= ($this->email) ? 'email=' . $this->prepareAPIData($this->email) : '';
		$url .= ($this->ip) ? 'ip=' . $this->prepareAPIData($this->ip) : '';
		$url .= 'f=' . self::SFS_API_METHOD;

		return $url;
	}

	/**
	 * Sends the StopForumSpam API _GET request, based on the chunks of information we are looking for.
	 * @return SFSResult - The results of the lookup.
	 *
	 * @throws SFSRequestException
	 */
	public function send()
	{
		if(empty($this->username) && empty($this->email) && empty($this->ip))
			throw new SFSRequestException('No request data provided for SFS API request', SFSRequestException::ERR_NO_REQUEST_DATA);

		// Setup the stream timeout, just in case
		$ctx = stream_context_create(array(
			'http'	=> array(
				'timeout'	=> $this->sfs->getStreamTimeout(),
			),
		));

		$json = file_get_contents($this->buildURL(), false, $ctx);

		if(!$json)
			throw new SFSRequestException('No data recieved from SFS API', SFSRequestException::ERR_API_RETURN_EMPTY);

		// Be prepared in case we get invalid JSON...
		try
		{
			$data = OfJSON::decode($json, false);
		}
		catch(OfJSONException $e)
		{
			throw new SFSRequestException('Invalid JSON recieved from SFS API', SFSRequestException::ERR_API_RETURNED_BAD_JSON);
		}

		// Did the StopForumSpam API return an error?
		if($data['error'])
			throw new SFSRequestException(sprintf('StopForumSpam API error: %1$s', $data['error']), SFSRequestException::ERR_API_RETURNED_ERROR);

		return new SFSResult($this->sfs, $data);
	}
}
