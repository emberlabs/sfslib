# StopForumSpam Integration Library

## Changelog


Changes in 0.2.0:

* Removed OfCache dependency and native caching integration (users of the library are now responsible for caching on their own side)
* Ported JSON handler from the OpenFlame Framework
* Removed dependency and git submodule for OpenFlame Framework

Changes in 0.3.0:

* Fix example file for accuracy.
* Added new instantiatable class SFSReport for reporting spammers to StopForumSpam
* Created new base class SFSTransmission, base class to reduce redundant code between SFSReport and SFSRequest.  Both SFSReport and SFSRequest will extend this class.
* Added method SFS->newReport() to allow easy creation of SFSReport objects
* Added method SFS->setAPIKey() to set the SFS API Key for use when reporting spammers, this removes the need to set the API key on every instance of SFSReport.  If set as null, however, the API key will not be passed to SFSReport instances.
* Renamed class SFSResult to SFSRequestResult
* Merged exception classes SFSRequestException and SFSResultException
* Renamed SFS->getRequestTimeout() to SFS->getTimeout()
* Renamed SFS->setRequestTimeout() to SFS->setTimeout()
* Added ability to force a certain transmission method on reports and requests.  If the method fails, **no** fallback is used.
* Fix issue with SFSRequest not using SFSTransmission->buildUserAgent() for one certain transmission method
* Resorted exception error codes so that dominant classes get the lower error codes.
* Provide a DateInterval object via SFSRequestResult->getUsernameLastseenSpan(), SFSRequestResult->getEmailLastseenSpan(), and SFSRequestResult->getIPLastseenSpan() in the SFSRequestResult results, to show the time interval between now and when the username/email/IP was last reported.  Requires PHP 5.3 in order for this feature to be available, otherwise false will be returned for these methods when data is expected.
* Add scripts for building PHAR packages easily for PHP 5.3+ users.
