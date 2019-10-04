# Coral - Remote Images

## Save space and download time!  Any linked images in the database content (wp-content/uploads folder) will be pulled from the live site URL.

### Description
Do you work with GIT repositories and develop WordPress websites locally? Have you found it time consuming and difficult to develop locally on a large WordPress website that has thousands of images? Does your development site get out of date within months due to active content management on the live site?

Activate this plugin on your local or development environment, and any linked images in the database content (wp-content/uploads folder) will be pulled from the live site URL.

### Installation

1. Upload the plugin files to the `/wp-content/plugins/coral-remote-images` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Done!

### Frequently Asked Questions

*The plugin didn't recognize the live URL*

Yea, it might not. When we at Big Sea build websites locally, we tend to import the live database, and modify the URL for our local installs via PHP constants. If you do not do that, and modify the database, you can tell Coral Remote Images what the live URL is by setting:

`define('BSD_CORAL_LIVE_URL', 'http://yourlivesiteURL.com');`

Add this in your `wp-config.php` or theme's `functions.php`. This is the location of your Wordpress install, not your site's root (if it varies)

### Contributors
*Big Sea, Soben*

* Donate link: http://bigseadesign.com/
* Tags: development, testing, local, uploads, images
* Requires at least: 3.4.1
* Tested up to: 4.7
* Stable tag: 1.1
* License: GPLv2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.html

### Upgrade Notice

_1.1_
* Admin Panel settings page, for those who don't have full FTP access to an environment.

_1.0_
* Initial Release.