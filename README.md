# Google Maps Module

Silverstripe module designed to allow embeding of google maps on a page

## Developer

Nicolaas Francken [at] sunnysideup.co.nz
Mo: info [at] i-lateral.com

## Requirements

SilverStripe 3.0 or greater.
[SortableGridField](https://github.com/UndefinedOffset/SortableGridField)

## Notes

Heavily re-written version of [GoogleMapsBasic](https://github.com/sunnysideup/silverstripe-googlemapbasic) module. 

This re-write makes use of the V3 engine for Google Maps API, plus it also
allows for multiple maps to be embeded on a page that can be acessed in the same
manner as any other object set.

Now you can add your account API Key in your SiteConfig. If you do not add
a key, maps will still work, but with a limited number of requests.

This re-write also makes use of the [gmap3](http://gmap3.net/) jquery plugin. 

## Installation Instructions

1. Download and add module to the folder "googlemaps" in your SilverStripe root directory.

2. Add $GoogleMaps to your page template.

3. Visit http://www.your-site-url.com/dev/build/?flush=all in your browser.

4. Log into the CMS, then visit Pages.

5. Select a page to add your Map to, and cick the "settings" tab.

6. Enable maps using the new option, then save. Now open the "Content" tab and use the maps sub tab.

7. review on screen and code CSS for the right look and feel.
