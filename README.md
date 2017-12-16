SMF-GitHub Issues
=================
*A Simple Machines Forum extension allowing users to interact with a GitHub issue tracker.*

This is a Simple Machines Forum (SMF)  wrapper for GitHub issue trackers. Using this, you can add an issue tracker to your SMF installation which works directly with your GitHub issue tracker.

Requirements / Notes:
* Simple Machines Forums, version 2 (Not sure how much, if any, work is required to fit it to SMF 1.0)
* A dummy GitHub account to handle posting and reading messages
 * This account will appear on GitHub to be the author of all forum posts
  * The original poster will be noted with profile hyperlink in the message body
  * The tracker on your forum will reflect the correct original posters
  * GitHub profiles on your forum will be italicized
 * This account is also required to increase the API call cap
* PHP requirements are pretty lenient; curl is required for API calls

To use:
Simply fill out the contents of `config.php` and modify `common.php` to suit your needs. Only the first section of `config.php` actually needs to be filled out. 
