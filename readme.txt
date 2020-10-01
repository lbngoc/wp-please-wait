=== WP PleaseWait ===
Contributors: ngoclb
Donate link: https://ko-fi.com/ngoclb
Tags: pleasewait, loading, loader, spa, animation, loading screen, loading page, page effects, page loader, spash, splash screen
Stable tag: 2.0.2
Requires PHP: 5.4.33
Requires at least: 3.3
Tested up to: 5.2.2

Display an awesome splash screen while your website loading assets. You can easily customize the background, text color, timing and everything!

== Description ==

[WP PleaseWait](https://github.com/lbngoc/wp-please-wait) is a wordpress plugin for [pleasewait](https://pathgather.github.io/please-wait) library.
PleaseWait is a small Javascript library that shows a beautiful loading page for your SPA while it loads.

= Features: =
* Auto place script for best user experience
* Testing mode for admin user
* Load assets from CDN
* Awesome spinner by SpinKit
* Custom loading message by filter or randomize
* Custom appearance styles from WP Admin
* Support [LiteSpeed Cache](https://wordpress.org/plugins/litespeed-cache/) (Turn off "CSS Combine" and "JS Combine" to make sure the plugin working perfectly)
* Support [Genesis framework](https://my.studiopress.com/themes/genesis/)
* Support [Roots Sage Starter Theme](https://roots.io/sage/)
* Support [BeTheme](https://themes.muffingroup.com/be/splash/)
* Support [Avada Theme](https://avada.theme-fusion.com/)

Project URL: [Ngoc L.B.](http://ngoclb.com/project/wp-please-wait)
Source Code URL: [https://github.com/lbngoc/wp-please-wait](https://github.com/lbngoc/wp-please-wait)

== Installation ==

Install from Wordpress Admin

1. Open plugin installing page *Plugins/Add New*
2. Search "WP PleaseWait"
3. Click button "Install Now", then "Activate" this plugin

Install custom

1. Download release package
2. Unzip folder
3. Move this folder into *[your wordpress path]/wp-content/plugins/* folder
4. Open WP-Admin and active this plugin

Just have fun.

== Screenshots ==

1. WP PleaseWait Settings
2. WP PleaseWait Loading Screen

== Changelog ==

**2.0.2**
- Fix bug display a white screen when LiteSpeed Cache plugin is activated

**2.0.2**
- Support LiteSpeed Cache plugin

**2.0**
- Add new option "Auto Mode"
- Add new option "Testing Mode"
- Allow `<h1>`, `<h2>`, `<h3>` tag in "Message Text" field

**1.0.4**
- Add new option: "Disappearance Delay Time"
- Add new spinner style "0-no-spinner" to show/hide the spinner
- Add new filter name "wp_pleasewait_message" for customize the message
- Support Avada Theme

**1.0.3**
- Add new option: "Messgage Position"
- Display random message on loading screen if user set multiple messages
- Force use custom hook name defined by user instead auto detect
- Support BeTheme

**1.0.2**
- Add .pg-loaded class to html tag after hide loading screen
- Support Sage starter theme

**1.0.1**
- Allow user input color for "Background Color" and "Text Color"
- Update screenshoot

**1.0.0**
- First release
