=== Submit Content ===
Plugin Name: Submit Content
Author: Bharat Thapa
Author URI: https://bharatt.com.np
Contributors: bharatthapa
Description: Submit posts and custom pots from anywhere on your website.
Tags: user post, frontend post, public post, submit custom post, submit post, visitor post, submit content
Version: 1.1
Stable tag: 1.1
Text Domain: submit-content
Domain Path: /languages
Requires at least: 4.9
Tested up to: 6.2.2
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html

Allows you to submit posts, and custom pots, from frontend.

== Description ==

**The most comprehensive Plugin for User-Generated Content!**

*Enable users to submit posts from the frontend of your website.*

Submit Content is a free and open source WordPress plugin maintained by bharat thapa that allows users to submit posts and custom posts from frontend of the WordPress website.

Submit Content is a free and open source WordPress plugin, and will be fully supported and maintained as long as is necessary.

At a glance, this plugin allows the following:

* Administrators can choose whether only logged in users can submit content or site visitors as well.
* Notify admin via email whenever a post or custom post is submitted by the user.
* Protect form from spams and bots by implementing Google's reCAPTCHA V3 service.
* You can choose what form fields to show in the frontend from plugin settings page.
* Use the shortcode tag in post, page or widget to allow users to submit content.

    [submitcontent id="1"]


### Core Features ###

* Includes a fast & secure post-submission form
* Display forms anywhere via shortcode or template tag
* You choose which fields to display on the form
* Receive email notification alerts for submitted posts
* AJAX for better user experience
* Rich Text Editor experience in the frontend form


### Form Features ###

* Google reCAPTCHA: v3 (hidden recaptcha)
* Stops spam via input validation, captcha, and hidden field
* Option to require users to be logged in to use the form
* AJAX for better user experience
* Rich Text Editor experience in the frontend form


*Submit Content is simple to use and built with clean, secure code via the WordPress APIs!*


### Privacy ###

__User Data:__ Submit Content enables users to submit post content. It collects data only from users who voluntarily submit content via the Submit Content form.

__Cookies:__ No cookies are used for any purpose in this plugin.

__Services:__ This plugin provides an option to enable Google reCaptcha, which is provided by Google as a third-party service. For details on privacy and more, please refer to official documentation for [Google reCAPTCHA](https://developers.google.com/recaptcha/). No other outside services or locations are accessed/used by this plugin.



== Installation ==

### How to install the plugin ###

1. Upload the plugin to your blog and activate
2. Configure your options via the plugin settings
3. Generate form via plugin settings page
4. Display the form via shortcode or template tag


### How to use the plugin ###

To display the form on any WordPress Post, Page, or widget, add the shortcode:

    [submitcontent id="1"]

__Note:__ the value of id can differ on your WordPress environment so make sure you copy the right shortcode id from plugin settings page.


### Customizing the form ###

Only way to customize the form is from plugin settings page. You can create as many shortcodes as you desire and the form fields can be selected when creating the shortcode in the plugin settings page.


### Displaying submitted posts ###

Submitted posts are handled by WordPress as regular WP Posts. So they are displayed along with your other posts according to your theme design.


### Upgrades ###

To upgrade Submit Content, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.


== Upgrade Notice ==

To upgrade Submit Content, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.


### Uninstalling ###

Submit Content cleans up after you delete the plugin via plugin screen. All plugin settings and database table will be removed from your database when the plugin is uninstalled via the Plugins screen. Plugin settings are retained if you deactivate the plugin.


== Screenshots ==

1. Submit Content settings page
2. Submit Content generate shortcodes page
3. Submit Content manage shortcodes page
4. Submit Content using shortcode in post/page 
5. Submit Content form on the frontend


== Frequently Asked Questions ==

**Default settings**

By default, the submitted content will be saved as a post type 'post' with a status of 'draft' in the database. But you can change these default behaviors by visiting plugin settings page.


**Featured image is not showing in the form**

* Make sure your theme supports the thumbnail


**Featured image is not uploading**

* Make sure your host allows uploading of images with size that is set in the plugin settings page.


**Can visitors also submit the content?**

Yes, they can and it can be controlled from plugin settings page.


**How to require login?**

Visit plugin settings page and check the checkbox: "Only loggedin users can submit" and save the settings.


**How to implement Google's reCAPTCHA v3 service?**

Just get the reCAPTCHA v3 keys from your google account and save the site key and secret keys in the plugin settings page fields.


**How to enable/disable email notification to admin when a post is submitted?**

There is an option in the plugin settings page "Send email to admin whenever content is submitted" to enable or disable that feature.


**Can I send email notification to another email instead of admin email?**

Yes, there is an additional email field available in plugin settings page to enable this feature.


== Changelog ==

= 1.1 =
Rich text editor added
Each shortcode is now able to save to different types of posts or custom posts

= 1.0 =
Initial release.