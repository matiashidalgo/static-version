# MHidalgo Static Version Module

## Introduction
This module provide the functionality required for add versioning 
to static content in order to avoid issues by browser cache. 
It's not so far from others modules but keeps 
more powerful and have more functionality, starting to be compatible
with CDN configurations and also has the functionality of inject version
into url path, in the same way Magento 2 do it as from version 2.1.3.

## Functionality
The module provide 2 types of versioning:
1. Query Param Version: This is the most basic and simple,
                        just add a query param to the url with desired content.
2. Url Rename Version: This powerful functionality provides a way to inject a 
version into the url it self, in the same way Magento 2 do it.

Also you can choose source of version with this 2 options:
1. Static Version: This is as simple and manual as possible, you can define a version number
for all your files, this allow you to force flush all the content version.
2. Dynamic Version: This is more sophisticate and smart
                    it detects the last change date of each file and use it as version number, 
                    then you always will get the last version on frontend.

## Extras
Static Version Module also gives you the posibility to choose to get a hash
for versions in order to improve your security by hiding what information are 
you using as version, also for query string you can choose the parameter name for 
version string.

## Installation
### Composer
Add the requirement `mhidalgo/static-version` and repository `git@github.com:matiashidalgo/static-version.git`
```json
{
    "require": {
        "mhidalgo/static-version": "*"
    }
    ...
    "repositories": [
        ...
        {
            "type": "vcs",
            "url": "git@github.com:matiashidalgo/static-version.git"
        }
        ...
    ]
}
```

### Modman
```bash
modman clone https://github.com/matiashidalgo/static-version.git
```

### Install manually
  * [Download the latest release](https://github.com/matiashidalgo/static-version/releases/latest)
  * Unzip
  * Copy `app`,`js` and `skin` directory into Magento
  
## Configuration
Once the module is installed you must set it up by go to System -> Configuration -> Developer and set Enable to Yes.

Then, you will be able to setup the module in order to use it. By default it comes ready for use Query String version
with an static version number which will be hashed and "v" will be the query string.

You will find most of the configurations with comments explaining his own functionality.

### File Rename Configuration
In order to use file rename version type you must check which Web Server are you using, 
the module support Apache and Nginx, where you only needs to enable Mod Rewrite Module 
for Apache, and for Nginx case you must setup your host configuration in order to manage
 automatic rewrites for version url section, for this you should follow nginx.conf.sample file
 included with the module.
 
### File Rename Testing
Before to set File Rename as version type and after follow File Rename Configuration step you can test
file rename functionality doing this:

1. Get the url to some JS which is part of core Magento, for ex. Prototype:
http://BASE_URL/js/prototype/prototype.js
2. Add an string "version123456" after "js" and try it on your browser
http://BASE_URL/js/version123456/prototype/prototype.js
3. If you get the same js file if you get it by common url, it's working fine.
 
You can also do same flow with some url from skin area in order to test both areas.
