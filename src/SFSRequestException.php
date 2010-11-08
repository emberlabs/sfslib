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
 * SFS Integration - Subordinate exception class,
 *      Extension of the SFS exception class.
 *
 *
 * @package     sfsintegration
 * @author      Damian Bushong
 * @license     MIT License
 * @link        http://github.com/Obsidian1510/SFSIntegration
 *
 * @note reserves 11xxx error codes
 */
class SFSRequestException extends SFSException
{
	// SFSRequest exception constants
	const ERR_NO_REQUEST_DATA = 13000;
	const ERR_API_RETURN_EMPTY = 13001;
	const ERR_API_RETURNED_BAD_JSON = 13002;
	const ERR_API_RETURNED_ERROR = 13003;
	const ERR_NO_REQUEST_METHOD_AVAILABLE = 13004;

	// SFSRequestResult exception constants
	const ERR_NO_ACCESS_DATA_ARRAYACCESS = 13100;
	const ERR_NO_MODIFY_DATA_ARRAYACCESS = 13101;
}
