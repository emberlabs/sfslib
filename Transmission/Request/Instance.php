<?php
/**
 *
 *===================================================================
 *
 *  StopForumSpam integration library
 *-------------------------------------------------------------------
 * @package     sfslib
 * @author      emberlabs.org
 * @copyright   (c) 2010 - 2011 emberlabs.org
 * @license     MIT License
 * @link        https://github.com/emberlabs/sfslib
 *
 *===================================================================
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 *
 */

namespace emberlabs\sfslib\Transmission\Request;
use \Codebite\StopForumSpam\Error\InternalException;

/**
 * StopForumSpam integration library - Request Instance object
 * 	     Represents the request to be made of the StopForumSpam API.
 *
 * @package     sfsintegration
 * @author      Damian Bushong
 * @license     http://opensource.org/licenses/mit-license.php The MIT License
 * @link        https://github.com/damianb/SFSIntegration
 */
class Instance
{
	/**
	 * @var array - Array of usernames to check against StopForumSpam
	 */
	protected $username = array();

	/**
	 * @var array - Array of email addresses to check against StopForumSpam
	 */
	protected $email = array();

	/**
	 * @var array - Array of IP addresses to check against StopForumSpam
	 */
	protected $ip = array();

	/**
	 * @var integer - The number of data points currently to be queried.
	 */
	protected $num_datapoints = 0;

	/**
	 * @var \emberlabs\sfslib\Transmission\Request\Result - The result object.
	 */
	protected $result;

	/**
	 * Obtain a new instance of this object
	 * @return \emberlabs\sfslib\Transmission\Request\Instance - Provides a fluent interface.
	 */
	public static function newInstance()
	{
		return new self();
	}

	/**
	 * Build the URL params to query StopForumSpam with.
	 * @return string - The URL param string to use.
	 */
	public function buildURL()
	{
		$username = $email = $ip = '';
		if(!empty($this->username))
		{
			$this->username = array_map('rawurlencode', $this->username);
			if(count($this->username) > 1)
			{
				$username .= 'username[]=' . implode('&username[]=', $this->username);
			}
			else
			{
				$username .= 'username=' . reset($this->username);
			}
		}

		if(!empty($this->email))
		{
			$this->email = array_map('rawurlencode', $this->email);
			if(count($this->email) > 1)
			{
				$email .= 'email[]=' . implode('&email[]=', $this->email);
			}
			else
			{
				$email .= 'email=' . reset($this->email);
			}
		}

		if(!empty($this->ip))
		{
			$this->ip = array_map('rawurlencode', $this->ip);
			if(count($this->ip) > 1)
			{
				$ip .= 'ip[]=' . implode('&ip[]=', $this->ip);
			}
			else
			{
				$ip .= 'ip=' . reset($this->ip);
			}
		}

		$url = implode('&', array($username, $email, $ip));

		return $url;
	}

	/**
	 * Sets the username that we are transmitting.
	 * @var string $username - The username to check.
	 * @return \emberlabs\sfslib\Transmission\Request\Instance - Provides a fluid interface.
	 *
	 * @throws
	 */
	public function setUsername($username)
	{
		if($this->num_datapoints >= 15)
		{
			trigger_error('Maximum number of data points to query reached for request instance', E_USER_WARNING);
			return $this;
		}

		if($this->result !== NULL)
		{
			throw new \Exception(); // @todo exception
		}

		$this->username[] = $username;
		$this->num_datapoints++;

		return $this;
	}

	/**
	 * Sets the email that we are transmitting.
	 * @var string $email - The email address to check.
	 * @return \emberlabs\sfslib\Transmission\Request\Instance - Provides a fluid interface.
	 *
	 * @throws
	 */
	public function setEmail($email)
	{
		if($this->num_datapoints >= 15)
		{
			trigger_error('Maximum number of data points to query reached for request instance', E_USER_WARNING);
			return $this;
		}

		if($this->result !== NULL)
		{
			throw new \Exception(); // @todo exception
		}

		if(filter_var($email, FILTER_VALIDATE_EMAIL) === false)
		{
			throw new InternalException('Invalid email address supplied'); // @todo exception
		}

		$this->email[] = $email;
		$this->num_datapoints++;

		return $this;
	}

	/**
	 * Sets the IP that we are transmitting.
	 * @var string $ip - The IP to check.
	 * @return \emberlabs\sfslib\Transmission\Request\Instance - Provides a fluid interface.
	 *
	 * @throws
	 */
	public function setIP($ip)
	{
		if($this->num_datapoints >= 15)
		{
			trigger_error('Maximum number of data points to query reached for request instance', E_USER_WARNING);
			return $this;
		}

		if($this->result !== NULL)
		{
			throw new \Exception(); // @todo exception
		}

		/**
		 * Validation will check for reserved or private IP ranges
		 *
		 * Validation will fail on the following IP ranges:
		 * 0.0.0.0/8
		 * 10.0.0.0/8
		 * 169.254.0.0/16
		 * 172.16.0.0/12
		 * 192.0.2.0/24
		 * 192.168.0.0/16
		 * 224.0.0.0/4
		 * FC* (IPv6)
		 * FD* (IPv6)
		 */
		if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 & FILTER_FLAG_IPV6 & FILTER_FLAG_NO_PRIV_RANGE & FILTER_FLAG_NO_RES_RANGE) === false)
		{
			throw new InternalException('Invalid IP address supplied'); // @todo exception
		}

		$this->ip[] = $ip;
		$this->num_datapoints++;

		return $this;
	}

	public function getResponse()
	{
		return $this->result;
	}

	public function newResponse($json)
	{
		if($this->result !== NULL)
		{
			throw new \Exception(); // @todo exception
		}

		$this->result = new \emberlabs\sfslib\Transmission\Request\Result($this, $json);
		return $this->result;
	}

	public function __destruct()
	{
		$this->result = NULL;
	}
}
