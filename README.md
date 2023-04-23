# Recipe

Recipe is a module for sharing cooking recipes. It provides a Recipe content type, an Ingredient database table referenced by an Ingredient reference field, and callbacks for recipe sharing and printing.

## History
Recipe is one of the oldest contrib modules for Drupal! The first lines of code were written in 2002.

Recipe existed before CCK and the Field API. It provided the content type via custom database tables, a practice which lasted through the 7.x-1.x development cycle. The Recipe module's purpose began to gradually diminish as fields became widely adopted, enabling site builders to create their own customized content types that had better integration with other APIs.

Today, the 1.x-2.x branch is built using standard field types from Backdrop Core as well as a custom field for Ingredient references. Think of this Recipe content type as starting point. It is expected that you will extend and modify recipes to meet your individual needs.

## Installation
- Install this module using the official Backdrop CMS instructions at
  https://backdropcms.org/guide/modules

- Add a recipe in Content > Add content > Recipe

## Credits
Current Drupal version maintainers:
- [scottprive](https://www.drupal.org/u/scottprive)
- [dcam](https://www.drupal.org/u/dcam)
- [brdwor](https://www.drupal.org/u/brdwor)
- [drawk](https://www.drupal.org/user/68658)
- [marble](https://www.drupal.org/user/49529)
- [thox](https://www.drupal.org/user/21913)

Ported to Backdrop CMS by [argiepiano](https://github.com/argiepiano)

Original Author: Moshe Weitzman <weitzman@tejasa.com>

## Current Backdrop maintainer
- [argiepiano](https://github.com/argiepiano)
- Seeking additional maintainers

## License
This project is GPL v2 software. See the LICENSE.txt file in this directory for
complete text.
