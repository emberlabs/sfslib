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
 * @note reserves 130xx error codes
 */
class SFSJSONException extends Exception
{
	const ERR_JSON_NO_FILE = 13000;
	const ERR_JSON_UNKNOWN = 13001;
	const ERR_JSON_NO_ERROR = 13002;
	const ERR_JSON_DEPTH = 13003;
	const ERR_JSON_CTRL_CHAR = 13004;
	const ERR_JSON_SYNTAX = 13005;
}
