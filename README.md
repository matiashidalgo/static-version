# MHidalgo Static Version Module

## Introduction
This module provide the functionality required for add versioning 
to static content in order to avoid issues by browser cache. 
It's not so far from others modules but keeps 
more powerful and have more functionality, starting to be compatible
with CDN configurations and also has the functionality of inject version
into url path, in the same way Magento 2 do it as from version 2.1.3.

## Functionality
The module provide 4 types of versioning:
1. Static Query Param Version: This is the most basic and simple,
just add a query param to the url with desired content.
2. Dynamic Query Param Version: This is more sophisticate and smart
it detects the last change date of each file and use it as param content, 
then you always will get the last version on frontend.
3. Static Url Rename Version: This powerful and simple functionality 
provides a way to choose your desired version and inject it into the url 
it self, in the same way Magento 2 do it.
4. Dynamic Url Rename Version: This not just powerful but smart functionality
will give you the latest content by generate a new url injecting the last 
update time from the file.

## Extras
Static Version Module also gives you the posibility to choose to get a hash
for versions in order to improve your security by hiding what information are 
you using as version, also for query string you can choose the parameter name for 
version string.

## Installation
### Composer
Add the requirement `mhidalgo/static-version` and repository `git@bitbucket.org:matiashidalgo/static-version.git`
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
            "url": "git@bitbucket.org:matiashidalgo/static-version.git"
        }
        ...
    ]
}
```

### Modman
```bash
modman clone https://bitbucket.org/matiashidalgo/static-version.git
```

### Install manually
  * [Download the latest release](https://bitbucket.org/matiashidalgo/static-version)
  * Unzip
  * Copy `app`,`js` and `skin` directory into Magento