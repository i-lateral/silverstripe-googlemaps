# Google Maps Module Documentation

## Installation Instructions

The prefered method of instalation is composer:

```
composer require i-lateral/silverstripe-googlemaps
```

Alternativley download and add module to the folder "googlemaps" in your SilverStripe
root directory.

## Usage Instructions

First install the module (as above).

Run a dev/build (either via the command line or the browser).

Next add $GoogleMaps template variable to your page template (where you want the maps to appear).

Now log into the CMS, then visit Pages.

Select a page to add your Map to and cick the "Settings" tab.

An "Enable maps" option will be available, select and then save. Now open the "Content"
tab and use the maps sub tab.

Review on screen and code CSS for the right look and feel.

## Notes

This module is a heavily re-written version of the [GoogleMapsBasic](https://github.com/sunnysideup/silverstripe-googlemapbasic)
module created by [Sunnysideup](https://github.com/sunnysideup).

This re-write makes use of the V3 engine for Google Maps API, allows for
multiple maps to be embeded on a page that can be acessed in the same
manner as any other object set.

Now you can add your account API Key in your SiteConfig. If you do not add
a key, maps will still work, but with a limited number of requests.

This re-write also makes use of the [gmap3](http://gmap3.net/) jquery plugin.