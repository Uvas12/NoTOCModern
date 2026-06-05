# NoTOCModern

**NoTOCModern** is a MediaWiki extension that hides the table of contents by default on normal wiki pages, while still allowing editors to display it normally using MediaWiki's native `__TOC__` magic word.

The extension works by automatically applying MediaWiki's native `__NOTOC__` behavior when a page does not explicitly request a table of contents. If a page contains `__TOC__` or `__FORCETOC__`, NoTOCModern does nothing and lets MediaWiki display the table of contents normally.

NoTOCModern can also optionally hide the table of contents on special pages through a configuration variable.

## Features

- Hides the table of contents by default on normal wiki pages.
- Allows editors to show the table of contents using the native `__TOC__` magic word.
- Supports `__FORCETOC__` for forcing the table of contents in the default position.
- Uses MediaWiki's native `__NOTOC__`, `__TOC__`, and `__FORCETOC__` behavior.
- Does not require editing `MediaWiki:Common.css`.
- Does not create database tables.
- Includes optional support for hiding the table of contents on special pages.
- Keeps compatibility with older pages that may have used `__SHOWTOC__` by removing it from rendered output.

## Requirements

- MediaWiki 1.35 or later
- PHP version supported by your MediaWiki installation

## Installation

Download or clone this repository into the `extensions/` directory of your MediaWiki installation:

```bash
cd extensions
git clone https://github.com/Uvas12/NoTOCModern.git
```

Add the following line to your `LocalSettings.php` file:

```php
wfLoadExtension( 'NoTOCModern' );
```

Then go to `Special:Version` on your wiki to verify that the extension has been installed successfully.

## Configuration

### `$wgNoTOCModernHideSpecialPages`

By default, NoTOCModern does not affect special pages:

```php
$wgNoTOCModernHideSpecialPages = false;
```

To enable NoTOCModern on special pages, add the following configuration to your `LocalSettings.php` file:

```php
$wgNoTOCModernHideSpecialPages = true;
```

Recommended setup:

```php
wfLoadExtension( 'NoTOCModern' );

$wgNoTOCModernHideSpecialPages = true;
```

When this option is enabled, NoTOCModern hides the table of contents on special pages using inline CSS.

## Usage

### Hide the table of contents by default

NoTOCModern hides the table of contents automatically on normal wiki pages.

You do not need to add anything to the page.

Example:

```text
== History ==

=== Early years ===

=== Later years ===

== See also ==
```

Even if the page has enough headings to normally generate a table of contents, NoTOCModern will hide it by default.

### Show the table of contents on a page

To show the table of contents on a specific page, add MediaWiki's native `__TOC__` magic word:

```text
__TOC__

== History ==

=== Early years ===

=== Later years ===

== See also ==
```

The table of contents will appear at the exact position where `__TOC__` is placed.

### Force the table of contents in the default position

You can also use MediaWiki's native `__FORCETOC__` magic word:

```text
__FORCETOC__

== History ==

=== Early years ===

=== Later years ===

== See also ==
```

When `__FORCETOC__` is used, NoTOCModern does not hide the table of contents.

## Behavior summary

```text
Page without __TOC__       → TOC hidden by default
Page with __TOC__          → TOC shown where __TOC__ is placed
Page with __FORCETOC__     → TOC shown in the default position
Page with __NOTOC__        → TOC hidden
Special pages              → Controlled by $wgNoTOCModernHideSpecialPages
```

## Special pages

Special pages usually do not have normal editable wikitext. Because of that, `__TOC__` usually cannot be added directly to a special page.

NoTOCModern controls special pages through the following configuration variable:

```php
$wgNoTOCModernHideSpecialPages = true;
```

If this option is set to `true`, the table of contents will be hidden on special pages.

If this option is set to `false`, special pages are not affected.

Default value:

```php
$wgNoTOCModernHideSpecialPages = false;
```

## Backward compatibility

Older versions of this extension may have used:

```text
__SHOWTOC__
```

This marker is no longer required in the version 2.0.

NoTOCModern removes `__SHOWTOC__` from visible output for compatibility, but the recommended way to show the table of contents is now:

```text
__TOC__
```

## How it works

For normal wiki pages, NoTOCModern checks the page wikitext before MediaWiki finishes parsing it.

If the page contains:

```text
__TOC__
```

or:

```text
__FORCETOC__
```

NoTOCModern does nothing and allows MediaWiki to display the table of contents normally.

If the page does not contain either of those magic words, NoTOCModern automatically adds:

```text
__NOTOC__
```

at the beginning of the page content during parsing.

This makes MediaWiki hide the table of contents using its native behavior.

For special pages, NoTOCModern optionally hides table of contents elements using inline CSS when:

```php
$wgNoTOCModernHideSpecialPages = true;
```

