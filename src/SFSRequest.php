<?php

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
	 * @throws SFSException
	 */
	public function send()
	{
		if(empty($this->username) && empty($this->email) && empty($this->ip))
			throw new SFSException(); // @todo exception

		$json = file_get_contents($this->buildURL());

		// Be prepared in case we get invalid JSON...
		try
		{
			$data = OfJSON::decode($json, false);
		}
		catch(OfJSONException $e)
		{
			throw new SFSException(); // @todo exception
		}

		if($data['error'])
			throw new SFSException(); // @todo exception

		return new SFSResult($data);
	}
}
