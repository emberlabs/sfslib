# StopForumSpam Integration Library

This library provides easy integration with the StopForumSpam lookup database, for easy integration in your own applications.
Please note that the library is not intended to be used as a firewall, and that the StopForumSpam service requests that no more than 10000 API queries per day be used.

**Copyright**: *(c) 2010 - 2011 Damian Bushong*

**License**: *MIT License*

## Requirements

* PHP 5.3.0
* Ability to open external network connections in PHP
* JSON support in PHP

## Warning

This library is being rewritten and is not currently stable enough for production use.

## Installation

Via Linux, or git bash in msysgit for Windows

	git clone http://github.com/damianb/SFSIntegration.git
	cd ./SFSIntegration

This will download the StopForumSpam integration library.

Optionally, you may verify the phar archive if you have the phar-util package installed via PEAR.

### Compiling an updated PHAR package

Install the phar-util package <http://github.com/koto/phar-util> via PEAR, if you have not done so already
    $ sudo pear channel-discover pear.kotowicz.net
    $ sudo pear install kotowicz/PharUtil-beta

Make changes to the files in the **src/** directory, then build the package (without signing it)
    $ ./build/unsigned-build.sh

Using the compile-on-commit script (without signing it)
    $ cp build/hooks/autobuild-unsigned .git/hooks/pre-commit

### Notes

This library is not an official product of the StopForumSpam service.
All trademarks and copyrights are property of their owners.
