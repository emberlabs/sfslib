<?php

define('SFSLIB', dirname(__FILE__) . 'src/');

define('OF_ROOT', dirname(__FILE__) . 'vendor/OpenFlame/src/');
require OF_ROOT . 'Of.php';
require OF_ROOT . 'OfException.php';

// Register the OpenFlame Framework autoloader
spl_autoload_register('Of::loader');

try
{
	Of::storeObject('cache', new OfCache('JSON', dirname(__FILE__) . 'data/cache'));
}
catch(OfException $e)
{
	// it's recommended that you do something here regarding error handling if we failed to start up the cache
}

require SFSLIB . 'SFS.php';
require SFSLIB . 'SFSException.php';

// Register our own autoloader
spl_autoload_register('SFS::loader');
