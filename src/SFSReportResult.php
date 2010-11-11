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
 *      Provides an interface to results obtained from reporting a spammer to the StopForumSpam API.
 *
 *
 * @package     sfsintegration
 * @author      Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/SFSIntegration
 */
class SFSReportResult
{
	/**
	 * @var SFS - The primary StopForumSpam object.
	 */
	protected $sfs;

	/**
	 * @var array - The array of raw data that we received from StopForumSpam.
	 */
	protected $raw_data;

	/**
	 * @var boolean - Was the report successful?
	 */
	protected $successful;

	/**
	 * @var SFSAPIError - An instantion of the error wrapper object if errors were returned from the API.
	 */
	protected $error;

	/**
	 * Constructor
	 * @param SFS $sfs - The primary StopForumSpam object
	 * @param array $data - The array of data returned from the StopForumSpam API.
	 * @return void
	 */
	public function __construct(SFS $sfs, array $data)
	{
		// waiting for pedigree's latest info on how the new API will return submission results.
		if(isset($data['error']))
		{
			$error = new SFSAPIError();
			$error->setAPIErrorCode($data['code'])->extractErrors();
			$this->error = $error;
		}
	}

	/**
	 * Retrieve whether or not the report was successful.
	 * @return boolean - Was the report successful?
	 */
	public function getSuccessful()
	{
		return $this->successful;
	}

	/**
	 * Get the errors that the StopForumSpam API returned to us.
	 * @return mixed - False if no errors encountered, or an array containing the errors encountered.
	 */
	public function getErrors()
	{
		if(is_null($this->error))
			return false;

		$errors = array();
		foreach($this->error as $error)
			$errors[] = $this->error->getDescription($error);

		return $errors;
	}
}
