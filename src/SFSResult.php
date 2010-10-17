<?php

class SFSResult /*implements ArrayAccess*/
{
	protected $sfs;

	protected $raw_data = array();

	protected $successful = false;

	protected $username = array();

	protected $username_data = '';

	protected $username_appears = false;

	protected $username_frequency = 0;

	protected $username_lastseen = '';

	protected $email = array();

	protected $email_data = '';

	protected $email_appears = false;

	protected $email_frequency = 0;

	protected $email_lastseen = '';

	protected $ip = array();

	protected $ip_data = '';

	protected $ip_appears = false;

	protected $ip_frequency = 0;

	protected $ip_lastseen = '';

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

		// at the end of all of this, run these methods...
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

	protected function getUsernameToArray()
	{
		return array(
			'data'			=> &$this->username_data,
			'appears'		=> &$this->username_appears,
			'frequency'		=> &$this->username_frequency,
			'lastseen'		=> &$this->username_lastseen,
		);
	}

	protected function getEmailToArray()
	{
		return array(
			'data'			=> &$this->email_data,
			'appears'		=> &$this->email_appears,
			'frequency'		=> &$this->email_frequency,
			'lastseen'		=> &$this->email_lastseen,
		);
	}

	protected function getIPToArray()
	{
		return array(
			'data'			=> &$this->ip_data,
			'appears'		=> &$this->ip_appears,
			'frequency'		=> &$this->ip_frequency,
			'lastseen'		=> &$this->ip_lastseen,
		);
	}
}
