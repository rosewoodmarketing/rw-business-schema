=== RW Business Schema ===
Contributors: rosewoodmarketing
Requires at least: 6.0
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.7
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

== Yoast SEO ==

Yoast already outputs an Organization for the site on every page. In single-location mode, with "Add these details to Yoast's Organization schema" checked (default) and Yoast > Settings > Site representation set to "Organization":

- The plugin adds its details into Yoast's Organization through the `wpseo_schema_organization` filter: business type (added alongside "Organization"), address, phone, email, hours, price range, description, image, geo, map link, service area and social profiles.
- No separate LocalBusiness block is printed, and the Target Page setting is ignored. Search engines see one business on every page.
- Yoast keeps its name, @id and logo. Social profiles from Yoast and this plugin are combined; duplicates (including trailing-slash differences) are removed.

If Yoast is inactive, set to "Person", or the checkbox is off, the plugin prints its own LocalBusiness block (with `@id` `/#localbusiness`) on the Target Page only.

In Multi-Location mode each location prints its own block (`@id` = location URL + `#localbusiness`). When Yoast is active it links to the site's Organization with `parentOrganization`. Locations don't inherit the main office's latitude/longitude/map link.

== Logo: PNG, not SVG ==

Yoast can't read an SVG's dimensions and reports SVG logos as 1x1 pixels in the schema. Google requires a logo of at least 112x112px, so an SVG schema logo is likely to be ignored. PNG/JPG is also handled more reliably by social networks and AI tools.

- Upload a PNG logo (square-ish; 512x512px is a safe size) and set it in Yoast > Settings > Site representation > Organization logo. In Yoast mode that's the logo search engines get.
- Paste the same PNG URL in this plugin's "Logo / Image URL". It's used as the business image, and as the logo when Yoast isn't in use.
- The SVG can stay in the site header; this only affects the schema.

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

= 1.0.7 =
* Added Yoast SEO merge (single-location): business details are added to Yoast's site-wide Organization so search engines see one business on every page.
* Added schema fields: description, logo/image, geo coordinates, map link, social/directory profiles (sameAs) and service area (areaServed).
* Added @id to standalone and location schema; locations link to the Yoast Organization via parentOrganization.
* Locations no longer inherit the main office's coordinates or map link.
* Aggregate rating in schema is now opt-in.
* New installs default Saturday and Sunday to closed.
* Fixed GitHub updates that fall back to the zipball installing into a wrongly named folder.

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
