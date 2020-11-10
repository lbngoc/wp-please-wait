=== WP PleaseWait - Loading Screen ===
Contributors: ngoclb
Donate link: https://ko-fi.com/ngoclb
Tags: pleasewait, loading, loader, spa, animation, loading screen, loading page, page effects, page loader, splash, splash screen, pwa
Stable tag: 2.2.3
Requires PHP: 5.4.33
Requires at least: 4.0
Tested up to: 5.5.3

Just an awesome splash screen for your website (or PWA), support 12+ spinner styles and many customizable things - message text, spinner size, background, text color...

== Description ==

[WP PleaseWait](https://github.com/lbngoc/wp-please-wait) is a wordpress plugin for [pleasewait](https://pathgather.github.io/please-wait) library.
PleaseWait is a small Javascript library that shows a beautiful loading page for your SPA while it loads.

= Features: =
* 12+ awesome spinners (powered by SpinKit v2)
* Optimized script for best user experience
* Custom rules for enable/disable loading screen on the website
* Custom loading message by filter or randomize
* Support Live Preview with Customization (Appearance / Customize)
* Support loading assets from server or using CDN
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
2. WP PleaseWait Settings
3. WP PleaseWait Loading Screen

== Changelog ==

**2.2.3**
- Fix some issues when running with old PHP version. You could skip this version if v2.2.2 working correctly on your server.

**2.2.2**
- Enable/Disable loading screen by add "Display Scopes" option
- Add new filter name "wp_pleasewait_enable" for customize where to show loading screen
- Support localization

**2.1.0**
- Update to SkinKit v2.0.1
- Preview Spinner style after selected

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
