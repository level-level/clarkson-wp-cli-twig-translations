# Clarkson WP-CLI Twig Translation commando

## What 
Parses all Twig files in your current themes `templates` directory to `.php` files. 

## How 

1. Install package via `composer require level-level/clarkson-wp-cli-twig-translations`.

1. Run `wp clarkson-twig-translations prepare-files` which dumps rendered `.php` files in a `dist` directory in your current active theme.

1. Make sure your PoEdit `.pot` or `.po` file configuration loads this `dist` folder when it updates from source like   `"X-Poedit-SearchPath-1: dist/rendered-templates\n"`

## Why
Free PoEdit doesn't scans Twig files. If you do want PoEdit to do this, buy the Pro version.


## Filters

- `clarkson_twig_translations_cache_path` alters the location where to parse to. 
- `clarkson_twig_translations_templates_path` alters the location where the `templates` are located in.
