=== Mobile Client Detection Plugin ===

Contributors: syslogic
Donate link: http://www.codefx.biz/donations
Tags: plugin, mobile, phone, tablet, theme, detect, query_var, layout, switch, page speed, platform, browser
Requires at least: 3.0.0
Tested up to: 3.2.1
Stable tag: 0.7

The Mobile Client Detection Plug-in provides query_vars 'platform' & 'browser'
for simply switching the layout within your theme (requires editing template files).

It can also be very helpful when it’s required to load different versions of CSS/JS code.

== Description ==

In case the mobile version of your blog shall have a different layout
or if you need to load alternate CSS/JS files for a specific browsers:
This plug-in provides the required variable for serving a customized version of your theme!!

Currently it can detect the following mobile platforms:
1. Android Phones (Android 1.x & 2.x)
2. Android Tablets (Android 3.x & 4.x),
3. Blackberry
4. iPad, iPhone, iPod
5. IE mobile
6. Kindle
7. SymbianOS
8. PalmOS
9. webOS
10. GoogleBot mobile.

It also can detect several desktop platforms (e.g. Internet Explorer)
so it's quite handy for fixing things for specific user-agents
(instead of using conditional CSS).

== Installation ==

1. Upload the 'mobile-client-detection-plugin' directory into the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu within WordPress.
3. It operates quite silently (unless debug output is enabled).

== Frequently Asked Questions ==

= How does it work ? =
The plugin adds two query_vars to each request (it uses PHP instead of mod_rewrite) -
which can be used to create templates with multi-device support (implemented)
- or to load different themes & templates (not yet implemented).

= Which values can the query_var 'platform' return ? =
1. android-phone, blackberry, windows, iphone, ipod, iemobile, webos, symbian, googlebot-mobile (or general: mobile).
2. android-tablet, ipad, kindle (or general: tablet).
3. windows, win64, wow64, macintosh, ppx mac os x, intel mac os x (or general: desktop).

The value 'desktop' can be (most likely) considered as a desktop PC -
since there’s pretty much any popular mobile platform covered.

= Which values can the query_var 'browser' return ? =
1. fennec, iemobile, mobile safari, googlebot-mobile (or general: mobile).
2. msie 5,msie 6, msie 7, msie 8, msie 9, chrome, camino, firefox, safari (or general: desktop)
3. w3c_validator, googlebot (or general: bot)

= Does this plugin have any options ? =
1. 'General Only': Yes/No - limit results to mobile, tablet, deskop, bot
2. 'Debug Output': Yes/No - append debug output to the footer on front-end
3. 'Modus Operandi': Standard/Template overloading/Theme overloading - not implemented yet.

= Where can I find these options ? =
You can find the options in the menu under Settings > Client Detection.

= Which further core features will be implemented until v1.0 ? =
1. checking for existance of wrapped template files.
2. attempt to overload the wrapped template paths.

Hint: Currently the arrays get shown in debug output - but not loaded yet...

== Screenshots ==
1. Options 'General Results' and 'Debug Output' are currently available.
2. The Debug Output shows how the templates get wrapped dynamically.

== Changelog ==
= 0.7.3 =
* Template arrays added to debug output.

= 0.7.2 =
* Theme / Template detection by regex added.
* path-wrapping per platform is working.

= 0.7 =
* Basic options page added.
* option 'General Results' added.
* option 'Debug Output' added.

= 0.6 =
* Tablet detection added (thanks to Mystech).
* Android & IE Version detection added.
* Mobile Safari detection fixed.
* readme.txt updated.

= 0.5 =
* Support for Kindle & Symbian added.
* Browser detection added.

= 0.4 =
* 'Footer Output' option added.
* 'General results only' option added.

= 0.3 =
* Example added.

= 0.2 =
* Initial release.

= 0.1 =
* Proof of concept.