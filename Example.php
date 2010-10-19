<?php

define('SFSLIB', dirname(__FILE__) . '/src/');

define('OF_ROOT', dirname(__FILE__) . '/vendor/OpenFlame/src/');
require OF_ROOT . 'Of.php';
require OF_ROOT . 'OfException.php';

// Register the OpenFlame Framework autoloader
spl_autoload_register('Of::loader');

try
{
	$cache = new OfCache('JSON', dirname(__FILE__) . '/data/cache');
	Of::storeObject('cache', $cache);
}
catch(OfException $e)
{
	// it's recommended that you do something here regarding error handling if we failed to start up the cache
	echo $e->getMessage() . PHP_EOL;
	exit;
}

require SFSLIB . 'SFS.php';
require SFSLIB . 'SFSException.php';

// Register our own autoloader
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
