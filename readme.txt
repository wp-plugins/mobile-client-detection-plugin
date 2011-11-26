=== Mobile Client Detection Plug-in ===

Contributors: syslogic
Donate link: http://www.codefx.biz/donations
Tags: plugin, mobile, phone, tablet, template, theme, detect, query_var, layout, page speed, platform, browser, google, android, iphone
Requires at least: 3.0.0
Tested up to: 3.2.1
Stable tag: 0.2

The Mobile Client Detection Plug-in provides query_vars 'platform' & 'browser'
for simply switching the layout within your theme (requires editing template files).

It can also be very helpful when it’s required to load different versions of CSS/JS code.

== Description ==

In case the mobile version of your blog shall have a different layout
or if you need to load alternate CSS/JS files for a specific browsers:
This plug-in provides the required variable for serving a customized version of your theme!!

Currently it can detect the following mobile platforms: Android Phones and Tablets,
Blackberry, iPad, iPhone, iPod, IE mobile, Kindle, SymbianOS, PalmOS, webOS and GoogleBot mobile.

Internet Explorer & Android version detection are supported -
so it's quite handy for fixing things for specific user-agents
(instead of using conditional CSS).

== Installation ==

1. Upload the 'mobile-client-detection-plugin' directory into the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu within WordPress.
3. It operates quite silently (unless debug output is enabled).

== Frequently Asked Questions ==

= How does it work ? =
The plugin just adds another query_var to each request (it uses PHP instead of mod_rewrite) -
which can be used to load different template file (not yet implemented) – or to create templates with multi device support.

= Which values can the query_var 'platform' return ? =
Currently it returns the tag for each known platform:
a)
android 1, android 2, blackberry, windows, iphone, ipod, iemobile, webos, symbian, googlebot-mobile
(or general: mobile).
b)
android 3,android 4, ipad, kindle
(or general: tablet).
c)
windows, win64, wow64, macintosh, ppx mac os x, intel mac os x
(or general: desktop).

Set option $general_only=true; in case you require the general result.

The value 'desktop' can be (most likely) considered as a desktop PC -
since there’s pretty much any popular mobile platform covered.

= Which values can the query_var 'browser' return ? =
Currently it returns the tag for each known browser:
a) 
fennec, iemobile, mobile safari, googlebot-mobile (or general: mobile).
b) 
msie 5,msie 6, msie 7, msie 8, msie 9, chrome, camino, firefox, safari (or general: desktop)
c) 
w3c_validator, googlebot (or general: bot)

= Does this plugin have any options ? =
There’s currently 2 options:
$general_only = true/false – return only 'mobile', 'desktop' or 'tablet'
$footer_output = true/false – append output to footer (good for testing purposes)

= Which features will be implemented next ? =
a) add option toggles to the admin panel
b) add template-switching by filename capability

== Screenshots ==

There are currently no screenshots – since the settings page does not exist yet.

== Changelog ==
= 0.6 =
* Tablet detection added (thanks to Mystech)
* Android & IE Version detection added
* Mobile Safari detection fixed
* readme.txt updated

= 0.5 =
* Support for Kindle & Symbian added
* Browser detection added

= 0.4 =
* 'Footer Output' option added.
* 'General results only' option added.

= 0.3 =
* Example added.

= 0.2 =
* Initial release.

= 0.1 =
* Proof of concept.