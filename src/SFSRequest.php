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
	 * @var string - Constant defining what API retrieval method we are using here.
	 */
	const SFS_API_METHOD = 'json';

	/**
	 * @var string - Constant defining the base URL of the StopForumSpam API.
	 */
	const SFS_API_URL = 'http://www.stopforumspam.com/api';

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
	 *
	 * @throws SFSRequestException
	 */
	public function setEmail($email)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL) === false)
			throw new SFSRequestException('Invalid email address supplied', SFSRequestException::ERR_INVALID_EMAIL_SUPPLIED);

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
			throw new SFSRequestException('Invalid IP address supplied', SFSRequestException::ERR_INVALID_IP_SUPPLIED);

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
		$url .= ($this->username) ? 'username=' . $this->prepareAPIData($this->username) . '&' : '';
		$url .= ($this->email) ? 'email=' . $this->prepareAPIData($this->email) . '&' : '';
		$url .= ($this->ip) ? 'ip=' . $this->prepareAPIData($this->ip) . '&' : '';
		$url .= 'f=' . self::SFS_API_METHOD;

		return $url;
	}

	/**
	 * Build our UserAgent string, and be sure to include the library version plus the PHP version we are running.
	 * @return string - The UserAgent string to send.
	 *
	 * @note useragent will look like the following:  PHP-SFSIntegration::0.1.0-DEV_PHP::5.3.2
	 */
	protected function buildUserAgent()
	{
		return sprintf('PHP-SFSIntegration::%1$s_PHP::%2$s', SFS::VERSION, PHP_VERSION);
	}

	/**
	 * Sends the StopForumSpam API _GET request, based on the chunks of information we are looking for.
	 * @return SFSResult - The results of the lookup.
	 *
	 * @throws SFSRequestException
	 *
	 * @todo maybe rewrite this, it's a hell of a mess and provides no way to force one method or the other
	 */
	public function send()
	{
		if(empty($this->username) && empty($this->email) && empty($this->ip))
			throw new SFSRequestException('No request data provided for SFS API request', SFSRequestException::ERR_NO_REQUEST_DATA);

		if(function_exists('curl_init'))
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $this->buildURL());
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT, $this->sfs->getRequestTimeout());
			curl_setopt($curl, CURLOPT_USERAGENT, $this->buildUserAgent());
			$json = curl_exec($curl);
			if(curl_errno($curl))
			{
				if(@ini_get('allow_url_fopen'))
				{
					// Setup the stream timeout, just in case
					$ctx = stream_context_create(array(
						'http'	=> array(
							'timeout'	=> $this->sfs->getRequestTimeout(),
						),
					));

					$json = @file_get_contents($this->buildURL() . '&useragent=' . urlencode($this->buildUserAgent()), false, $ctx);
				}
				else
				{
					throw new SFSRequestException('No reliable method is available to send the request to the StopForumSpam API', SFSRequestException::ERR_NO_REQUEST_METHOD_AVAILABLE);
				}
			}
			curl_close($curl);

			unset($curl);
		}
		elseif(@ini_get('allow_url_fopen'))
		{
			// Setup the stream timeout, just in case
			$ctx = stream_context_create(array(
				'http'	=> array(
					'timeout'	=> $this->sfs->getRequestTimeout(),
				),
			));

			$json = @file_get_contents($this->buildURL() . sprintf('&useragent=%1$s', urlencode('SFSIntegration_PHP-' . SFS::VERSION . ' PHP ' . PHP_VERSION)), false, $ctx);
		}
		else
		{
			throw new SFSRequestException('No reliable method is available to send the request to the StopForumSpam API', SFSRequestException::ERR_NO_REQUEST_METHOD_AVAILABLE);
		}

		// If no JSON response received, asplode.
		if(!$json)
			throw new SFSRequestException('No data recieved from SFS API', SFSRequestException::ERR_API_RETURN_EMPTY);

		// Be prepared in case we get invalid JSON...
		try
		{
			$data = OfJSON::decode($json, false);
		}
		catch(OfJSONException $e)
		{
			// Bad JSON, we'll chain the exception.
			// Also, due to how OfJSON is coded, this will return much more detailed errors in environments with PHP 5.3.0 or newer.
			throw new SFSRequestException(sprintf('Invalid JSON recieved from SFS API - %1$s', $e->getMessage()), SFSRequestException::ERR_API_RETURNED_BAD_JSON);
		}

		// Did the StopForumSpam API return an error?
		if(isset($data['error']))
			throw new SFSRequestException(sprintf('StopForumSpam API error: %1$s', $data['error']), SFSRequestException::ERR_API_RETURNED_ERROR);

		// Pass the requested data to the SFSResult object instantiation, so we know what we requested.
		$requested_data = array('username' => $this->username, 'email' => $this->email, 'ip' => $this->ip);

		return new SFSResult($this->sfs, $data, $requested_data);
	}
}
