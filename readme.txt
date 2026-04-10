=== RW Business Schema ===
Contributors: rosewoodmarketing
Requires at least: 6.0
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Manage Local Business schema from one source with Elementor dynamic fields, shortcodes, Google Business Profile sync, and GitHub updates.

== Description ==

RW Business Schema lets you manage your business data once and reuse it everywhere:

- LocalBusiness JSON-LD output
- Business type selector (Restaurant, Hotel, Medical Clinic, Store, etc.)
- Required contact and address fields
- Schema hours by day and display hours text
- Elementor dynamic field tag
- Shortcodes for card, hours, single field, and reviews
- Google Business Profile rating/review sync
- GitHub-based plugin auto-updates

== Shortcodes ==

- [rw_business_card]
- [rw_business_hours]
- [rw_business_field field="company_name"]
- [rw_business_reviews count="5"]

== Changelog ==

= 1.0.1 =
* Fixed admin menu routing when Multi-Location is enabled so Business Schema consistently opens Settings first.
* Fixed missing Settings submenu edge cases by normalizing submenu ordering.
* Kept setup/welcome instructions visible on the settings page.

= 1.0.0 =
* Initial release
* Added business type selector with conditional fields
* Added LocalBusiness JSON-LD generator
* Added Elementor dynamic tag integration
* Added shortcodes
* Added Google Business Profile OAuth and sync
* Added GitHub auto updater
