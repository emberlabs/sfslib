# StopForumSpam Integration Library

This library provides easy integration with the StopForumSpam lookup database, for easy integration in your own applications.
Please note that the library is not intended to be used as a firewall, and that the StopForumSpam service requests that no more than 5000 API queries per day be used.

**Copyright**: *(c) 2010 Damian Bushong*

**License**: *MIT License*

## Requirements

* PHP 5.2.3
* Installation of the OpenFlame Framework, available at http://github.com/OpenFlame/OpenFlame-Framework

## Installation

Via Linux, or git bash in msysgit for Windows

	git clone http://github.com/Obsidian1510/SFSIntegration.git
	cd ./SFSIntegration
	git submodule init
	git submodule update

This will download the StopForumSpam integration Library, along with its dependency, the OpenFlame Framework.

### Notes

If requested enough, I may fork out some of my original code for JSON handling from the OpenFlame Framework in order to make this library standalone.
In such an event however, the caching functionality of the library will be lost, as it would not be able to be ported.

Additionally, this library is not an official product of the StopForumSpam service.
All trademarks and copyrights are property of their owners.