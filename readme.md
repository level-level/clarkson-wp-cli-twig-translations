# Clarkson WP-CLI Twig Translation commando

# What 
Parses all Twig files in your current themes `templates` directory. 

# How 

1. Run `wp clarkson-twig-translations prepare-files` which dumps rendered `.php` files in a `dist` directory in your current active theme.

2. Make sure your PoEdit `.pot` file configuration loads this `dist` folder when it updates from source.

# Why
Free PoEdit doesn't scans Twig files. 


# Filters

- `clarkson_twig_translations_cache_path` alters the location where to parse to. 
- `clarkson_twig_translations_templates_path` alters the location where the `templates` are located in.
