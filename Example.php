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
 * The following is an example file demonstrating how the
 * StopForumSpam integration library should be prepared for use
 * within your own code.
 */

// Define the required root include path for the StopForumSpam integration library, and for the OpenFlame Framework.
define('SFSLIB', dirname(__FILE__) . '/src/');
define('OF_ROOT', dirname(__FILE__) . '/vendor/OpenFlame/src/');

// Include our main file here.  We need this as it houses the autoloader.
require SFSLIB . 'SFS.php';

// Register our own autoloader here.
spl_autoload_register('SFS::loader');

// Instantiate the main StopForumSpam interaction object.
$sfs = new SFS();

// Example of how to set a bunch of options on the SFS library before you request...
$sfs->setCacheTTL(43200)->setRequestTimeout(5);

try
{
	// @note $result is an object of type SFSResult, and can have its properties accessed as an array, or through its built-in methods.
	$result = $sfs->requestCheck('Some username here', 'someemail@email.tld', '127.0.0.2');
}
catch(SFSException $e)
{
	// error handling goes here, handle errors however you'd want.
	echo $e->getMessage() . PHP_EOL;
	exit;
}

// Examples of accessing the SFSResult data
print_r($result['username']['lastseen']); // object of type DateTime
var_dump($result['email']); // array containing all result data that is available for the email looked up
echo $result->getIPFrequency(); // integer of how many times the IP was found in the StopForumSpam database
