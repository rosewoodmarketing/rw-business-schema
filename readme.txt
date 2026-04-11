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

Developer Notes: see `AI_DEVELOPER_SETUP.md` in the plugin root for full local setup, AI-assisted workflow, and update/release guidance.

== Shortcodes ==

- [rw_business_card]
- [rw_business_hours]
- [rw_business_field field="company_name"]
- [rw_business_reviews count="5"]

Optional location override:

- [rw_business_card location_id="123"]
- [rw_business_hours location_id="123"]
- [rw_business_field field="company_name" location_id="123"]

== Changelog ==

= 1.0.4 =
* Added context-aware Elementor dynamic field resolution for Multi-Location mode.
* Added automatic location resolution on mapped target pages and public singular location pages.
* Added optional public Location pages with a configurable site-wide location slug.
* Added context-aware shortcode support with an optional location_id override.
* Added schema output on public singular location pages.

= 1.0.3 =
* Added all remaining second-level business subtypes.
* Restructured business types into a nested hierarchy (parents with subtypes).
* Updated settings and location Business Type dropdowns to render subtype optgroups.
* Updated type lookup and validation logic to support nested type definitions via flat key resolution.

= 1.0.2 =
* Added schema output targeting using a page selector with manual path fallback in single-location mode.
* Added per-location schema target controls (page selector + path fallback) in Multi-Location mode.
* Hid single-location schema fields on Settings when Multi-Location mode is enabled.
* Prevented global single-location schema output while Multi-Location mode is enabled.

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
