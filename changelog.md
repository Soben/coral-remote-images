
### Changelog

_1.1_
* Added a settings page under 'Settings' called 'Remote Images'. This allows you to set the live url via the database. Will only be overridden by the BSD_CORAL_LIVE_URL PHP constant.

_1.0.3_
* [Fix] Discovered that we weren't compensating for SSL redirection. URLs to change to weren't properly being determined.

_1.0.2_
* [Fix] Noticed during wp-cli activate that WP_SITEURL isn't set during the time that it's being utilized. Changed the location that it gets used, and swapped out some conditionals accordingly.

_1.0_
* Initial Release