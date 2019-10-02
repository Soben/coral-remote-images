# Changelog

### 2.0

* [Change] constant changed from `BSD_CORAL_LIVE_URL` to `CORAL_REMOTEIMAGES_PROD_URL`
* [Change] removed all references of `bsd` from filters and constants.

### 1.1

* Added a settings page under 'Settings' called 'Remote Images'. This allows you to set the live url via the database. Will only be overridden by the BSD_CORAL_LIVE_URL PHP constant.

### 1.0.3

* [Fix] Discovered that we weren't compensating for SSL redirection. URLs to change to weren't properly being determined.

### 1.0.2

* [Fix] Noticed during wp-cli activate that WP_SITEURL isn't set during the time that it's being utilized. Changed the location that it gets used, and swapped out some conditionals accordingly.

### 1.0

* Initial Release

## Upgrade Notice

### 1.1

* Admin Panel settings page, for those who don't have full FTP access to an environment.

### 1.0

* Initial Release.