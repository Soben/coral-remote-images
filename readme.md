# Coral Remote Images

Do you develop [WordPress](https://wordpress.org) websites locally? Have you found it time consuming and difficult to develop locally on large WordPress websites that has thousands of images? Does your development site get out of date within months due to active content management on the live site?

Activate this plugin on your local or development environment, and any linked images in the database (referencing your media Uploads folder such as `wp-content/uploads`) will reference from the live site URL.

Keeping the database in sync is still vital, but you will no longer need to worry about the uploads folder for your Media Attachments!

## Installation

1. Upload the plugin files to `/wp-content/plugins/coral-remote-images`.
1. Activate the plugin through the 'Plugins' screen in WordPress (or via [WP CLI](https://developer.wordpress.org/cli/commands/plugin/activate/))
1. Done (with some potential caveats below)!

## Usage

### Default Behavior

When pulling down the latest database from production, it's common to leave the database configurations alone and add `WP_HOME` and `WP_SITEURL` constants in your `wp-config.php` file for overriding the settings with your local URL.

If this is what you do, you're already configured! Coral Remote Images will see that your defined `WP_SITEURL` does not match the [siteurl](https://codex.wordpress.org/Function_Reference/site_url) in the `wp_options` table, and flags the option in the database as the 'production' url to switch to.

### wp-config.php

If you've modified the database, or want to test with another synced environment, you can add a constant to your `wp-config.php` file:

```
define('CORAL_REMOTEIMAGES_PROD_URL', 'http://yourlivesiteURL.com/path/to/wp');
```

The URL should match your [siteurl](https://codex.wordpress.org/Function_Reference/site_url) on production (the location of your WP core files).

### Settings Page

If you don't want to or can't modify your `wp-config.php`, there is a admin panel for managing your settings available at

WP Admin > Remote Images

## Notes

Originally built and released by [Chris Lagasse](https://chrislagasse.com) while at [Big Sea](https://bigsea.co), on the WordPress SVN plugin repository. The WordPress plugin directory is no longer up to date, and currently (2019-10-01) I have no plans to update it.

## @TODO

* Move the admin panel settings location page to Media.
* Update for WP 5.x
* Confirm all apply_filters and add more as needed.
* Define/clarify system requirements
* switch to composer/psr-2?