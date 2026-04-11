# RW Business Schema - AI Developer Setup Guide

This guide is for developers who want to clone, run, modify, and ship updates to this plugin on another computer, with AI-assisted coding workflows.

## 1) What this plugin does

RW Business Schema is a WordPress plugin that:

- Stores business profile data (single-location mode)
- Supports a multi-location mode via a custom post type (`rw_bs_location`)
- Outputs JSON-LD schema on configured target pages/paths
- Exposes data to Elementor dynamic tags
- Exposes data through shortcodes
- Supports optional public location URLs with a configurable slug
- Includes Google Business Profile integration hooks and GitHub updater support

## 2) Required local tools

Install these on the target machine:

- PHP 7.4+ (8.1+ recommended)
- WordPress local environment (DevKinsta, LocalWP, Docker, etc.)
- MySQL/MariaDB
- Git
- A code editor (VS Code recommended)

Optional but useful:

- WP-CLI
- Xdebug

## 3) Install the plugin in WordPress

1. Place this plugin folder in your WordPress plugins directory.
2. Confirm the plugin path is:
   - `wp-content/plugins/rw-business-schema`
3. In wp-admin, go to Plugins and activate "RW Business Schema".

If moving from another machine/repo, bring over `.git` history if you want commit continuity.

## 4) First-run setup in wp-admin

1. Go to `Business Schema > Settings`.
2. Fill required fields for single-location mode (company/address/contact/etc.).
3. Configure schema target:
   - Target page and/or path fallback.
4. Save.

For multi-location setup:

1. Enable `Multi-Location Mode`.
2. Go to `Business Schema > All Locations`.
3. Add one or more locations.
4. For each location, set:
   - Business data
   - Business type
   - Type-specific fields
   - Schema target page/path

Optional public location pages:

1. In Settings, enable `Public Location Pages`.
2. Set `Location URL Slug` (example: `business-location`).
3. Save.
4. Go to `Settings > Permalinks` and click Save once to flush rewrite rules.

## 5) Key architecture map

Main bootstrap:

- `rw-business-schema.php`

Core classes:

- `includes/class-plugin.php` - boot/init hooks
- `includes/helpers.php` - global settings/defaults + helper functions
- `includes/class-admin.php` - settings page rendering/sanitization/admin asset localization
- `includes/class-locations.php` - location CPT, metabox save/load, location context resolver
- `includes/class-type-definitions.php` - business type hierarchy + groups
- `includes/class-schema-generator.php` - schema output routing and JSON-LD generation
- `includes/class-elementor-field-tag.php` - Elementor dynamic tag controls and rendering
- `includes/class-shortcodes.php` - shortcodes + location-aware data resolution

Admin templates:

- `admin/templates/settings-page.php`
- `admin/templates/metabox-location.php`
- `admin/templates/help-gbp.php`
- `admin/templates/help-shortcodes.php`

## 6) Current data resolution behavior

Single-location mode:

- Dynamic tags and shortcodes use global settings.

Multi-location mode:

- Resolver tries to find the current location by:
  1. Current singular location post
  2. Mapped target page ID
  3. Mapped target path
- If no location resolves, fallback is global settings.

Elementor dynamic tag source modes:

- Auto
- Global Settings
- Current Location
- Specific Location

## 7) Typical AI-assisted workflow

Use this pattern to make safe changes with AI tools:

1. Ask AI to inspect relevant files before editing.
2. Ask AI for a minimal patch plan.
3. Apply focused edits file-by-file.
4. Run syntax/lint checks after edits.
5. Test behavior in wp-admin + front end.
6. Update `readme.txt` changelog.
7. Commit with a clear message.

Recommended prompts:

- "Trace where this field is rendered, sanitized, and consumed at runtime."
- "Patch only the files required for this behavior change."
- "Run php -l on every changed PHP file and report failures."
- "Add a changelog entry for the completed feature set."

## 8) Quality gates before commit

Run PHP lint on changed files:

```bash
php -l includes/helpers.php
php -l includes/class-admin.php
php -l includes/class-locations.php
php -l includes/class-schema-generator.php
php -l includes/class-elementor-field-tag.php
php -l includes/class-shortcodes.php
php -l admin/templates/settings-page.php
php -l admin/templates/metabox-location.php
```

Check Git diff:

```bash
git --no-pager diff
```

Ensure changelog updated:

- `readme.txt`

## 9) Manual test checklist

Settings and save:

- Can save in single-location mode
- Can save in multi-location mode
- Target validation works

Multi-location:

- Location CPT appears only when multi-location is enabled
- Location data saves correctly
- Mapped page/path outputs the correct schema block

Public locations (if enabled):

- Public location URL resolves (`/<slug>/<location>/`)
- Schema outputs on singular location pages
- Slug changes apply after permalink flush

Elementor:

- Dynamic tag lists expected fields
- Source modes behave correctly
- Specific Location mode returns selected location data

Shortcodes:

- Default shortcodes render correctly
- `location_id` override works

## 10) Safe branching and release process

Suggested branch strategy:

- Feature work: `feature/<short-name>`
- Fix work: `fix/<short-name>`

Typical flow:

```bash
git checkout -b feature/location-resolver-improvements
git add .
git commit -m "Add location resolver improvements and tests"
git push origin feature/location-resolver-improvements
```

Before release:

1. Bump version in plugin header if required.
2. Add changelog entry.
3. Re-test key flows.
4. Tag release if your deployment expects tags.

## 11) Common pitfalls

- Forgetting to flush permalinks after changing location slug/rewrite behavior
- Editing only UI without sanitization updates
- Editing sanitization without runtime consumer updates
- Breaking fallback behavior between single and multi-location modes
- Forgetting changelog updates

## 12) Notes for future AI sessions

When using AI on a fresh machine/session, provide:

- Current plugin path
- Current branch
- The exact feature request
- Whether behavior should affect single-location, multi-location, or both
- Whether public location pages are enabled

This reduces incorrect assumptions and keeps patches focused.
