=== Mobile Client Detection Plugin ===

Contributors: syslogic
Donate link: http://www.codefx.biz/donations
Tags: plugin, mobile, phone, tablet, theme, detect, query_var, layout, switch, page speed, platform, browser
Requires at least: 3.0.0
Tested up to: 3.2.1
Stable tag: 0.8.2

The Mobile Client Detection Plugin can overload platform-specific templates and themes
and provides query_vars 'platform' & 'browser'.

== Description ==

In case the mobile version of your blog shall have a different layout
or when it’s required to load different versions of CSS/JS code.:

This plug-in can overload template files and theme directories - by the detected platform.

And it can provide query_vars 'platform' & 'browser' for creating multi-device templates.

Currently it can detect the following mobile platforms:
Android Phones (Android 1.x & 2.x), Android Tablets (Android 3.x & 4.x),
Blackberry, iPad, iPhone, iPod, IE mobile, Kindle, SymbianOS, PalmOS, webOS,
GoogleBot mobile.

It also can detect several desktop platforms (e.g. Internet Explorer)
so it's quite handy for fixing things for specific user-agents
(instead of using conditional CSS).

== Installation ==

1. Upload the 'mobile-client-detection-plugin' directory into the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu within WordPress.
3. It operates quite silently (unless debug output is enabled).

== Frequently Asked Questions ==

= How does it work ? =
The plugin adds two query_vars to each request (it uses PHP instead of mod_rewrite),
which can be used to create templates with multi-device support -
and it can send out platform-specific template-files or whole themes!!

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
3. 'Modus Operandi': default WP template / template-overloading / theme-overloading
4. 'Add query_vars platform & browser': Yes/No - those vars are available in any theme file.

= Where can I find these options ? =
You can find the options in the menu under Settings > Mobile Client Detection.

= Which further core features will be implemented until v1.0 ? =
1. adding a optional facility for logging & submitting user-agent strings.

== Screenshots ==
1. This screenshot shows the current options screen, as per Version 0.8.2.
2. The Debug Output shows how the template paths get wrapped dynamically (outdated screenshot).

== Changelog ==
= 0.8.2 =
* Template file overloading works.
* Theme directory overloading works.
* Checks if the templates file even exist.
* option 'Setup query_vars' added.

= 0.7.3 =
* Template arrays added to debug output.

= 0.7.2 =
* Theme / Template detection by regex added.
* path-wrapping per platform is working.

= 0.7.0 =
* Basic options page added.
* option 'General Results Only' added.
* option 'Footer Debug Output' added.

= 0.6.0 =
* Tablet detection added (thanks to Mystech for the request).
* Android & IE Version detection added.
* Mobile Safari detection fixed.
* readme.txt updated.

= 0.5.0 =
* Support for Kindle & Symbian added.
* Browser detection added.

= 0.4.0 =
* 'Footer Output' option added.
* 'General results only' option added.

= 0.3.0 =
* Example added.

= 0.2.0 =
* Initial release.

= 0.1.0 =
* Proof of concept.