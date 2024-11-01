=== Comment Spam Filter by SpamLytics ===
Contributors: petervw
Donate link: https://spamlytics.com
Tags: spam, comments, google analytics, google analytics referral spam, blacklist, blacklist for wp, wp spam, anti-spam, comment spam, spam filtering service
Requires at least: 4.0
Tested up to: 4.4
Stable tag: 1.0.2

Block comment spam in WordPress with this plugin. Verify comments automatically with the SpamLytics API, and prevent comment spam. No captcha.

== Description ==

> **Main features of this plugin:**<br />
> * Automatically verify comments and check whether it's spam or not<br />
> * (partially) block Google Analytics referral spam<br />

= Comment Spam Filter =

Block comment spam automatically with this plugin. Use our service and verify up to 50 comments per month for free. You can upgrade your monthly limit on our [comment spam filtering](https://spamlytics.com/comment-spam-filtering-wp/) website.

When a new comment is posted on your site, it will be verified automatically with the SpamLytics API. Within a second, the comment is approved or marked as spam by our comment spam API.

We've developed our own algorithm in order to verify comments. Once a comment has passed the algorithm, we verify it with our comment blacklist. The comment spam blacklist is growing continuesly and we're improving the algorithm on a daily basis. This makes sure we can verify and recognize almost all spamm comments from your website.

Comment spam can be reduced a lot by using this plugin, and you don't have to use a captcha element anymore. This improves the UX on your website, and the comment spam will be reduced.

= Google Analytics Referral Spam =

This plugin uses the [SpamLytics blacklist](https://spamlytics.com/referral-spam/) with 495+ sources of traffic with referral spam in Google Analytics. This plugin filters these bots out on your WordPress site.

You need a free API key to get daily updates from SpamLytics, the API key could be generated from your WordPress dashboard.

Note: this plugin can only filter out bots who actually visit your website. The SpamLytics premium service will create segments and filters automatically in your Analytics account in order to filter (almost) everything for you.

== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.

Note: This plugin needs at least PHP 5.3+ to work as expected. PHP 5.2 is NOT supported. This plugin needs the PHP modules: cURL and filter.

== Frequently Asked Questions ==

**Where can I report issues and bugs?**
The development of this plugin is on GitHub. You can help us by creating an issue directly in [the GitHub repository](https://github.com/SpamLytics).

**How much monthly checks do I have for free in order to verify comment spam?**
Everyone has 50 monthly checks for free. When you have more comments than that per month, you need to upgrade your API key. When you've upgraded your API key, you have a limit of 75.000 monthly checks to prevent comment spam on your site.

== Screenshots ==

1. The SpamLytics overview, with detailed statistics about your comment spam validations.
2. Manage the comment spam filtering settings for this plugin.
3. Re-verify comments to the comment API and make sure a comment is spam or safe.

== Changelog ==

= 1.0.2 - 19 April 2016 =

Improvements:

- Major UX improvements to handle user comment spam

= 1.0.1 - 4 April 2016 =

Improvements:

- Added a new feature, [comment spam filtering](https://spamlytics.com/comment-spam-filtering-wp/).

Fixes:

- Several small bug fixes
- Improved UX

= 1.0.0 - 20 December 2015 =

- Initial spam prevention by SpamLytics release