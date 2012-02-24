###############################################
Google Map Basic
Pre 0.1 proof of concept
###############################################

Developer
-----------------------------------------------
Nicolaas Francken [at] sunnysideup.co.nz

Requirements
-----------------------------------------------
SilverStripe 2.3.0 or greater.

Notes
-----------------------------------------------
This re-write makes use of the V3 engine for Google Maps API

Now you can add your account API Key in your SiteConfig. If you do not add
a key, the map will still work, but with a limited number of requests. 


Installation Instructions
-----------------------------------------------
1. Download and add module to the folder "googlemapbasic" in your SilverStripe root directory.

2. Visit http://www.your-site-url.com/dev/build/?flush=all in your browser.

3. Add <% include GoogleMapBasic %> to your template.

4. Log into the CMS, then visit Pages.

5. Select a page to add your Map to, and cick the "behaviour" tab.

6. Enable maps using the new option, then save. Now open the "Main" tab and use the maps sub tab.

7. review on screen and code CSS for the right look and feel.
